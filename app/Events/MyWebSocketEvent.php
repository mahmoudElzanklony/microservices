<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;


class MyWebSocketEvent implements ShouldBroadcast
{

    /**
     * Create a new event instance.
     */
    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('messages');
    }

    public function broadcastAs()
    {
        return 'message.sent';
    }
}
