<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    use HasFactory;
    protected $fillable = [
        'recurso1',
        'recurso2',
        'recurso3',
        'recurso4',
        'mineroInit',
        'mineroUpgrade',
        'soldierRoboChance',
        'soldierRoboUpgrade',
        'soldierMaxAtt',
        'soldierMaxDeff',
    ];
}
