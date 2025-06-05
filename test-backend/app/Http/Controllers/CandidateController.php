<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplyJobRequest;
use App\Models\Job;
use App\Models\User;
use App\Services\JobService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CandidateController extends Controller
{
    protected JobService $jobService;

    public function __construct(JobService $jobService)
    {
        $this->jobService = $jobService;
    }

    public function show(User $user): JsonResponse
    {
        if (!$user->type === User::TYPE_CANDIDATE) {
            return response()->json(['error' => 'User is not a candidate'], Response::HTTP_FORBIDDEN);
        }

        return response()->json($user);
    }

    public function apply(ApplyJobRequest $request, Job $job): JsonResponse
    {
        $user = $request->user();
        $this->jobService->apply($job, $user, $request->validated());
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
