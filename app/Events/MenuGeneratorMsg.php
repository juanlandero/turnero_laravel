<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MenuGeneratorMsg implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $channel;
    public $idTicket;
    public $idUser;
    public $isNew;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($channel, $idTicket, $idUser, $isNew)
    {
        $this->channel = $channel;
        $this->idTicket = $idTicket;
        $this->idUser = $idUser;
        $this->isNew= $isNew;
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
        return 'toPublicPanel';
    }
}
