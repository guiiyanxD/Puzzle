<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;
    protected $fillable = [
        'url','game_id','x_index','y_index','is_ful_image','width','height'
    ];

    public function game(){
        $this->belongsTo(Game::class,'game_id');
    }
}
