<?php

namespace App\Services;

use App\Models\Job;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class JobService
{
    public function list(array $filters = []): LengthAwarePaginator
    {
        $query = Job::query();

        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['paused'])) {
            $query->where('paused', $filters['paused']);
        }

        if (isset($filters['search'])) {
            $query->where('title', 'like', '%' . $filters['search'] . '%');
        }

        if (isset($filters['deleted']) && $filters['deleted']) {
            $query->withTrashed();
        } else {
            $query->whereNull('deleted_at');
        }

        if (isset($filters['orderBy'])) {
            $query->orderBy($filters['orderBy'], $filters['direction'] ?? 'asc');
        }

        $perPage = min(20, ($filters['perPage'] ?? 20));
        return $query->paginate($perPage, ['id', 'title', 'description', 'type', 'created_at'], 'page', max(1, $filters['page'] ?? 1));
    }

    public function create(User $user, array $data): Job
    {
        $this->checkIfUserIsRecruiter($user);
        $data['user_id'] = $user->id;
        return Job::create($data);
    }

    public function update(User $user, Job $job, array $data): void
    {
        $this->checkIfUserIsRecruiter($user, $job);
        $job->update($data);
    }

    public function delete(User $user, Job $job): void
    {
        $this->checkIfUserIsRecruiter($user, $job);
        $job->delete();
    }

    public function pause(User $user, Job $job, int $paused): void
    {
        $this->checkIfUserIsRecruiter($user, $job);
        $job->paused = $paused;
        $job->save();
    }

    private function checkIfUserIsRecruiter(User $user, ?Job $job = null): void
    {
        if ($user->type !== User::TYPE_RECRUITER) {
            throw new \Exception('Unauthorized action');
        }

        if ($job && $job->user_id !== $user->id) {
            throw new \Exception('Unauthorized action on this job');
        }
    }
}

