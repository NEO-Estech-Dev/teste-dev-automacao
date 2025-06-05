<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\api\FuncitionController;
use App\Http\Controllers\Controller;
use App\Models\tb_usuario;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\RecruiterRequest;
use App\Http\Requests\PushTokenRequest;
use App\Models\tb_recruite;

class CadastroUserController extends Controller
{
   
   
    public function processCad(Request $request) : JsonResponse
  {
 
        $retorno = User::getUser($request->email);

        if($retorno){
              
               return response()->json([
                'Status' => 0,
                'Message' => 'Usuario já Cadastrado!'
            ],500);
        }
        
          //trato phone 
         $newDados = new FuncitionController();
        
         $phoneMask = $newDados->removeMask($request->phone);
        
         //aqui vem o id do insert na base
        
          $id = User::getUserInsert($request);

         //veririca se tem o arquivo
         
         if($request->hasFile('arquivoCv')){
           
            $filename =  $newDados->saveCv($request->arquivoCv, $request->nome,$id);
           
           }

            //inserir baase e criar Token

            $retono = tb_usuario::gettbUserInsert($phoneMask,$request,$filename,$id);

            if($retono){
                
         return response()->json(['Status' => 2, 'menssage' => 'Usúario Cadastrado Efetue o login'], 200);
        } else {

            return response()->json(['Status' => 0, 'menssage' => 'Falha ao inserir'], 500);
        }

        return response()->json(['Status' => 0, 'Menssage' => 'Consulte o Administrador do Sistema'], 500);

     }



     //para o recrutador
       public function  recruiterCadRecutrador(RecruiterRequest $dados)
      {
        
        $retorno = User::getUser($dados->email);

        if($retorno){
              
               return response()->json([
                'Status' => 0,
                'Message' => 'Usuario já Cadastrado!'
            ],500);
        }
           $newDados = new FuncitionController();
        
         $phoneMask = $newDados->removeMask($dados->phone);
        
         //aqui vem o id do insert na base
        
              $id = User::getUserInsert($dados);


              $retono = tb_recruite::getRecruiterInsert($phoneMask,$dados,$id);

            if($retono){
                        
             return response()->json(['Status' => 2, 'menssage' => 'Usúario Cadastrado Efetue o login'], 200);
         } else {

            return response()->json(['Status' => 0, 'menssage' => 'Falha ao inserir'], 500);
        }


      
        return response()->json(['Status' => 0, 'Menssage' => 'Consulte o Administrador do Sistema'], 500);
      }


      public function allToken(PushTokenRequest $dados) : JsonResponse
      {
  
         $retorno = User::getAllUser($dados);

            if($retorno){
                        
             return response()->json(['Status' => 2, 'data' => $retorno, 'menssage' => 'Usúario Cadastrado Efetue o login'], 200);
         } else {

            return response()->json(['Status' => 0, 'menssage' => 'Falha ao inserir'], 500);
        }
 return response()->json(['Status' => 0, 'Menssage' => 'Consulte o Administrador do Sistema'], 500);
      }
 
}
