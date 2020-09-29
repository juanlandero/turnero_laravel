<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ShiftPanelMsg
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $text;
    public $channel;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($text , $channel)
    {
        $this->text = $text;
        $this->channel = $channel;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel($this->channel);
    }

    public function broadcastAs(){
        return 'toPanel';
    }
}
