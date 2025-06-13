<?php

use App\Http\Controllers\CandidatesController;
use App\Http\Controllers\TemperatureController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VacanciesJobController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('temperature')->group(function() {
    Route::get('/mean', [TemperatureController::class,'mean']);
    Route::get('/median', [TemperatureController::class,'median']);
    Route::get('/lower', [TemperatureController::class,'lower']);
    Route::get('/higher', [TemperatureController::class,'higher']);
    Route::get('/greater_than_ten', [TemperatureController::class,'greater_than_ten']);
    Route::get('/lower_than_minus_ten', [TemperatureController::class,'lower_than_minus_ten']);
    Route::get('/between_both_limits', [TemperatureController::class,'between_both_limits']);
});

Route::prefix('users')->group(function() {
    Route::get('/', [UserController::class,'index']);
    Route::get('/active/{id}', [UserController::class,'active']);
    Route::post('/store', [UserController::class,'store']);
    Route::get('/{id}', [UserController::class,'show']);
    Route::put('/update/{id}', [UserController::class,'update']);
    Route::delete('/delete/{id}', [UserController::class,'destroy']);
});

Route::prefix('vacancies')->group(function() {
    Route::get('/', [VacanciesJobController::class,'index']);
    Route::put('/subscribe/{id}', [VacanciesJobController::class,'subscribe']);
    Route::get('/active/{id}', [VacanciesJobController::class,'active']);
    Route::post('/store', [VacanciesJobController::class,'store']);
    Route::get('/{id}', [VacanciesJobController::class,'show']);
    Route::put('/update/{id}', [VacanciesJobController::class,'update']);
    Route::delete('/delete/{id}', [VacanciesJobController::class,'destroy']);
});

Route::prefix('candidates')->group(function() {
    Route::get('/', [CandidatesController::class,'index']);
    Route::get('/active/{id}', [CandidatesController::class,'active']);
    Route::post('/store', [CandidatesController::class,'store']);
    Route::get('/{id}', [CandidatesController::class,'show']);
    Route::put('/update/{id}', [CandidatesController::class,'update']);
    Route::delete('/delete/{id}', [CandidatesController::class,'destroy']);
});
