<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LatestMessageEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $unreadCount;

    public function broadcastAs(): string
    {
        return 'LatestMessageEvent';
    }
    public function __construct($message, $unreadCount)
    {
        $this->message     = $message;
        $this->unreadCount = $unreadCount;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('latest-message-channel.' . $this->message->conversation_id),
        ];
    }

    // Broadcast the event's data with frontend 
    public function broadcastWith(): array
    {
        return [
            'unreadCount'     => $this->unreadCount,
            'conversation_id' => $this->message->conversation_id,
            'receiver_id'     => $this->message->receiver_id,
            'sender_id'       => $this->message->sender_id,
        ];
    }
}
