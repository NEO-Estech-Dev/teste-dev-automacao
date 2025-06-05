<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ArquivoCvRequest;
use App\Jobs\ProcessarImportacaoTemperatura;
use App\Jobs\Temperaturajob;

class ArquivosController extends Controller
{
 
    public function file(ArquivoCvRequest $dados)
    {
             if(!$dados->hasFile('arquivo')){
           
                 return response()->json(['Status' => 0, 'menssage' => 'Consulte o Administrador do Sistema'], 500);
            }
             
                 $file = $dados->file('arquivo');
                
                  //verifico a extensao
                  $extensao = $file->getClientOriginalExtension();
                  if($extensao != 'csv')
                  {
                    return response()->json(['Status' => 0, 'menssage' => 'Precisa ser um arquvio  CSV'], 500); 
                  }
             
                  $path = $file->store('uploads');
  
                   $retorno  =  Temperaturajob::dispatch($path);
                   
                  if($retorno){
                       return response()->json(['Status' => 2, 'menssage' => 'Arquivo Enviando com successo'], 200);
                  }else{
                       return response()->json(['Status' => 2, 'menssage' => 'Falha ao Enviar Arquivo'], 200);
                  }
            
              return response()->json(['Status' => 2, 'menssage' => 'Arquivo Enviando com successo'], 200);
    }
}
