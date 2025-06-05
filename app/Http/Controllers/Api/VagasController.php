<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CandidacyRequest;
use App\Http\Requests\PauseVancyRequest;
use App\Http\Requests\VagasRequest;
use App\Models\tb_empresa;
use App\Models\tb_empresa_vaga;
use App\Models\tb_vaga;
use App\Models\tb_candidato_vagas;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class VagasController extends Controller
{
    public function StoreVagas(VagasRequest $request) :JsonResponse
    {
 
         $restultado =  tb_vaga::PushVagas($request);

         if($restultado){
          
               return response()->json(['Status' =>2, 'menssage' => 'Sucesso ao Cadastrar Vaga'],200);
       
             }else{
          
            return response()->json(['Status' =>0, 'mensage' => 'Falhar ao Inserir'],200);
          }

         return response()->json(['Status' =>0, 'mensage' => 'Contate o Administrador'],500);
    }

    public function ListVagasid(Request $dados)
    {
          $retorno = tb_empresa_vaga::getidVaga($dados->id);

           if($retorno){
               return response()->json(['Status' =>2, 'data' => $retorno, 'menssage' => 'Sucesso ao Consultar'],200);
       
             }else{
          
            return response()->json(['Status' =>0, 'mensage' => 'Contate o Administrador'],200);

         }
    }


     ///rota publica para lista as vagas ao usuarios
     public function ListarAllvagas() : JsonResponse
     {
             
         $retonoVagas = tb_empresa_vaga::getAllvagas();

         
           if($retonoVagas){
               return response()->json(['Status' =>2, 'data' => $retonoVagas, 'menssage' => 'Sucesso ao listar as vagas'],200);
       
             }else{
          
            return response()->json(['Status' =>0, 'mensage' => 'Contate o Administrador'],500);
          
          }


       return response()->json(['Status' =>0, 'mensage' => 'Contate o Administrador'],500);
        
     }

     public function CadUserVg(CandidacyRequest $request) :JsonResponse
      {
           
            $verificar = new FuncitionController();
            //verifico se esta logado, por mas que tenha o token 
             $user = $verificar->finduser();

             if(!$user){
             
                return response()->json(['Status' =>0, 'mensage' => 'Contate o Administrador'],500);
             }

             $insertVaga = tb_candidato_vagas::getInsertVaga($request);
           
           
             if($insertVaga){
                
              return response()->json(['Status' =>2, 'menssage' => 'Sucesso ao Candidatar a vaga'],200);
       
             }else{
          
            
              return response()->json(['Status' =>0, 'mensage' => 'Falha a se Candidatar'],500);
          
          }
 
       return response()->json(['Status' =>0, 'mensage' => 'Contate o Administrador'],500);
        
     }


     //busca para exibir as vagas de acordo com o id 

     public function listCandaciy(Request $request)
     {
              extract($request->all());
               $verificar = new FuncitionController();
            //verifico se esta logado, por mas que tenha o token 
             $user = $verificar->finduser();

             if(!$user){
             
                return response()->json(['Status' =>0, 'mensage' => 'Contate o Administrador'],500);
             }

                $resultBusca = tb_candidato_vagas::getId($id);

                // Garante que $resultBusca seja sempre um array
                if (is_null($resultBusca)) {
                    $resultBusca = [];
                }
              

    
       if($resultBusca){
                       
           return response()->json(['Status' => 2, 'data'=>$resultBusca,  'menssage' => 'Sucesso em consultar Lista'], 200);
         
         } else {

            return response()->json(['Status' => 0, 'menssage' => 'Falha ao Solicitar Lista'], 500);
        }
   
        return response()->json(['Status' => 0, 'menssage' => 'Consulte o Administrador do Sistema'], 500);

     }

 
      public function FuncoesVagas(PauseVancyRequest $request) :JsonResponse
       {

               $verificar = new FuncitionController();
            //verifico se esta logado, por mas que tenha o token 
             $user = $verificar->finduser();

             

             //pego para deletar 
             if(isset($request->funcao)){
                
               $retorno = tb_vaga::DeleteVaga($request);

               if($retorno){
               
               return response()->json(['Status' => 2, 'menssage' => 'Sucesso em Ativar Vaga'], 200);
         
         } else {

            return response()->json(['Status' => 0, 'menssage' => 'Falha em Ativar Vaga'], 500);
        }
     }//fechamento do isset

           
             $retornoFechamento = tb_vaga::findFechamento($request);

          

             if(!$retornoFechamento){
                $result = tb_vaga::pausesVancy($request);
              
                   if($result > 0){
                
               return response()->json(['Status' => 2, 'menssage' => 'Sucesso em Pausar Vaga'], 200);
         
         } else {

            return response()->json(['Status' => 0, 'menssage' => 'Falha em Pausa Vaga'], 500);
        }
    }
    
    //faco ativacao

    if($retornoFechamento){
           
          $result = tb_vaga::AtiveVaga($request);
              
                   if($result > 0){
                
               return response()->json(['Status' => 2, 'menssage' => 'Sucesso em Ativar Vaga'], 200);
         
         } else {

            return response()->json(['Status' => 0, 'menssage' => 'Falha em Ativar Vaga'], 500);
        }
       
    }
             return response()->json(['Status' => 0, 'menssage' => 'Consulte o Administrador do Sistema'], 500);
      }

      public function EditVancncie(VagasRequest $request) :JsonResponse
       {

             $verificar = new FuncitionController();
            //verifico se esta logado, por mas que tenha o token 
             $user = $verificar->finduser();

              $resultEdite = tb_vaga::upVacancy($request);
              
            if($resultEdite){
                
               
               return response()->json(['Status' => 2, 'menssage' => 'Sucesso em Atualizar Vaga'], 200);
         
         } else {

            return response()->json(['Status' => 0, 'menssage' => 'Falha em Atualizar Vaga'], 500);
        }

          return response()->json(['Status' => 0, 'menssage' => 'Consulte o Administrador do Sistema'], 500);
      }

}
