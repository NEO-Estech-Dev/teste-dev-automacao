<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{
    public function __construct(private User $user) {}

    public function list(array $param): LengthAwarePaginator
    {
        $cacheKey = 'users:' . md5(json_encode($param));

        $query = function () use ($param) {
            $query = $this->user->query();

            $filters = [
                'type' => fn($value) => $query->where('type', $value),
                'status' => fn($value) => $query->where('status', $value),
                'name' => fn($value) => $query->where('name', 'like', '%' . $value . '%'),
                'email' => fn($value) => $query->where('email', 'like', '%' . $value . '%'),
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

    public function create(array $data): User
    {
        return $this->user->create($data);
    }

    public function update(int $id, array $data): User
    {
        $user = $this->user->find($id);

        if (!$user) {
            throw new \Exception('User not found', 404);
        }

        $user->update($data);
        cache()->tags(['users'])->flush();
        return $user;
    }

    public function delete(int $authUserId, int $id): bool
    {
        if ($authUserId !== $id) {
            throw new \Exception('You can only delete your own account.', 403);
        }

        $user = $this->user->find($id);

        if (!$user) {
            throw new \Exception('User not found', 404);
        }

        $user->delete();
        cache()->tags(['users'])->flush();
        return true;
    }

    public function findUserByEmail(string $email): ?User
    {
        $cacheKey = 'user:email:' . md5($email);

        return cache()->remember($cacheKey, now()->addMinutes(30), function () use ($email) {
            return $this->user->where('email', $email)->first();
        });
    }

    public function bulkDelete(array $ids): bool
    {
        $this->user->whereIn('id', $ids)->delete();
        cache()->tags(['users'])->flush();
        return true;
    }
}
