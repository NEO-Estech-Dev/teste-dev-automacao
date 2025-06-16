<?php

namespace App\Models;

use App\Enums\VacancyType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vacancy extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'type',
        'description',
        'company_name',
        'salary',
        'recruiter_id',
        'status'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    protected function casts(): array
    {
        return [
            'type' => VacancyType::class,
        ];
    }

    public function recruiter()
    {
        return $this->belongsTo(User::class, 'recruiter_id');
    }
}
