<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;

class Player extends Model
{
    use HasFactory;
    protected $fillable = [
        'game_id',
        'user_id',
        'player',
        'puntos_accion',
        'recurso1',
        'recurso2',
        'recurso3',
        'recurso4',
        'c1',
        'c2',
        'c3',
        'c4',
        'c5',
        'c6',
        'c7',
        'c8',
        'c9',
    ];


    public function mineros()
    {
        return $this->hasOne(Mineros::class);
    }

    public function codigo()
    {
        return $this->hasOne(Codigo::class);
    }

    public function codigoIntentos()
    {
        return $this->hasMany(CodigoIntento::class);
    }

    public function soldados()
    {
        return $this->hasMany(Soldado::class);
    }
}
