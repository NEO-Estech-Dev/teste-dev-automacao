<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    const ACTIVE = 1;
    const DESACTIVATE = 0;

    protected $table = "tbl_candidates";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cpf',
        'phone',
        'linkedin',
        'github',
        'user_id'
    ];
}
