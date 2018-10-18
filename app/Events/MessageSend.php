<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageSend implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;
    public $userSender;
    public $userReceiverId;
    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($userSender , $userReceiverId, $message)
    {
        $this->userSender = $userSender;
        $this->userReceiverId = $userReceiverId;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('App.User.'. $this->userReceiverId);
    }
}
