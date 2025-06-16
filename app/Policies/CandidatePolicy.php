<?php

namespace App\Policies;

use App\Enums\UserType;
use App\Models\User;

class CandidatePolicy
{
    public function apply(User $user): bool
    {
        return $user->type === UserType::CANDIDATE->value;
    }

    public function updateStatus(User $user): bool
    {
        return $user->type === UserType::RECRUITER->value;
    }

    public function bulkDelete(User $user): bool
    {
        return $user->type === UserType::RECRUITER->value;
    }

    public function delete(User $user): bool
    {
        return $user->type === UserType::RECRUITER->value;
    }
}
