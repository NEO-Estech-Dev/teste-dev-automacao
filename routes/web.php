<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\routesControllers;
use Illuminate\Support\Facades\Route;


Route::middleware(['web'])->group(function () {

    Route::get('/', [routesControllers::class, 'index'])->name('page-inicial');
    Route::get('/Login', [routesControllers::class, 'login'])->name('login');
    Route::get('/Cadastrar', [routesControllers::class, 'Cad'])->name('cadastrase');
    Route::post('/Home', [LoginController::class, 'processLogin'])->name('LoginProcess');
    Route::get('/Perfil',[routesControllers::class, 'ExibirPerfil'])->name('Perfil');
    
    Route::get('/Destroy', [LoginController::class, 'processDestroy'])->name('Destroy');
    Route::get('/redis',[routesControllers::class,'testeRedir'])->name('testeRedis');
});

    Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/Home', [routesControllers::class, 'Homes'])->name('home');
    Route::get('/Listar', [routesControllers::class, 'List'])->name('ListarUsers');
    Route::get('/Temperatura', [routesControllers::class, 'Tem'])->name('ListarTemp');
   

});
