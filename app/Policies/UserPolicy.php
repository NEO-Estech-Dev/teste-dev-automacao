<?php

namespace App\Policies;

use App\Enums\UserType;
use App\Models\User;

class UserPolicy
{
    public function __construct() {}

    public function bulkDelete(User $user): bool
    {
        return $user->type === UserType::RECRUITER->value;
    }

    public function update(User $user, User $model): bool
    {
        return true;
    }

    public function delete(User $user, User $model): bool
    {
        return $user->id === $model->id;
    }
}
