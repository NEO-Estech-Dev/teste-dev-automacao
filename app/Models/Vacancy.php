<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{
    use HasFactory;

    const ACTIVE = 1;
    const DESACTIVATE = 0;

    protected $table = "tbl_vacancies_job";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title_vacancy_job',
        'description_vacancy_job',
        'type_vacancy_job',
        'company_name',
        'candidate_id',
    ];

    public function users() {
        return $this->belongsToMany(User::class);
    }
}
