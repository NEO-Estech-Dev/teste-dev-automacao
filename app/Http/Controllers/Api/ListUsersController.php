<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DelRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\api\LoginController;
use App\Http\Requests\AtivarUserRequest;
use Carbon\Carbon;
use App\Http\Requests\EditCandiRequest;
use App\Http\Requests\EditRequest;
use App\Http\Requests\RecruiterRequest;
use App\Http\Requests\RecruterEditResquest;
use App\Models\tb_empresa_vaga;
use App\Models\tb_recruite;
use App\Models\tb_usuario;
use Illuminate\Foundation\Http\FormRequest;

class ListUsersController extends Controller
{
    public function storeUsers(DelRequest $dadoRequest): JsonResponse
    {

        $lista = [];

        $dados = new FuncitionController();



        $retorno = User::getUsersListall($dadoRequest);
        $estadosIBGE = Http::get("https://servicodados.ibge.gov.br/api/v1/localidades/estados");

        if (!$estadosIBGE->successful()) {
            return response()->json(['erro' => 'Não foi possível buscar os estados'], 500);
        }

        $estados = $estadosIBGE->json();
        foreach ($retorno as $item) {
            foreach ($item as $campo => $valor) {

                if (!isset($valor) || is_null($valor)) {
                    $item->$campo = '*';
                }

                if ($campo == 'estado' && is_numeric($valor)) {

                    $estadoEncontrado = collect($estados)->firstWhere('id', (int)$valor);
                    if ($estadoEncontrado) {
                        $item->estado = $estadoEncontrado['nome'];
                    }
                }

                if ($campo == 'cidade' && is_numeric($valor)) {
                    $municipioResponse = Http::get("https://servicodados.ibge.gov.br/api/v1/localidades/municipios/" . $valor);

                    if ($municipioResponse->successful()) {
                        $municipio = $municipioResponse->json();
                        $item->cidade = $municipio['nome'] ?? '*';
                    } else {
                        $item->cidade = 'Sem informaçáo';
                    }
                }

                if ($campo == 'phone' && is_numeric($valor)) {
                    $retornoPhones = $dados->maskPhone($valor);
                    $item->phone = $retornoPhones;
                }

                if ($campo == 'genero' && is_numeric($valor)) {
                    $retornoGenero = $dados->functionGenero($valor);
                    $item->genero = $retornoGenero;
                }
                if ($campo == 'idFomacao' && is_numeric($valor)) {
                    $retornoFormacao = $dados->functionFormacao($valor);
                    $item->idFomacao = $retornoFormacao;
                }
                if ($campo == 'nivelUser' && is_numeric($valor)) {
                    $infoNivel = $dados->functionNivel($valor);
                    $item->nivelUser = $infoNivel;
                }
                if ($campo == 'info') {
                    $infos = $dados->functionAtivo($valor);
                    $item->info = $infos;
                }
            }
            //salvo no array todas as alteracoes
            $lista[] = $item;
        }


        if ($lista) {

            return response()->json(['Status' => 2, 'data' => $lista,  'menssage' => 'Sucesso em consultar Lista'], 200);
        } else {

            return response()->json(['Status' => 0, 'menssage' => 'Falha ao Solicitar Lista'], 500);
        }

        return response()->json(['Status' => 0, 'menssage' => 'Consulte o Administrador do Sistema'], 500);
    }


    public function dell(DelRequest $dados)
    {
        $verificar = new FuncitionController();
        //verifico se esta logado, por mas que tenha o token

        $user = $verificar->finduser();

        if (!isset($user->nivelUser) || $user->nivelUser != 2) {

            return response()->json(['erro' => 'Não foi possivel Deletar Usuario'], 500);
        }

        //realizar o delete

        $up = User::getUserId($dados->id);

        if ($up->nivelUser == 2) {

            $result = User::delleteUserAdm($dados->id);
            //limpo se tiver um token existente
            User::clearToken($dados->id);
        } else {
        }

        dd($result);
    }

    public function ative(AtivarUserRequest $request): JsonResponse
    {

        $verificar = new FuncitionController();
        //verifico se esta logado, por mas que tenha o token
        $user = $verificar->finduser();

        //remover date
        if (!isset($user->nivelUser) || $user->nivelUser != 2) {

            return response()->json(['erro' => 'Não foi possivel Ativar '], 500);
        }

        $reuslt = user::getUpUsers($request);

        if ($reuslt) {

            return response()->json(['Status' => 2, 'menssage' => 'Sucesso em consultar Lista'], 200);
        } else {

            return response()->json(['Status' => 0, 'menssage' => 'Falha ao Solicitar Lista'], 500);
        }

        return response()->json(['Status' => 0, 'menssage' => 'Consulte o Administrador do Sistema'], 500);
    }



