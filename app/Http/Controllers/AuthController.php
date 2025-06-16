<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\LoginService;

class AuthController extends Controller
{
    public function login(
        LoginRequest $request,
        LoginService $service
    ) {
        $data = $request->validated();
        $token = $service->run($data);

        return response()->json($token);
    }

    public function logout()
    {
        return response()->json(auth()->user()->tokens()->delete(), 204);
    }
}
