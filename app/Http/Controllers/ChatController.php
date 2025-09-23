<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\DocumentRequest;

class ChatController extends Controller
{
    public function fetch(Request $request)
    {
        $request->validate([
            'reference_number' => 'required|string'
        ]);
        $doc = DocumentRequest::where('reference_number', $request->reference_number)->firstOrFail();
        $messages = Message::where('document_request_id', $doc->id)
            ->orderBy('created_at')
            ->get(['sender_type','message','created_at']);
        return response()->json(['success' => true, 'messages' => $messages]);
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'reference_number' => 'required|string',
            'sender_type' => 'required|in:requester,registrar',
            'message' => 'required|string|max:2000',
        ]);
        $doc = DocumentRequest::where('reference_number', $validated['reference_number'])->firstOrFail();
        $msg = Message::create([
            'document_request_id' => $doc->id,
            'sender_type' => $validated['sender_type'],
            'sender_id' => auth()->id(),
            'message' => $validated['message'],
        ]);
        return response()->json(['success' => true, 'message' => [
            'sender_type' => $msg->sender_type,
            'message' => $msg->message,
            'created_at' => $msg->created_at->toDateTimeString(),
        ]]);
    }
}

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
