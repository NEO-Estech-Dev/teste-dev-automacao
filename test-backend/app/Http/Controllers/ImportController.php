<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportCSVTempRequest;
use App\Jobs\ProcessCSVFileJob;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ImportController extends Controller
{
    public function import(ImportCSVTempRequest $request): JsonResponse
    {
        $path = $request->file('file')->store('imports');
        ProcessCSVFileJob::dispatch($path);
        return response()->json(['message' => 'Import started'], Response::HTTP_ACCEPTED);
    }
}
