<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function register(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }

    public function softDelete(User $user): void
    {
        $user->delete();
    }

    public function update(User $user, array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        unset($data['email']);
        unset($data['type']);
        $user->update($data);

        return $user;
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function list(array $filters = [])
    {
        $query = User::query();
        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['search'])) {
            $search = '%' . $filters['search'] . '%';
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                  ->orWhere('email', 'like', $search);
            });
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
        return $query->paginate($perPage, ['name', 'email', 'type']);
    }
}
