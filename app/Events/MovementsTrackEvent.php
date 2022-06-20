<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use phpDocumentor\Reflection\Types\This;

class MovementsTrackEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $idCeldaImage;
    public $game_id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($idCeldaImage, $game_id)
    {

        $this->idCeldaImage = $idCeldaImage;
        $this->game_id = $game_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
//        $x_index = $this->x_index;
        return new PresenceChannel('game.' . $this->game_id);
    }
}
