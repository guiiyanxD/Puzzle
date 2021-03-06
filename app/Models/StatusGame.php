<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusGame extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'description'
    ];

    public function game(){
        $this->hasMany(Game::class);
    }
}
