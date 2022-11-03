<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pokemons extends Model
{
    protected $fillable = [
        'type_id',
        'generation',
        'url',
        'name',
        'hp',
        'attack',
        'defence',
        'special_attack',
        'special_defence',
        'speed',
    ];
}
