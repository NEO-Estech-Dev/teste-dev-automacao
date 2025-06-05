<?php

use App\Http\Controllers\api\ArquivosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\CadastroUserController;
use App\Http\Controllers\api\ListUsersController;
use App\Http\Controllers\api\TemperaturaController;
use App\Http\Controllers\api\VagasController;

//Rota para trazer as vagas 
Route::post('/AllVagas', [VagasController::class, 'ListarAllvagas'])->name('todas as vagas');


Route::post('/Cadastro', [CadastroUserController::class, 'processCad'])->name('CadProcess');
Route::post('/CadastroRecrutador', [CadastroUserController::class, 'recruiterCadRecutrador'])->name('recruiterProcess');



Route::post('/Tokens', [CadastroUserController::class, 'allToken'])->name('all');


Route::post('/LoginsControl', [LoginController::class, 'index'])->name('index');

Route::post('/listUsers', [ListUsersController::class, 'storeUsers'])->name('listar');



Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::post('/Vacancies', [VagasController::class, 'StoreVagas'])->name('vagas');

    Route::post('/Allvagasid', [VagasController::class, 'ListVagasid'])->name('listvagas');

    Route::post('/Deletar', [ListUsersController::class, 'dell'])->name('DeletarUser');

    Route::post('/AtivarUser', [ListUsersController::class, 'ative'])->name('AtivarUser');

    Route::post('/BuscarUser', [ListUsersController::class, 'allUser'])->name('User');

    Route::post('/EditUser', [ListUsersController::class, 'EditUser'])->name('Edit');

    Route::post('/Candidacy', [VagasController::class, 'CadUserVg'])->name('vagaCaf');

    Route::post('/ListCandidacyUser', [VagasController::class, 'listCandaciy'])->name('LitCand');

    Route::post('/ListdataPerfil', [ListUsersController::class, 'Perfil'])->name('listPerfil');

    Route::post('/EditarCandy', [ListUsersController::class, 'EditUserCandy'])->name('EditiCandy');

    Route::post('/NewCv', [ListUsersController::class, 'NewCurriculo'])->name('CvNew');

    Route::post('/PickUpCandidates', [ListUsersController::class, 'PickUp'])->name('Up');

    Route::post('/FuncoesVagas', [VagasController::class, 'FuncoesVagas'])->name('break');

    Route::post('/EditVacancies', [VagasController::class, 'EditVancncie'])->name('EditVacancies');

    Route::post('/Arquivos', [ArquivosController::class, 'file'])->name('files');

    Route::post('/Temperatura', [TemperaturaController::class, 'retornoTemp'])->name('temp');
});
