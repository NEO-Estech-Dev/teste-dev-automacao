<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\UserController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('jwt.auth')->group(function (): void {
    Route::apiResource('users', UserController::class);

    Route::apiResource('jobs', JobController::class);
    Route::patch('jobs/{job}/pause', [JobController::class, 'pause']);
    
    Route::post('jobs/{job}/apply', [CandidateController::class, 'apply']);
    Route::delete('jobs/{job}/cancel', [CandidateController::class, 'cancelApplication']);
    Route::get('jobs/{job}/appliedJobs', [CandidateController::class, 'appliedJobs']);
    Route::get('jobs/{job}/candidates', [CandidateController::class, 'candidates']);
    Route::get('jobs/{job}/candidates/{user}', [CandidateController::class, 'show']);

    Route::post('/import', [ImportController::class, 'import']);
});