    //pegar os dados do usuario 
    public function allUser(EditRequest $dados)
    {
        //armazeno o resultado
        $dadosTratado = [];
        extract($dados->all());

        $dados = new FuncitionController();
        if ($nivel == 1) {

            $result  =  tb_recruite::getuser($id);

            foreach ($result as $item) {
                foreach ($item as $campo => $valor) {

                    if ($campo == 'phone' && is_numeric($valor)) {
                        $retornoPhones = $dados->maskPhone($valor);
                        $item->phone = $retornoPhones;
                    }
                }
                $dadosTratado[] = $item;
            } //primeiro foareach 

            if ($dadosTratado) {

                return response()->json(['Status' => 2, 'data' => $dadosTratado, 'menssage' => 'Sucesso em consultar Lista'], 200);
            } else {

                return response()->json(['Status' => 0, 'menssage' => 'Falha ao Solicitar Lista'], 500);
            }
        } //primeiro if   

    }
    public function EditUser(RecruterEditResquest $dados)
    {
        $verify = new FuncitionController();
        $retornoMask = $verify->removeMask($dados->phone);

        $retornoEmail = $verify->verificarEmail($dados->email);

        if (!$retornoEmail) {

            return response()->json(['Status' => 2,  'menssage' => 'Verifique o E-mail informado'], 200);
        }
        $retorno = tb_recruite::upRecruiter($dados, $retornoMask);

        if ($retorno) {
            return response()->json(['Status' => 2, 'menssage' => 'Sucesso em Atualizar Perfil'], 200);
        } else {

            return response()->json(['Status' => 0, 'menssage' => 'Falha em Editar Perfil '], 500);
        }
    }

    public function Perfil(EditRequest $request): JsonResponse
    {

        extract($request->all());

        $lista = [];

        $dados = new FuncitionController();


        $result =  tb_usuario::getId($id);

        $estadosIBGE = Http::get("https://servicodados.ibge.gov.br/api/v1/localidades/estados");

        if (!$estadosIBGE->successful()) {
            return response()->json(['erro' => 'Não foi possível buscar os estados'], 500);
        }

        $estados = $estadosIBGE->json();
        foreach ($result as $item) {
            foreach ($item as $campo => $valor) {

                if ($campo == 'estado' && is_numeric($valor)) {

                    $estadoEncontrado = collect($estados)->firstWhere('id', (int)$valor);
                    if ($estadoEncontrado) {
                        $item->estado = $estadoEncontrado['nome'];
                    }
                }

                if ($campo == 'cidade' && is_numeric($valor)) {
                    $municipioResponse = Http::get("https://servicodados.ibge.gov.br/api/v1/localidades/municipios/" . $valor);

                    if ($municipioResponse->successful()) {
                        $municipio = $municipioResponse->json();
                        $item->cidade = $municipio['nome'] ?? '*';
                    } else {
                        $item->cidade = 'Sem informaçáo';
                    }
                }

                if ($campo == 'phone' && is_numeric($valor)) {
                    $retornoPhones = $dados->maskPhone($valor);
                    $item->phone = $retornoPhones;
                }

                if ($campo == 'genero' && is_numeric($valor)) {
                    $retornoGenero = $dados->functionGenero($valor);
                    $item->genero = $retornoGenero;
                }
                if ($campo == 'idFomacao' && is_numeric($valor)) {
                    $retornoFormacao = $dados->functionFormacao($valor);
                    $item->idFomacao = $retornoFormacao;
                }
                if ($campo == 'nivelUser' && is_numeric($valor)) {
                    $infoNivel = $dados->functionNivel($valor);
                    $item->nivelUser = $infoNivel;
                }
                if ($campo == 'info') {
                    $infos = $dados->functionAtivo($valor);
                    $item->info = $infos;
                }
            }
            //salvo no array todas as alteracoes
            $lista[] = $item;
        }

        if ($lista) {

            return response()->json(['Status' => 2, 'data' => $lista,  'menssage' => 'Sucesso em consultar Lista'], 200);
        } else {

            return response()->json(['Status' => 0, 'menssage' => 'Falha ao Solicitar Lista'], 500);
        }




        return response()->json(['Status' => 0, 'menssage' => 'Contate o Administrador '], 500);
    }

