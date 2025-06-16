<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplyCandidateRequest;
use App\Http\Requests\BulkCandidatesDeleteRequest;
use App\Http\Requests\ListCandidatesRequest;
use App\Http\Requests\UpdateCandidateStatusRequest;
use App\Http\Services\CandidateService;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    public function __construct(private CandidateService $candidateService) {}

    public function list(ListCandidatesRequest $request)
    {
        $param = $request->validated();

        $candidates = $this->candidateService->list($param);

        return response()->json($candidates);
    }

    public function apply(ApplyCandidateRequest $request, int $vacancyId)
    {
        $param = $request->validated();

        $candidate = $this->candidateService->apply($param, $vacancyId, $request->user()->id);

        return response()->json([
            'message' => 'Candidate applied successfully',
            'candidate' => $candidate,
            'status' => 200
        ]);
    }

    public function updateCandidateStatus(UpdateCandidateStatusRequest $request, int $id)
    {
        $param = $request->validated();

        $candidate = $this->candidateService->updateCandidateStatus($id, $param);

        return response()->json([
            'message' => 'Candidate status updated successfully',
            'candidate' => $candidate,
            'status' => 200
        ]);
    }

    public function delete(Request $request, int $id)
    {
        $this->candidateService->delete($request->user()->type, $id);

        return response()->json([
            'message' => 'Candidate deleted successfully',
            'status' => 200
        ]);
    }

    public function bulkDelete(BulkCandidatesDeleteRequest $request)
    {
        $this->candidateService->bulkDelete($request->user()->type, $request->validated()['ids']);

        return response()->json([
            'message' => 'Candidates deleted successfully',
            'status' => 200
        ]);
    }
}
