<?php

namespace App\Services\Vacancy;

use App\Models\Vacancy;

class IndexVacancyService
{
    public function run(array $data)
    {
        $title = $data['search'] ?? null;
        $description = $data['description'] ?? null;
        $salaryMin = $data['salary_min'] ?? null;
        $salaryMax = $data['salary_max'] ?? null;
        $type = $data['type'] ?? null;
        $status = $data['status'] ?? null;
        $recruiterId = $data['recruiter_id'] ?? null;
        $perPage = $data['per_page'] ?? 20;
        $orderBy = $data['order_by'] ?? 'id';
        $orderByDirection = $data['order_direction'] ?? 'asc';

        $query = Vacancy::with('recruiter')
            ->when($recruiterId, function ($query) use ($recruiterId) {
                return $query->where('recruiter_id', $recruiterId);
            })
            ->when($title, function ($query) use ($title) {
                return $query->where('title', 'like', '%' . $title . '%');
            })
            ->when($description, function ($query) use ($description) {
                return $query->where('description', 'like', '%' . $description . '%');
            })
            ->when($salaryMin, function ($query) use ($salaryMin) {
                return $query->where('salary', '>=', $salaryMin);
            })
            ->when($salaryMax, function ($query) use ($salaryMax) {
                return $query->where('salary', '<=', $salaryMax);
            })
            ->when($type, function ($query) use ($type) {
                return $query->where('type', $type);
            })
            ->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->orderBy($orderBy, $orderByDirection)
            ->paginate($perPage);

        return $query;
    }
}