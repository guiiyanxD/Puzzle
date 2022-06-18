<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameSession extends Model
{
    use HasFactory;

    protected $table ='game_session';
    protected $fillable = [
        'user_id', 'game_id','score',
        'movements','winner','joined_time','left_time'
    ];

    public function addScore(){

    }
    public function game(){
        $this->belongsTo(Game::class, 'game_id');
    }

    public function user(){
        $this->belongsTo(User::class,'user_id');
    }
}
