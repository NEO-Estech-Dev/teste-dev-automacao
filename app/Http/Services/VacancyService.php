<?php

namespace App\Http\Services;

use App\Enums\UserType;
use App\Models\Vacancy;
use Illuminate\Pagination\LengthAwarePaginator;

class VacancyService
{
    public function __construct(private Vacancy $vacancy) {}

    public function create(array $data, int $recruiterId): Vacancy
    {
        $vacancy = $this->vacancy->create(array_merge($data, ['recruiter_id' => $recruiterId]));
        cache()->tags(['vacancies'])->flush();
        return $vacancy;
    }

    public function list(array $param): LengthAwarePaginator
    {
        $cacheKey = 'vacancies:' . md5(json_encode($param));

        $query = function () use ($param) {
            $query = $this->vacancy->query()->with('recruiter');

            $filters = [
                'type' => fn($value) => $query->where('type', $value),
                'status' => fn($value) => $query->where('status', $value),
                'salary' => fn($value) => $query->where('salary', $value),
                'recruiter_id' => fn($value) => $query->where('recruiter_id', $value),
                'title' => fn($value) => $query->where('title', 'like', '%' . $value . '%'),
                'description' => fn($value) => $query->where('description', 'like', '%' . $value . '%'),
                'company_name' => fn($value) => $query->where('company_name', 'like', '%' . $value . '%'),
            ];

            foreach ($filters as $key => $filter) {
                if (isset($param[$key])) {
                    $filter($param[$key]);
                }
            }

            if (isset($param['order_by'])) {
                $direction = isset($param['order']) && strtolower($param['order']) === 'desc' ? 'desc' : 'asc';
                $query->orderBy($param['order_by'], $direction);
            }

            $paginate = $param['paginate'] ?? 20;

            return $query->paginate($paginate);
        };

        return cache()->remember($cacheKey, now()->addMinutes(30), $query);
    }

    public function update(int $id, array $data)
    {
        $vacancy = $this->vacancy->find($id);

        if (!$vacancy) {
            throw new \Exception('Vacancy not found', 404);
        }

        $vacancy->update($data);
        cache()->tags(['vacancies'])->flush();
        return $vacancy;
    }

    public function delete(string $userType, int $id)
    {
        if ($userType != UserType::RECRUITER->value) {
            throw new \Exception('You are not authorized to delete this vacancy.', 403);
        }

        $vacancy = $this->vacancy->find($id);

        if (!$vacancy) {
            throw new \Exception('Vacancy not found', 404);
        }

        $vacancy->delete();
        cache()->tags(['vacancies'])->flush();
        return true;
    }

    public function changeStatus(int $id)
    {
        $vacancy = $this->vacancy->find($id);

        if (!$vacancy) {
            throw new \Exception('Vacancy not found', 404);
        }

        $vacancy->update(['status' => $vacancy->status === 'active' ? 'inactive' : 'active']);
        cache()->tags(['vacancies'])->flush();
        return $vacancy;
    }

    public function bulkDelete(array $ids)
    {
        $this->vacancy->whereIn('id', $ids)->delete();
        cache()->tags(['vacancies'])->flush();
        return true;
    }
}
