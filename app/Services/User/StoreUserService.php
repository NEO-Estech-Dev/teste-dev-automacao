<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StoreUserService
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function run(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $data['type'] = $this->translateType($data['type']);
        $user = $this->user->create($data);

        return $user;
    }

    public function translateType(string $type): string
    {
        return match ($type) {
            'recrutador' => 'recruiter',
            'candidato' => 'applicant',
            default => 'recruiter',
        };
    }
}
