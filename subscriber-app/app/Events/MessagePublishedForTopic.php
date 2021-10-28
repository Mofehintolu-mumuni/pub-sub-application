<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessagePublishedForTopic implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $topic;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($topic, $message)
    {
        $this->message = $message;
        $this->topic = $topic;
    }


    public function broadcastWith()
    {
        // This must always be an array. Since it will be parsed with json_encode()
        return [
            'topic' => $this->topic,
            'message' => $this->message,
          
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('publish');
    }
}
