<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Models\Job;
use App\Services\JobService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JobController extends Controller
{
    protected $jobService;
    public function __construct(JobService $jobService)
    {
        $this->jobService = $jobService;
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json($this->jobService->list($request->all()));
    }

    public function store(CreateJobRequest $request): JsonResponse
    {
        $user = $request->user();
        $job = $this->jobService->create($user, $request->validated());
        return response()->json($job, Response::HTTP_CREATED);
    }

    public function show(Job $job): JsonResponse
    {
        return response()->json($job);
    }

    public function update(UpdateJobRequest $request, Job $job): JsonResponse
    {
        $user = $request->user();
        $this->jobService->update($user, $job, $request->validated());
        return response()->json($job);
    }

    public function destroy(Request $request, Job $job): JsonResponse
    {
        $user = $request->user();
        $this->jobService->delete($user, $job);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function pause(Request $request, Job $job): JsonResponse
    {
        $user = $request->user();
        $paused = $request->input('paused');
        if (is_null($paused)) {
            return response()->json(['error' => 'O campo "paused" é obrigatório'], Response::HTTP_BAD_REQUEST);
        }

        $this->jobService->pause($user, $job, $paused);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
