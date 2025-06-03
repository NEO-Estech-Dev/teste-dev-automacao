<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use App\Service\UserService;
use App\Service\JwtService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    protected UserService $service;
    protected JwtService $jwtService;

    public function __construct(
        UserService $service,
        JwtService $jwtService
    )
    {
        $this->service = $service;
        $this->jwtService = $jwtService;
    }

    public function register(RegisterUserRequest $request): JsonResponse
    {
        $user = $this->service->register($request->validated());

        return response()->json([
            'message' => 'Usuário criado com sucesso!',
            'user' => $user,
        ], Response::HTTP_CREATED);
    }

    public function login(LoginUserRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Credenciais inválidas'], 401);
        }

        $token = $this->jwtService->generateToken($user);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }
}
