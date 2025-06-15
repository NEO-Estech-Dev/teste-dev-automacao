<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListUsersRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private UserService $userService) {}

    public function list(ListUsersRequest $request): JsonResponse
    {
        $param = $request->validated();

        $users = $this->userService->list($param);

        return response()->json($users->items());
    }

    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        $data = $request->validated();

        $user = $this->userService->update($id, $data);

        return response()->json(
            [
                'message' => 'User updated successfully',
                'user' => $user,
                'status' => 200
            ],
        );
    }

    public function delete(Request $request, int $id): JsonResponse
    {
        $this->userService->delete($request->user()->id, $id);

        return response()->json(
            [
                'message' => 'User deleted successfully',
                'status' => 200
            ],
        );
    }

    public function findUserByEmail($email): JsonResponse
    {
        $user = $this->userService->findUserByEmail($email);

        return response()->json($user);
    }
}
