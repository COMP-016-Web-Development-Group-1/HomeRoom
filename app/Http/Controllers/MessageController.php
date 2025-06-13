<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;

class MessageController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Get all conversations and filter in PHP for better compatibility
        $conversations = Conversation::with(['latestMessage.user'])
            ->get()
            ->filter(function ($conversation) use ($userId) {
                return $conversation->hasParticipant($userId);
            })
            ->sortByDesc('last_message_at')
            ->sortByDesc('created_at');

        return view('messages.index', compact('conversations'));
    }

    public function show(Conversation $conversation)
    {
        // Check if current user is participant
        if (!$conversation->hasParticipant(Auth::id())) {
            abort(403);
        }

        $messages = $conversation->messages()->with('user')->get();
        $otherParticipant = $conversation->getOtherParticipant(Auth::id());

        return view('messages.show', compact('conversation', 'messages', 'otherParticipant'));
    }

    public function store(Request $request, Conversation $conversation)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        // Check if current user is participant
        if (!$conversation->hasParticipant(Auth::id())) {
            abort(403);
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => Auth::id(),
            'content' => $request->content,
            'type' => 'text',
        ]);

        // Update conversation's last message timestamp
        $conversation->update([
            'last_message_at' => now(),
        ]);

        // Broadcast the message
        broadcast(new MessageSent($message->load('user')));

        if ($request->ajax()) {
            return response()->json([
                'message' => $message->load('user'),
                'success' => true
            ]);
        }

        return redirect()->route('messages.show', $conversation);
    }

    public function startConversation(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        $currentUserId = Auth::id();
        $recipientId = (int) $request->recipient_id;

        // Check if conversation already exists - filter in PHP for reliability
        $existingConversation = Conversation::all()
            ->filter(function ($conversation) use ($currentUserId, $recipientId) {
                $participants = array_map('intval', $conversation->participants);
                return in_array((int) $currentUserId, $participants) && in_array($recipientId, $participants);
            })
            ->first();

        if ($existingConversation) {
            return redirect()->route('messages.show', $existingConversation);
        }

        // Create new conversation
        $conversation = Conversation::create([
            'participants' => [(int) $currentUserId, $recipientId],
            'last_message_at' => now(),
        ]);

        // Create first message
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $currentUserId,
            'content' => $request->message,
            'type' => 'text',
        ]);

        // Broadcast the message
        broadcast(new MessageSent($message->load('user')));

        return redirect()->route('messages.show', $conversation);
    }
}
