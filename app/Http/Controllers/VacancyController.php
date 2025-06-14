<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vacancy\IndexVacancyRequest;
use App\Http\Requests\Vacancy\StoreVacancyRequest;
use App\Http\Requests\Vacancy\UpdateVacancyRequest;
use App\Http\Resources\VacancyResource;
use App\Models\Vacancy;
use App\Services\Vacancy\IndexVacancyService;
use App\Services\Vacancy\StoreVacancyService;
use App\Services\Vacancy\UpdateVacancyService;

class VacancyController extends Controller
{
    public function index(
        IndexVacancyRequest $request,
        IndexVacancyService $service
    )
    {
        $data = $request->validated();
        $vacancies = $service->run($data);
        return response()->json($vacancies, 200);
    }

    public function store(
        StoreVacancyRequest $request,
        StoreVacancyService $service,
    )
    {
        $data = $request->validated();
        $vacancy = $service->run($data);

        return response()->json(new VacancyResource($vacancy), 201);
    }

    public function update(
        UpdateVacancyRequest $request,
        Vacancy $vacancy,
        UpdateVacancyService $service
    )
    {
        $data = $request->validated();
        $updatedVacancy = $service->run($data, $vacancy);

        return response()->json(new VacancyResource($updatedVacancy), 200);
    }

    public function destroy(Vacancy $vacancy)
    {
        return response()->json($vacancy->delete(), 204);
    }
}
