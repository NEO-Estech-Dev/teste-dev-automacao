<?php

namespace App\Services\Applicant;

class ApplyToVacancyService
{
    public function run(array $data)
    {
        $user = auth()->user();
        $user->vacancies()->attach($data['vacancy_id']);

        return 'Candidatura enviada com sucesso!';
    }
}
