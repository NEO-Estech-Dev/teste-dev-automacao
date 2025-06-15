<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Temperature extends Model
{
    protected $fillable = [
        'temperature',
        'registered_at',
    ];

    protected $casts = [
        'temperature' => 'float',
        'registered_at' => 'datetime',
    ];
}
