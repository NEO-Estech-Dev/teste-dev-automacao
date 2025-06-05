<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Temperature extends Model
{
    protected $table = 'temperature';

    protected $fillable = [
        'date',
        'temperature'
    ];

    public $timestamps = true;

    protected $casts = [
        'date' => 'datetime'
    ];
}
