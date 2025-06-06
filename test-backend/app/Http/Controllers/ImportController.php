<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportCSVTempRequest;
use App\Http\Requests\TemperatureReportRequest;
use App\Jobs\ProcessCSVFileJob;
use App\Services\TemperatureService;
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

    public function report(TemperatureReportRequest $request, TemperatureService $temperatureService): JsonResponse
    {
        $resume = $temperatureService->analisy($request->all());
        if (empty($resume)) {
            return response()->json(['message' => 'No data available for analysis'], Response::HTTP_NOT_FOUND);
        }
        return response()->json(['resume' => $resume]);
    }
}
