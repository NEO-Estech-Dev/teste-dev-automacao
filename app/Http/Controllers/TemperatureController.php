<?php

namespace App\Http\Controllers;

use App\Services\Temperature\TemperatureAnalysisService;
use Illuminate\Http\Request;

class TemperatureController extends Controller
{
    public function index(
        TemperatureAnalysisService $service
    )
    {
        $analysis = $service->run();
        return response()->json($analysis);
    }
}
