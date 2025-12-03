<?php
namespace App\Events;
use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $message;
    public $chat;

    public function __construct(Message $message)
    {
        $this->message = $message->load('attachments');
        $this->chat = $message->chat;
    }

    public function broadcastOn()
    {
        // private channel per chat
        return new PrivateChannel('chat.' . $this->chat->id_chat);
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message->toArray(),
            'chat' => [
                'id_chat' => $this->chat->id_chat,
                'last_message' => $this->chat->last_message,
                'last_message_at' => $this->chat->last_message_at,
            ]
        ];
    }
}
