<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;
    protected $fillable = [
        'turno',
        'user_id',
        'game_type',
    ];

    /*
     ### GAME TYPE OPTIONS ###

     1- Normal game

     */

    public function players()
    {
        return $this->hasMany(Player::class)->with('mineros', 'codigo', 'soldados', 'codigoIntentos');
    }
}