    public function EditUserCandy(EditCandiRequest $request): JsonResponse
    {

        $tratar = new FuncitionController();

        $retornoPhome = $tratar->removeMask($request->phone);
        $retornoEmail = $tratar->verificarEmail($request->email);
        $retornoEstado = $tratar->consultaibge($request->estado);
        $retornoCidade = $tratar->consultaCidade($request->cidade);
        $retornoCurso = $tratar->verificarCurso($request->nameFormacao);
        
         if (!is_numeric($retornoPhome)) {
            return response()->json(['Status' => 0, 'menssage' => 'Verifique o campo Telefone'], 500);
        }

        if (!$retornoEmail) {
            return response()->json(['Status' => 0, 'menssage' => 'Verifique o Campo e-mail'], 500);
        }

        if (!is_numeric($retornoEstado)) {
            return response()->json(['Status' => 0, 'menssage' => 'Verifique o Campo Estado'], 500);
        }
        if (!is_numeric($retornoCidade)) {
            return response()->json(['Status' => 0, 'menssage' => 'Verifique o Campo Cidade'], 500);
        }
  
           $retornoUp =  tb_usuario::updatedId($request,$retornoPhome,$retornoEstado,$retornoCidade,$retornoCurso);
  
           
        if ($retornoUp) {

            return response()->json(['Status' => 2,   'menssage' => 'Sucesso em Atualizar'], 200);
        } else {

            return response()->json(['Status' => 0, 'menssage' => 'Falha ao Solicitar Lista'], 500);
        }
 
          return response()->json(['Status' => 0, 'menssage' => 'Contate o Administrador '], 500);
    }

   
     public function NewCurriculo(FormRequest $request) : JsonResponse 
     {
                  $newDados = new FuncitionController();

         if(!$request->hasFile('arquivoCv')){
           
               return response()->json(['Status' => 0, 'menssage' => 'Verifique o Arquivo Enviado'], 500);
           }


              $redis = Redis::connection('default');
              $json = ['id' => $request->id, 'dia' => Carbon::now(), 'Ativado', Auth::user() ];
         
         $redis->set('atualiado_curriculo_user', json_encode($json));
        
            $filename =  $newDados->saveCv($request->arquivoCv, $request->nome,$request->id);

          if ($filename) {

            return response()->json(['Status' => 2,   'menssage' => 'Sucesso em Atualizar'], 200);
        } else {

            return response()->json(['Status' => 0, 'menssage' => 'Falha em Atualizar '], 500);
        }
 
             
         return response()->json(['Status' => 0, 'menssage' => 'Contate o Administrador '], 500);
     }

     public  function PickUp():JsonResponse
     {
        
        $verificar = new FuncitionController();
        //verifico se esta logado, por mas que tenha o token
        $user = $verificar->finduser();

        if (!isset($user->nivelUser) ) {

            return response()->json(['Status' => 0,'menssage' => 'Verique sem tem acesso para listar '], 500);
        }
           if (in_array($user->nivelUser, [2, 0])) {
              return response()->json([ 'Status' => 0,'menssage' => 'Acesso não permitido.']);
        }
           $tratarDados  =  tb_empresa_vaga::getVagasPickup();
        
     
        foreach ($tratarDados as $item) {
            foreach ($item as $campo => $valor) {

                if ($campo == 'estado' && is_numeric($valor)) {

                    $estadoEncontrado =   $verificar->getIdestado((int)$valor); 

                    if ($estadoEncontrado) {
                        
                        $item->estado = $estadoEncontrado['nome'];
                    }
                }

                if ($campo == 'cidade' && is_numeric($valor)) {
                    
                    $estadoEncontrado =   $verificar->getIdMunicipio((int)$valor); 
                   
                     $item->cidade = $estadoEncontrado['nome'];
                
                    }

                if ($campo == 'phone' && is_numeric($valor)) {
                    $retornoPhones = $verificar->maskPhone($valor);
                    $item->phone = $retornoPhones;
                }

                if ($campo == 'genero' && is_numeric($valor)) {
                    $retornoGenero = $verificar->functionGenero($valor);
                    $item->genero = $retornoGenero;
                }
                if ($campo == 'idFomacao' && is_numeric($valor)) {
                    $retornoFormacao = $verificar->functionFormacao($valor);
                    $item->idFomacao = $retornoFormacao;
                }
                if ($campo == 'nivelUser' && is_numeric($valor)) {
                    $infoNivel = $verificar->functionNivel($valor);
                    $item->nivelUser = $infoNivel;
                }
                if ($campo == 'info') {
                    $infos = $verificar->functionAtivo($valor);
                    $item->info = $infos;
                }
            }
            //salvo no array todas as alteracoes
            $lista[] = $item;
        }

        if ($lista) {

            return response()->json(['Status' => 2, 'data' => $lista,  'menssage' => 'Sucesso em consultar Lista'], 200);
        } else {

            return response()->json(['Status' => 0, 'menssage' => 'Falha ao Solicitar Lista'], 500);
        }






          return response()->json(['Status' => 0, 'menssage' => 'Contate o Administrador '], 500);
     }
}
