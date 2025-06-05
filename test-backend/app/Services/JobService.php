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

    public function apply(Job $job, User $user, array $data): void
    {
        if ($user->type !== User::TYPE_CANDIDATE) {
            throw new \Exception('User is not a candidate');
        }

        if ($job->isPaused()) {
            throw new \Exception('Job is paused');
        }

        $job->candidates()->attach($user->id);
        // Save resume and coverLetter on S3 in a separate service
    }

    public function getCandidates(Job $job, array $filters): array 
    {
        $query = $job->candidates();

        if (isset($filters['search'])) {
            $search = '%' . $filters['search'] . '%';
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                  ->orWhere('email', 'like', $search);
            });
        }

        $perPage = min(20, ($filters['perPage'] ?? 20));
        $page = max(1, $filters['page'] ?? 1);
        return $query->paginate($perPage, ['user.id', 'user.name', 'user.email'], 'page', $page)->toArray();
    }

    public function getAppliedJobs(User $user, array $filters = []): array
    {
        $query = $user->appliedJobs()->with('recruiter');
        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['paused'])) {
            $query->where('paused', $filters['paused']);
        }

        if (isset($filters['title'])) {
            $query->where('title', 'like', '%' . $filters['title'] . '%');
        }

        $perPage = min(20, ($filters['perPage'] ?? 20));
        $page = max(1, $filters['page'] ?? 1);
        $result = $query->paginate($perPage, ['job.id', 'job.title', 'job.type', 'job.paused'], 'page', $page);
        $result->getCollection()->makeHidden(['recruiter']);
        return $result->toArray();
    }

    public function hasUserApplied(Job $job, User $user): bool
    {
        return $job->candidates()->where('user_id', $user->id)->exists();
    }

    public function getJobDetails(Job $job, User $user): ?array
    {
        if (!$this->hasUserApplied($job, $user)) {
            return null;
        }

        return [
           'candidate' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'coverLetter' => 'xpto',
                'resume' => [
                    'url' => sprintf('https://s3.example.com/jobs/%s/resumes/%s/resume.pdf', $job->id, $user->id),
                    'filename' => 'resume.pdf',
                    'size' => 2048000,
                ]
            ]
        ];
    }
}

