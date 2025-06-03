<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Service\UserService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    protected UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function register(RegisterUserRequest $request): JsonResponse
    {
        $user = $this->service->register($request->validated());

        return response()->json([
            'message' => 'Usuário criado com sucesso!',
            'user' => $user,
        ], Response::HTTP_CREATED);
    }
}
