<?php

namespace App\Policies;

use App\Enums\UserType;
use App\Models\User;

class VacancyPolicy
{
    public function __construct() {}

    public function create(User $user): bool
    {
        return $user->type === UserType::RECRUITER->value;
    }

    public function update(User $user): bool
    {
        return $user->type === UserType::RECRUITER->value;
    }

    public function bulkDelete(User $user): bool
    {
        return $user->type === UserType::RECRUITER->value;
    }

    public function changeStatus(User $user): bool
    {
        return $user->type === UserType::RECRUITER->value;
    }

    public function delete(User $user): bool
    {
        return $user->type === UserType::RECRUITER->value;
    }
}
