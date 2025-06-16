<?php

namespace App\Http\Services;

use App\Enums\UserType;
use App\Models\Candidate;
use App\Models\Vacancy;
use Illuminate\Pagination\LengthAwarePaginator;

class CandidateService
{
    public function __construct(private Candidate $candidate, private Vacancy $vacancy) {}

    public function list(array $param): LengthAwarePaginator
    {
        $query = $this->candidate->query()->with('user')->with('vacancy');

        $filters = [
            'status' => fn($value) => $query->where('status', $value),
            'vacancy_id' => fn($value) => $query->where('vacancy_id', $value),
            'user_id' => fn($value) => $query->where('user_id', $value),
            'candidate_name' => fn($value) => $query->whereHas('user', fn($q) => $q->where('name', 'like', "%$value%")),
            'vacancy_title' => fn($value) => $query->whereHas('vacancy', fn($q) => $q->where('title', 'like', "%$value%")),
        ];

        foreach ($filters as $key => $filter) {
            if (isset($param[$key]) && $param[$key] !== null) {
                $filter($param[$key]);
            }
        }

        if (isset($param['order_by'])) {
            $direction = isset($param['order']) && strtolower($param['order']) === 'desc' ? 'desc' : 'asc';
            $query->orderBy($param['order_by'], $direction);
        }

        return $query->paginate($param['paginate'] ?? 20);
    }

    public function apply(array $data, int $vacancyId, int $userId)
    {
        $data['user_id'] = $userId;
        $data['vacancy_id'] = $vacancyId;

        if (!$this->vacancy->where('id', $vacancyId)->exists()) {
            throw new \Exception('Vacancy not found.');
        }

        if ($this->candidate->where('user_id', $userId)->where('vacancy_id', $vacancyId)->exists()) {
            throw new \Exception('Candidate already applied for this vacancy.');
        }

        return $this->candidate->create($data);
    }

    public function updateCandidateStatus(int $id, array $data)
    {
        $candidate = $this->candidate->find($id);

        if (!$candidate) {
            throw new \Exception('Candidate not found.');
        }

        $candidate->update($data);

        return $candidate;
    }

    public function delete(string $userType, int $id)
    {
        $candidate = $this->candidate->find($id);

        if (!$candidate) {
            throw new \Exception('Candidate not found.');
        }

        if ($userType != UserType::RECRUITER->value) {
            throw new \Exception('You are not authorized to delete this candidate.');
        }

        return $candidate->delete();
    }

    public function bulkDelete(string $userType, array $ids)
    {
        if ($userType != UserType::RECRUITER->value) {
            throw new \Exception('You are not authorized to delete these candidates.');
        }

        $this->candidate->whereIn('id', $ids)->delete();

        return true;
    }
}
