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
//    return dd($session);
    return $session;
//    return (int) $game[0]->id === (int) $id  ;
//    return true;
});
