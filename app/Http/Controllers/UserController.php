<?php

namespace App\Http\Controllers;

use App\Http\Requests\BulkUsersDeleteRequest;
use App\Http\Requests\ListUsersRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Services\UserService;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private UserService $userService) {}

    public function list(ListUsersRequest $request): JsonResponse
    {
        $param = $request->validated();

        $users = $this->userService->list($param);

        return response()->json($users->items(), Response::HTTP_OK);
    }

    public function update(UpdateUserRequest $request): JsonResponse
    {
        $data = $request->validated();
        $userId = $request->user()->id;

        $user = $this->userService->update($userId, $data);

        return response()->json(
            [
                'message' => 'User updated successfully',
                'user' => $user,
            ],
            Response::HTTP_OK
        );
    }

    public function delete(Request $request, int $id): JsonResponse
    {
        $this->userService->delete($request->user()->id, $id);

        return response()->json(
            [
                'message' => 'User deleted successfully',
            ],
            Response::HTTP_NO_CONTENT
        );
    }

    public function findUserByEmail($email): JsonResponse
    {
        $user = $this->userService->findUserByEmail($email);

        return response()->json($user, Response::HTTP_OK);
    }

    public function bulkDelete(BulkUsersDeleteRequest $request): JsonResponse
    {
        $this->authorize('bulkDelete', User::class);

        $this->userService->bulkDelete($request->validated()['ids']);

        return response()->json(
            [
                'message' => 'Users deleted successfully',
            ],
            Response::HTTP_NO_CONTENT
        );
    }
}
