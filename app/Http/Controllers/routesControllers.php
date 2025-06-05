<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class routesControllers extends Controller
{
    public function index()
    {
        return view('pages.listar');

    }

     public function Homes()
    {
        return view('pages.home');

    }
     public function Login()
    {
        return view('pages.login');

    }
      public function Cad()
    {
        return view('pages.Cadastrar');

    } 
      public function List()
    {
        return view('pages.ListarUser');

    }

    public function ExibirPerfil()
    {
        return view('pages.Perfil');
    }
    public function Tem()
    {
        return view('pages.Temperatura');
    }
    
}
