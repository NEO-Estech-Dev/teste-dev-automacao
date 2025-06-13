<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:sanctum');

Route::prefix('user')->group(function () {
    Route::get('/list', [UserController::class, 'list'])->name('user.list');
    Route::post('/create', [UserController::class, 'create'])->name('user.create');
    Route::put('/update/{id}', [UserController::class, 'update'])->name('user.update')->middleware('auth:sanctum');
    Route::delete('/delete/{id}', [UserController::class, 'delete'])->name('user.delete')->middleware('auth:sanctum');
});
