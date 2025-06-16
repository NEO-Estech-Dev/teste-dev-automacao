<?php

namespace App\Http\Controllers;

use App\Http\Requests\BulkVacanciesDeleteRequest;
use App\Http\Requests\CreateVacancyRequest;
use App\Http\Requests\ListVacanciesRequest;
use App\Http\Requests\UpdateVacancyRequest;
use App\Http\Services\VacancyService;
use App\Models\Vacancy;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VacancyController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private VacancyService $vacancyService) {}

    public function create(CreateVacancyRequest $request): JsonResponse
    {
        $this->authorize('create', Vacancy::class);

        $data = $request->validated();

        $vacancy = $this->vacancyService->create($data, $request->user()->id);

        return response()->json(
            [
                'message' => 'Vacancy created successfully',
                'vacancy' => $vacancy,
            ],
            Response::HTTP_CREATED
        );
    }

    public function list(ListVacanciesRequest $request): JsonResponse
    {
        $param = $request->validated();

        $vacancies = $this->vacancyService->list($param);

        return response()->json($vacancies, Response::HTTP_OK);
    }

    public function update(UpdateVacancyRequest $request, int $id): JsonResponse
    {
        $this->authorize('update', Vacancy::class);

        $data = $request->validated();

        $vacancy = $this->vacancyService->update($id, $data);

        return response()->json(
            [
                'message' => 'Vacancy updated successfully',
                'vacancy' => $vacancy,
            ],
            Response::HTTP_OK
        );
    }

    public function delete(int $id): JsonResponse
    {
        $this->authorize('delete', Vacancy::class);

        $this->vacancyService->delete($id);

        return response()->json(
            [
                'message' => 'Vacancy deleted successfully',
            ],
            Response::HTTP_NO_CONTENT
        );
    }

    public function changeStatus(int $id): JsonResponse
    {
        $this->authorize('changeStatus', Vacancy::class);

        $this->vacancyService->changeStatus($id);

        return response()->json(
            [
                'message' => 'Vacancy status changed successfully',
            ],
            Response::HTTP_OK
        );
    }

    public function bulkDelete(BulkVacanciesDeleteRequest $request): JsonResponse
    {
        $this->authorize('bulkDelete', Vacancy::class);

        $ids = $request->validated()['ids'];

        $this->vacancyService->bulkDelete($ids);

        return response()->json(
            [
                'message' => 'Vacancies deleted successfully',
            ],
            Response::HTTP_NO_CONTENT
        );
    }
}
