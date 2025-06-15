<?php

namespace App\Http\Controllers;

use App\Http\Requests\BulkVacanciesDeleteRequest;
use App\Http\Requests\CreateVacancyRequest;
use App\Http\Requests\ListVacanciesRequest;
use App\Http\Requests\UpdateVacancyRequest;
use App\Http\Services\VacancyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class VacancyController extends Controller
{
    public function __construct(private VacancyService $vacancyService) {}

    public function create(CreateVacancyRequest $request): JsonResponse
    {
        $data = $request->validated();

        $vacancy = $this->vacancyService->create($data, $request->user()->id);

        return response()->json(
            [
                'message' => 'Vacancy created successfully',
                'vacancy' => $vacancy,
                'status' => 200
            ],
        );
    }

    public function list(ListVacanciesRequest $request): JsonResponse
    {
        $param = $request->validated();

        $vacancies = $this->vacancyService->list($param);

        return response()->json($vacancies);
    }

    public function update(UpdateVacancyRequest $request, int $id): JsonResponse
    {
        $data = $request->validated();

        $vacancy = $this->vacancyService->update($id, $data);

        return response()->json(
            [
                'message' => 'Vacancy updated successfully',
                'vacancy' => $vacancy,
                'status' => 200
            ],
        );
    }

    public function delete(Request $request, int $id): JsonResponse
    {
        $this->vacancyService->delete($request->user()->type, $id);

        return response()->json(
            [
                'message' => 'Vacancy deleted successfully',
                'status' => 200
            ],
        );
    }

    public function changeStatus(int $id): JsonResponse
    {
        $this->vacancyService->changeStatus($id);

        return response()->json(
            [
                'message' => 'Vacancy status changed successfully',
                'status' => 200
            ],
        );
    }

    public function bulkDelete(BulkVacanciesDeleteRequest $request): JsonResponse
    {
        $this->vacancyService->bulkDelete($request->validated()['ids']);

        return response()->json(
            [
                'message' => 'Vacancies deleted successfully',
                'status' => 200
            ],
        );
    }
}
