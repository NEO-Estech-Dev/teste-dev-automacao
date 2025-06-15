<?php

namespace App\Services\Auth;

use App\Models\User;

class LoginService
{
    public function run(array $data)
    {
        $user = User::where('email', $data['email'])->first();

        return $user->createToken('auth_token')->plainTextToken;
    }
}
