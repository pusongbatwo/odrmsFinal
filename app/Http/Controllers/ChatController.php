<?php
namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\DocumentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Events\MessageSent;

class ChatController extends Controller
{
    // Fetch all messages for a document request
    public function index($documentRequestId)
    {
        // If documentRequestId is a reference_number (string), try to resolve to id
        $documentRequest = DocumentRequest::where('id', $documentRequestId)->orWhere('reference_number', $documentRequestId)->first();
        if (! $documentRequest) {
            return response()->json(['success' => false, 'message' => 'Document request not found'], 404);
        }

        $messages = Message::where('document_request_id', $documentRequest->id)
            ->orderBy('created_at', 'asc')
            ->get();

        // Optionally mark as read when called from registrar UI
        $markRead = request()->query('mark_read');
        $viewer = Auth::user();
        $viewerIsRegistrar = $viewer && in_array($viewer->role, ['registrar', 'admin', 'cashier']);
        if ($markRead && $viewerIsRegistrar) {
            foreach ($messages as $msg) {
                if ($msg->read_at === null && $msg->sender_type === 'requester') {
                    $msg->read_at = now();
                    $msg->save();
                }
            }
            $messages = Message::where('document_request_id', $documentRequest->id)
                ->orderBy('created_at', 'asc')
                ->get();
        }

        return response()->json($messages);
    }

    // Conversations list for registrar UI with unread counts
    public function conversationsForRegistrar()
    {
        $viewer = Auth::user();
        Log::info('conversationsForRegistrar called', ['viewer_id' => $viewer ? $viewer->id : null, 'viewer_role' => $viewer ? $viewer->role : null]);
        if (! $viewer || ! in_array($viewer->role, ['registrar', 'admin', 'cashier'])) {
            Log::warning('conversationsForRegistrar unauthorized', ['viewer_id' => $viewer ? $viewer->id : null]);
            return response()->json([], 403);
        }

        $conversations = [];
        $requests = DocumentRequest::orderBy('created_at', 'desc')->get();
        foreach ($requests as $req) {
            $last = Message::where('document_request_id', $req->id)->orderBy('created_at', 'desc')->first();
            $unreadCount = Message::where('document_request_id', $req->id)
                ->whereNull('read_at')
                ->where('sender_type', 'requester')
                ->count();

            $conversations[] = [
                'id' => $req->id,
                'reference_number' => $req->reference_number,
                'requester_name' => trim($req->first_name . ' ' . $req->last_name),
                'last_message' => $last ? $last->message : null,
                'last_message_at' => $last ? $last->created_at : null,
                'unread_count' => $unreadCount,
            ];
        }

        return response()->json($conversations);
    }

    // Store a new message
    public function store(Request $request, $documentRequestId)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $documentRequest = DocumentRequest::findOrFail($documentRequestId);
        $user = Auth::user();
        if ($user) {
            // Authenticated user
            $senderType = ($user->role === 'registrar') ? 'registrar' : 'requester';
            $senderId = $user->id;
        } else {
            // Guest/public user
            $senderType = 'requester';
            $senderId = null;
        }

        $message = Message::create([
            'document_request_id' => $documentRequestId,
            'sender_type' => $senderType,
            'sender_id' => $senderId,
            'message' => $request->message,
        ]);

        // Optionally: Log to system log here
        // SystemLog::create([...]);

