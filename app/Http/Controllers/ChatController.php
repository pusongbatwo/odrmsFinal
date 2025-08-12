<?php
namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\DocumentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // Fetch all messages for a document request
    public function index($documentRequestId)
    {
        $messages = Message::where('document_request_id', $documentRequestId)
            ->orderBy('created_at', 'asc')
            ->get();
        return response()->json($messages);
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
}
