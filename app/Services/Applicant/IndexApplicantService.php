<?php

namespace App\Services\Applicant;

use App\Models\User;

class IndexApplicantService
{
    public function run(array $data)
    {
        $user = auth()->user();

        $vacanciesId = $data['vacancies_id'] ?? null;
        $orderBy = $data['order_by'] ?? 'id';
        $orderDirection = $data['order_direction'] ?? 'asc';
        $perPage = $data['per_page'] ?? 20;

        return User::with(['vacancies' => function ($query) use ($vacanciesId) {
            if ($vacanciesId) {
                $query->whereIn('vacancies.id', $vacanciesId);
            }
        }])
            ->where('type', 'applicant')
            ->when(! $user->isRecruiter(), function ($query) use ($user) {
                $query->where('id', $user->id);
            })
            ->when($vacanciesId, function ($query) use ($vacanciesId) {
                $query->whereHas('vacancies', function ($query) use ($vacanciesId) {
                    $query->whereIn('vacancies.id', $vacanciesId);
                });
            })
            ->orderBy($orderBy, $orderDirection)
            ->paginate($perPage);
    }
}
