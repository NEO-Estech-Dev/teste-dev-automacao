<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\VacancyController;
use Illuminate\Support\Facades\Route;

Route::post('/user', [UserController::class, 'store']);

//Route::middleware('auth:sanctum')->group(function () {
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
//    });// Add other authenticated routes here
