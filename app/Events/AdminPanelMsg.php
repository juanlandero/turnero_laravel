<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdminPanelMsg implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $channel;
    public $idTicket;
    public $idSpeciality;
    public $box;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($channel, $idTicket, $idSpeciality, $box)
    {
        $this->channel = $channel;
        $this->idTicket = $idTicket;
        $this->idSpeciality = $idSpeciality;
        $this->box = $box;
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
        return 'nextShift';
    }
}
