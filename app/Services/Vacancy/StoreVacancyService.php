<?php

namespace App\Services\Vacancy;

use App\Models\Vacancy;

class StoreVacancyService
{
    private Vacancy $vacancy;

    public function __construct(Vacancy $vacancy)
    {
        $this->vacancy = $vacancy;
    }

    public function run(array $data)
    {
        return $this->vacancy->create($data);
    }
}
