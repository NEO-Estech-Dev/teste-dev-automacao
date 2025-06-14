<?php

namespace App\Services\User;

use App\Models\User;

class IndexUserService
{
    public function run(array $data)
    {

        $name = $data['search'] ?? null;
        $type = $data['type'] ?? null;
        $pagination = $data['per_page'] ?? 20;
        $orderBy = $data['order_by'] ?? 'id';
        $orderByDirection = $data['order_direction'] ?? 'asc';

        $query = User::query()
            ->when($name, function ($query) use ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        })
            ->when($type, function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->orderBy($orderBy, $orderByDirection)
            ->paginate($pagination);

        return $query;
    }
}