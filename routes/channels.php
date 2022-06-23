<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/


Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//    return (int)$user->id === (int)$id;
    return true;
});


Broadcast::channel('game.{id}', function ($game, $id) {

    $session = \App\Models\GameSession::where([['game_id', $game->id], ['user_id', \Illuminate\Support\Facades\Auth::user()->id]])->get();
    if($session){
        return json_encode(\Illuminate\Support\Facades\Auth::user());
    }
//    return json_encode($session);
});


Broadcast::channel('movTo.{x_index}', function($x_index){
    $message = ['msg'=> "Esta prueba me dira si se envia el evento" . $x_index];
    if ($message){
        echo $message;
        return json_encode($message);
    }
});
