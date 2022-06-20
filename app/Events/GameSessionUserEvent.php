<?php

namespace App\Events;

use App\Models\Game;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GameSessionUserEvent implements ShouldBroadcast
{
    /**
     * Este evento se va encargar de contar cuantas personas estan en la misma
     * sala a traves de la validacion del juego
     */
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $game;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Game $game)
    {
//        return dd($game);
        $this->game = $game;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
//        return dd($this->game->id);
        return new PresenceChannel('game.'. $this->game->id);
    }
    /*public function broadcastAs(){
        return 'gameSession';
    }*/
}
