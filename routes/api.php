<?php

use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VacancyController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/user', [UserController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::put('/{user}', [UserController::class, 'update']);
        Route::delete('/{user}', [UserController::class, 'destroy']);
    });

    Route::prefix('vacancy')->group(function () {
        Route::get('/', [VacancyController::class, 'index']);
        Route::post('/', [VacancyController::class, 'store']);
        Route::put('/{vacancy}', [VacancyController::class, 'update']);
        Route::delete('/{vacancy}', [VacancyController::class, 'destroy']);
    });
    Route::prefix('applicant')->group(function () {
        Route::get('/', [ApplicantController::class, 'index']);
        Route::post('/apply-to-vacancy', [ApplicantController::class, 'applyToVacancy']);
    });
});
