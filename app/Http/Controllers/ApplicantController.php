<?php

namespace App\Http\Controllers;

use App\Http\Requests\Applicant\ApplyToVacancyRequest;
use App\Http\Requests\Applicant\IndexApplicantRequest;
use App\Services\Applicant\ApplyToVacancyService;
use App\Services\Applicant\IndexApplicantService;

class ApplicantController extends Controller
{
    public function index(
        IndexApplicantRequest $request,
        IndexApplicantService $service
    ) {
        $data = $request->validated();
        $applicants = $service->run($data);

        return response()->json($applicants, 200);
    }

    public function applyToVacancy(
        ApplyToVacancyRequest $request,
        ApplyToVacancyService $service
    ) {
        $data = $request->validated();
        $message = $service->run($data);

        return response()->json($message, 200);
    }
}
