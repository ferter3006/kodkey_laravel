<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mineros extends Model
{
    use HasFactory;
    protected $fillable = [        
        'player_id',
        'lvl_minero1',
        'lvl_minero2',
        'lvl_minero3',
        'lvl_minero4',
    ];

/*

### 1 - Tierra
### 2 - Aire
### 3 - Agua
### 4 - Fuego

### LVL MINEROS ###

0- Minero explotado -> solo 5% i no puede subir más lvl. 
1- 10% chance
2- +5%
3- +5%
... etc

*/

}
 