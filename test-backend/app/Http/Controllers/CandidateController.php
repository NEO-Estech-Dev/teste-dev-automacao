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

    public function show(Job $job, User $user): JsonResponse
    {
        if (!$this->jobService->hasUserApplied($job, $user)) {
            return response()->json(['message' => 'User not applied for this job.'], Response::HTTP_FORBIDDEN);
        }

        $jobDetails = $this->jobService->getJobDetails($job, $user);
        if (!$jobDetails) {
            return response()->json(['message' => 'Job not found or user has not applied.'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($jobDetails);
    }

    public function apply(ApplyJobRequest $request, Job $job): JsonResponse
    {
        $user = $request->user();
        $this->jobService->apply($job, $user, $request->validated());
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function appliedJobs(Request $request): JsonResponse
    {
        $user = $request->user();
        return response()->json($this->jobService->getAppliedJobs($user, $request->all()));
    }

    public function candidates(Request $request, Job $job): JsonResponse
    {
        return response()->json($this->jobService->getCandidates($job, $request->all()));
    }

    public function cancelApplication(Request $request, Job $job): JsonResponse
    {
        $user = $request->user();
        if (!$this->jobService->hasUserApplied($job, $user)) {
            return response()->json(['message' => 'User not applied for this job.'], Response::HTTP_FORBIDDEN);
        }

        $this->jobService->cancelApplication($user, $job);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
