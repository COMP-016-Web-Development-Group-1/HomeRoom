<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Storage;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Message $message)
    {
        //
    }


    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('conversation.' . $this->message->conversation_id);
    }

    public function broadcastWith(): array
    {
        return [
            'message' => [
                'id' => $this->message->id,
                'content' => $this->message->content,
                'user_id' => $this->message->user_id,
                'user' => [
                    'id' => $this->message->user->id,
                    'name' => $this->message->user->name,
                    'profile_picture' => $this->message->user->profile_picture
                        ? Storage::url($this->message->user->profile_picture)
                        : asset('assets/images/default_profile.png'),
                ],
                'created_at' => $this->message->created_at->format('g:i A'),
                'conversation_id' => $this->message->conversation_id,
            ]
        ];
    }
}
