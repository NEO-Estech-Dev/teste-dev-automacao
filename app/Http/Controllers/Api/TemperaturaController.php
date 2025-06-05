<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\tb_temperatura;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TemperaturaController extends Controller
{
    
    public function retornoTemp(Request $request) : JsonResponse
    { 
      
        
         $retorno = tb_temperatura::getdados();

          if($retorno){
            
            return response()->json(['Status' => 2, 'data' => $retorno ,'Menssage' => 'Sucessom em consultar lista temperatura'], 200);

          }else{
              return response()->json(['Status' => 0,'Menssage' => 'Falha em consultar os dados'], 500);

          }
          return response()->json(['Status' => 0, 'Menssage' => 'Consulte o Administrador do Sistema'], 500);

    }
}
