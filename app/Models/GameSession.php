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


    public function game(){
        return $this->belongsTo(Game::class, 'game_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
