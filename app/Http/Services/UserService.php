<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{
    public function __construct(private User $user) {}

    public function list(array $param): LengthAwarePaginator
    {
        $query = $this->user->query();

        $filters = [
            'email' => fn($value) => $query->where('email', 'like', '%' . $value . '%'),
            'name'  => fn($value) => $query->where('name', 'like', '%' . $value . '%'),
            'type'  => fn($value) => $query->where('type', $value),
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

        $perPage = $param['paginate'] ?? 20;

        return $query->paginate($perPage);
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

        return $user;
    }

    public function delete(int $userLoggedId, int $id): bool
    {
        $user = $this->user->find($id);

        if (!$user) {
            throw new \Exception('User not found', 404);
        }

        if ($userLoggedId !== $user->id) {
            throw new \Exception('You can only delete your own account', 403);
        }

        return $user->delete();
    }

    public function findUserByEmail(string $email): User
    {
        return $this->user->where('email', $email)->first();
    }
}
