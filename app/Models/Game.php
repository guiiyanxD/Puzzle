<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'cols', 'rows', 'status_id','user_id', 'code_invitation'
    ];

    public function status(){
        return $this->belongsTo(StatusGame::class, 'user_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function session(){
        return $this->hasMany(GameSession::class);
    }

    public function file(){
        return $this->hasMany(File::class);
    }

    public function portrait(){
        return $this->hasOne(PortraitFile::class);
    }

}
