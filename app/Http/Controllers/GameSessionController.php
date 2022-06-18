<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function MongoDB\BSON\toJSON;

class GameSessionController extends Controller
{
    public function addScore(Request $request){
        $request->validate([
            'user_id' => 'required',
            'game_id' => 'required',
        ]);

        $gameSession = GameSession::where([['game_id', $request->game_id],['user_id', $request->user_id]])->get();
        $id = $gameSession[0]->id;
        $score = $gameSession[0]->score;
        $score += 1;

        $session = GameSession::findOrFail($id);
        $session->score = $score;
        $session->save();

        $game = json_encode($score);
        return response($game,200);
    }

    public function addMovement(Request $request){
        $request->validate([
            'user_id' => 'required',
            'game_id' => 'required',
        ]);

        $gameSession = GameSession::where([['game_id', $request->game_id],['user_id', $request->user_id]])->get();
        $id = $gameSession[0]->id;
        $movements = $gameSession[0]->movements;
        $movements += 1;

        $session = GameSession::findOrFail($id);
        $session->movements = $movements;
        $session->save();

        $game = json_encode($movements);
        return response($game,200);
    }

    public function subScore(Request $request){
        $request->validate([
            'user_id' => 'required',
            'game_id' => 'required',
        ]);

        $gameSession = GameSession::where([['game_id', $request->game_id],['user_id', $request->user_id]])->get();
        $id = $gameSession[0]->id;
        $score = $gameSession[0]->score;
        $score -= 1;

        $session = GameSession::findOrFail($id);
        $session->score = $score;
        $session->save();

        $game = json_encode($score);
        return response($game,200);
    }
}
