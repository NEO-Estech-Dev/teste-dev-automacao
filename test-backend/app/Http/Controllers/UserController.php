<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    protected UserService $userService;
    public function __construct(
        UserService $userService
    ) {
        $this->userService = $userService;
    }

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($user->type != User::TYPE_ADMIN) {
            response()->json(['error' => 'user is not admin'], Response::HTTP_UNAUTHORIZED);
        }
        $users = $this->userService->list($request->all());
        return response()->json($users);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json($user);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $this->userService->update($user, json_decode($request->getContent(), true));
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function destroy(User $user): JsonResponse
    {
        $this->userService->softDelete($user);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
