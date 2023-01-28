<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodigoIntento extends Model
{
    use HasFactory;
    protected $fillable = [
        'player_id',
        'c1',
        'c2',
        'c3',
        'c4',
        'c5',
        'c6',
        'c7',
        'c8',
        'c9',
        'result_count',
        'result_c1',
        'result_c2',
        'result_c3',
        'result_c4',
        'result_c5',
        'result_c6',
        'result_c7',
        'result_c8',
        'result_c9',
    ];
}