        return response()->json($message, 201);
    }

    // Fetch messages by reference_number (used by web chat widget)
    public function fetch(Request $request)
    {
        $reference = $request->query('reference_number');
        Log::info('Chat fetch called', ['reference' => $reference, 'user_id' => Auth::id()]);
        if (! $reference) {
            Log::warning('Chat fetch missing reference');
            return response()->json(['success' => false, 'message' => 'Reference number is required'], 400);
        }

        $documentRequest = DocumentRequest::where('reference_number', $reference)->first();
        if (! $documentRequest) {
            return response()->json(['success' => false, 'message' => 'Document request not found'], 404);
        }

        $messages = Message::where('document_request_id', $documentRequest->id)
            ->orderBy('created_at', 'asc')
            ->get();

        // Optionally mark as read if requested and viewer is authorized
        $markRead = $request->query('mark_read');
        $viewer = Auth::user();
        $viewerIsRegistrar = $viewer && in_array($viewer->role, ['registrar', 'admin', 'cashier']);
        $viewerIsOwner = false;
        if ($viewer && !$viewerIsRegistrar) {
            if (!empty($viewer->username) && $viewer->username == $documentRequest->student_id) $viewerIsOwner = true;
            if (!empty($viewer->email) && $viewer->email == $documentRequest->email) $viewerIsOwner = true;
        }

        if ($markRead && ($viewerIsRegistrar || $viewerIsOwner)) {
            // Mark messages as read where the sender is the opposite party and read_at is null
            foreach ($messages as $msg) {
                // If viewer is registrar, mark requester messages as read; if viewer is owner, mark registrar messages as read
                if ($msg->read_at === null) {
                    if ($viewerIsRegistrar && $msg->sender_type === 'requester') {
                        $msg->read_at = now();
                        $msg->save();
                    } elseif ($viewerIsOwner && $msg->sender_type === 'registrar') {
                        $msg->read_at = now();
                        $msg->save();
                    }
                }
            }
            // Refresh messages after marking
            $messages = Message::where('document_request_id', $documentRequest->id)
                ->orderBy('created_at', 'asc')
                ->get();
        }

        return response()->json(['success' => true, 'messages' => $messages]);
    }

    // Send a message via web chat widget (accepts reference_number)
    public function send(Request $request)
    {
        $data = $request->validate([
            'reference_number' => 'required|string',
            'message' => 'required|string',
            'sender_type' => 'nullable|string',
            'sender_email' => 'nullable|email',
            'sender_mobile' => 'nullable|string',
        ]);

        Log::info('Chat send attempt', ['payload' => $data, 'user_id' => Auth::id()]);
        $documentRequest = DocumentRequest::where('reference_number', $data['reference_number'])->first();
        if (! $documentRequest) {
            Log::warning('Chat send: document not found', ['reference' => $data['reference_number']]);
            return response()->json(['success' => false, 'message' => 'Document request not found'], 404);
        }

        $user = Auth::user();
        $senderId = null;

        // Ownership/authorization checks
        if ($user) {
            // Allow registrar/admin/cashier to post
            if (in_array($user->role, ['registrar', 'admin', 'cashier'])) {
                $senderType = ($user->role === 'registrar') ? 'registrar' : 'requester';
                $senderId = $user->id;
            } else {
                // For requester users, ensure they own the document (match by username or email)
                $isOwner = false;
                if (!empty($user->username) && $user->username == $documentRequest->student_id) {
                    $isOwner = true;
                }
                if (!empty($user->email) && $user->email == $documentRequest->email) {
                    $isOwner = true;
                }
                if (! $isOwner) {
                    Log::warning('Chat send: requester user not owner', ['user_id' => $user->id, 'document_id' => $documentRequest->id]);
                    return response()->json(['success' => false, 'message' => 'Not authorized to post to this conversation'], 403);
                }
                $senderType = 'requester';
                $senderId = $user->id;
            }
        } else {
            // Guest: require sender_email OR sender_mobile that matches the document's contact
            $matchesEmail = !empty($data['sender_email']) && $data['sender_email'] === $documentRequest->email;
            $matchesMobile = !empty($data['sender_mobile']) && $data['sender_mobile'] === $documentRequest->mobile_number;
            if (! ($matchesEmail || $matchesMobile)) {
                Log::warning('Chat send: guest not authorized (contact mismatch)', ['provided_email' => $data['sender_email'] ?? null, 'provided_mobile' => $data['sender_mobile'] ?? null, 'document_email' => $documentRequest->email, 'document_mobile' => $documentRequest->mobile_number]);
                return response()->json(['success' => false, 'message' => 'Not authorized to post to this conversation'], 403);
            }
            $senderType = 'requester';
            $senderId = null;
        }
        $message = Message::create([
            'document_request_id' => $documentRequest->id,
            'sender_type' => $senderType,
            'sender_id' => $senderId,
            'message' => $data['message'],
        ]);

        Log::info('Chat send: message created', ['message_id' => $message->id, 'document_request_id' => $message->document_request_id, 'sender_type' => $message->sender_type]);

        // Broadcast via Laravel events so clients can receive it through Echo / websockets
        try {
            event(new MessageSent($message));
        } catch (\Exception $e) {
            Log::warning('Failed to broadcast MessageSent event', ['error' => $e->getMessage()]);
        }
        return response()->json(['success' => true, 'message' => $message], 201);
    }
}
