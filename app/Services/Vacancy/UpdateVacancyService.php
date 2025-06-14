<?php

namespace App\Services\Vacancy;

use App\Models\Vacancy;

class UpdateVacancyService
{
    public function run(array $data, Vacancy $vacancy)
    {
        $filteredData = array_filter($data);
        $vacancy->update($filteredData);

        return $vacancy;
    }
}