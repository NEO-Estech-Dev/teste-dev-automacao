<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplyCandidateRequest;
use App\Http\Requests\BulkCandidatesDeleteRequest;
use App\Http\Requests\ListCandidatesRequest;
use App\Http\Requests\UpdateCandidateStatusRequest;
use App\Http\Services\CandidateService;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Symfony\Component\HttpFoundation\Response;

class CandidateController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private CandidateService $candidateService) {}

    public function list(ListCandidatesRequest $request)
    {
        $param = $request->validated();

        $candidates = $this->candidateService->list($param);

        return response()->json($candidates, Response::HTTP_OK);
    }

    public function apply(ApplyCandidateRequest $request, int $vacancyId)
    {
        $this->authorize('apply', Candidate::class);

        $param = $request->validated();

        $candidate = $this->candidateService->apply($param, $vacancyId, $request->user()->id);

        return response()->json([
            'message' => 'Candidate applied successfully',
            'candidate' => $candidate,
        ], Response::HTTP_CREATED);
    }

    public function updateCandidateStatus(UpdateCandidateStatusRequest $request, int $id)
    {
        $this->authorize('updateStatus', Candidate::class);

        $param = $request->validated();

        $candidate = $this->candidateService->updateCandidateStatus($id, $param);

        return response()->json([
            'message' => 'Candidate status updated successfully',
            'candidate' => $candidate,
        ], Response::HTTP_OK);
    }

    public function delete(int $id)
    {
        $this->authorize('delete', Candidate::class);

        $this->candidateService->delete($id);

        return response()->json([
            'message' => 'Candidate deleted successfully',
        ], Response::HTTP_NO_CONTENT);
    }

    public function bulkDelete(BulkCandidatesDeleteRequest $request)
    {
        $this->authorize('bulkDelete', Candidate::class);

        $ids = $request->validated()['ids'];

        $this->candidateService->bulkDelete($ids);

        return response()->json([
            'message' => 'Candidates deleted successfully',
        ], Response::HTTP_NO_CONTENT);
    }
}
