<?php

namespace App\Services\User;

class UpdateUserService
{
    public function run($user, array $data)
    {
        $filteredData = array_filter($data);
        $user->fill($filteredData);
        $user->save();

        return $user;
    }
}
