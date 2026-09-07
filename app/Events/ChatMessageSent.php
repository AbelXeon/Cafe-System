<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ChatMessage $message;

    public function __construct(ChatMessage $message)
    {
        $this->message = $message;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('order.' . $this->message->order_id . '.chat'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        return [
            'id'          => $this->message->id,
            'order_id'    => $this->message->order_id,
            'sender_id'   => $this->message->sender_id,
            'sender_name' => $this->message->sender->fullname ?? $this->message->sender->name ?? 'User',
            'is_me'       => false, // recipients only receive this via toOthers(), so it's never their own message
            'message'     => $this->message->message,
            'created_at'  => $this->message->created_at->format('H:i'),
        ];
    }
}