<?php

namespace App\Http\Services;

use App\Models\User;

class UserService
{
    public function list(array $param)
    {
        $query = User::query();

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

        $perPage = $param['paginate'] ?? 20;

        return $query->paginate($perPage);
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update($id, array $data)
    {
        $user = User::find($id);

        if (!$user) {
            throw new \Exception('User not found', 404);
        }

        $user->update($data);

        return $user;
    }

    public function delete($userLoggedId, $id)
    {
        $user = User::find($id);

        if (!$user) {
            throw new \Exception('User not found', 404);
        }

        if ($userLoggedId !== $user->id) {
            throw new \Exception('You can only delete your own account', 403);
        }

        return $user->delete();
    }
}
