<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Job extends Model
{
    protected $table = 'job';

    CONST TYPE_CLT = 'CLT';
    CONST TYPE_PJ = 'PJ';
    CONST TYPE_FREELANCER = 'Freelancer';
    CONST ALL_TYPES = [
        self::TYPE_CLT,
        self::TYPE_PJ,
        self::TYPE_FREELANCER,
    ];


    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'type',
        'paused',
    ];

    protected $hidden = [
        'pivot',
    ];

    public function recruiter()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function candidates()
    {
        return $this->belongsToMany(User::class, 'job_user');
    }

    public function isPaused(): bool
    {
        return (bool) $this->paused;
    }
}
