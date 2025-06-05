<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class tb_candidato_vagas extends Model
{
    
    protected $table = "tb_candidato_vaga";



    
     public static function getInsertVaga($dados)
     {
         extract($dados->all());

        $redis = Redis::connection('default');
        //para amanha salvar o token aqui e rucuperar para usar
        $json = ['dia' => Carbon::now(), 'USUARIO MARCOU VAGA', Auth::user()];
        $redis->set('Atualizado', json_encode($json));

        
         DB::beginTransaction();


         try{
            $insert = [
               'candidato_id' => $id,
               'vaga_id'=> $idVaga,
               'created_at' => now()

            ];

              DB::table('tb_candidato_vaga')->insertGetId($insert);
 
             DB::commit();

             return true;
            
         } catch (\Exception $e){

         
            return false;

         }


        
     }

     public static function getId($id)
        {
   
        $retorno = DB::table('tb_candidato_vaga as cvgas')
        ->leftjoin('tb_vagas as vaga' , 'vaga.id', '=','cvgas.vaga_id')
        ->leftjoin('tb_empresa_vaga as empVagas' , 'empVagas.vaga_id', '=', 'vaga.id')
        ->leftjoin('tb_recruiter as recru' , 'recru.idUserRecruiter', '=', 'empVagas.id_empresa')
        ->select(
         'recru.nome_empresa',
         'vaga.titulo',
         'vaga.tipo_contrato',
         'vaga.local',
         'cvgas.created_at',
         'vaga.modelo_vaga',
         'cvgas.id',
         'vaga.deleted_at as info'
         )->where('cvgas.candidato_id', $id)
         ->get();

          return $retorno;
     }
    

}
