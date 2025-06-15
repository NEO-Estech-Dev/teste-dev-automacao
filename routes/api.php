<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VacancyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/register', [AuthController::class, 'register'])->name('user.register');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});

Route::prefix('user')->middleware('auth:sanctum')->group(function () {
    Route::get('/list', [UserController::class, 'list'])->name('user.list');
    Route::put('/update/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/delete/{id}', [UserController::class, 'delete'])->name('user.delete');
    Route::delete('/bulk-delete', [UserController::class, 'bulkDelete'])->name('user.bulkDelete');
});

Route::prefix('vacancy')->group(function () {
    Route::get('/list', [VacancyController::class, 'list'])->name('vacancy.list');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/create', [VacancyController::class, 'create'])->name('vacancy.create');
        Route::put('/update/{id}', [VacancyController::class, 'update'])->name('vacancy.update');
        Route::delete('/delete/{id}', [VacancyController::class, 'delete'])->name('vacancy.delete');
        Route::put('/change-status/{id}', [VacancyController::class, 'changeStatus'])->name('vacancy.changeStatus');
        Route::delete('/bulk-delete', [VacancyController::class, 'bulkDelete'])->name('vacancy.bulkDelete');
    });
});
