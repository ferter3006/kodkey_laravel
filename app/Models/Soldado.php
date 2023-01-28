<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soldado extends Model
{
    use HasFactory;
    protected $fillable = [
        'player_id',
        'atack',
        'defense',
        'ubicacion',
        'special'
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    /*
    #### CODIGOS UBICACION ####

    0- Inventario
    1- Atacanto
    2- Defendiando
    3- Cementerio

    #### CODIGOS SPECIAL ####

    0- RASO Sin habilidad
    1- GUANTEBLANCO +25% Probabilidad de Robar
    2- ADRENALINA +1 PA si logra Robar
    3- SABOTAJE -1 LVL minero si logra Robar
    4- ESPIA Info Soldados enemigos Inventario
    5- MOTIVADO1 x2 Probabilidad de minar recurso1 mientras está en las defensas
    6- MOTIVADO2 x2 Probabilidad de minar recurso2 mientras está en las defensas
    7- MOTIVADO3 x2 Probabilidad de minar recurso3 mientras está en las defensas
    8- MOTIVADO4 x2 Probabilidad de minar recurso4 mientras está en las defensas
    9- RESETEADOR Resetea minero aleatorio del enemigo si logra entrar

    */
}
