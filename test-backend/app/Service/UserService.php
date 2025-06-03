<?php

namespace App\Service;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function register(array $data): User
    {
        $data['password'] = Hash::make($data['password']);

        return User::create($data);
    }
}
