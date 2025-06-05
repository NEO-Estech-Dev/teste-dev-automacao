<?php

namespace App\Http\Controllers;

use App\Http\Controllers\api\LoginController as ApiLoginController;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\AutenticacaoController;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
  public function processLogin(LoginRequest $request)
  {
    $controllerValidaAuth = new  AutenticacaoController();

    $auth = $controllerValidaAuth->autenticar($request);

   return  $this->redirecionarPage($auth);
  }

  public function redirecionarPage($dadosAuth)
  {

    if ($dadosAuth) {

      return view('pages.home');
    } else {

      return back()->withInput()->with('msg', 'Usúario ou senha inválidos');
    }
  }

  public function processDestroy()
  {
    $you = Auth::user();
    //deleto todas as chaves
    $loggoutToken = new ApiLoginController();

    $loggoutToken->loggout($you);

          Auth::logout();


    return view('pages.listar');
  }
}
