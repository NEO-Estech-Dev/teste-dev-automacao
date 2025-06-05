<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\tb_empresa;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class tb_vaga extends Model
{
    use SoftDeletes;
    protected $table = 'tb_vagas';


    //inserir vagas 
    public static function PushVagas($dados)
    {

        extract($dados->all());

        if (is_array($modeloTra)) {
            $modeloTraString = implode(', ', $modeloTra); // Junta todos os valores com vírgula e espaço
        } else {
            $modeloTraString = $modeloTra; 
        }
 
          DB::beginTransaction();


        try {
            $insert = [
                'titulo' => $titulo,
                'descricao' => $descricao,
                'tipo_contrato' => $tipoContrato,
                'local' => $local,
                'salario' => $salario,
                'requisitos' => $requisitos,
                'beneficios' => $beneficios,
                'created_at' => now(),
                'modelo_vaga' => $modeloTraString,

            ];
            
            $idVaga = DB::table('tb_vagas')->insertGetId($insert);
            //faco o insert na empresa
            tb_empresa::inserAll($idVaga, $id);
 
             DB::commit();

            return $id;

        } catch (\Exception $e) {

              return 'Falha em inserir' . $e;
        }
    }

   
     public static function pausesVancy($dados)
     {
           extract($dados->all());
         $redis = Redis::connection('default');
         $json = ['id' => $id, 'dia' => Carbon::now(), 'Fechado', Auth::user() ];
         
         $redis->set('fechamento_Vaga', json_encode($json));
          $retorno = DB::table('tb_vagas as vaga')
         ->leftjoin('tb_empresa_vaga as empVagas' , 'empVagas.vaga_id', '=', 'vaga.id')
         ->where('empVagas.vaga_id' ,  '=', $idvaga)
         ->where('empVagas.id_empresa','=', $id)
         ->update(['vaga.fechamento_vaga' => Carbon::now(), 'vaga.updated_at' => now() ]);

          return $retorno;
     }

       public static function AtiveVaga($dados)
     {
           extract($dados->all());
         $redis = Redis::connection('default');
         $json = ['id' => $id, 'dia' => Carbon::now(), 'Ativado', Auth::user() ];
         
         $redis->set('Ativado', json_encode($json));
          $retorno = DB::table('tb_vagas as vaga')
         ->leftjoin('tb_empresa_vaga as empVagas' , 'empVagas.vaga_id', '=', 'vaga.id')
         ->where('empVagas.vaga_id' ,  '=', $idvaga)
         ->where('empVagas.id_empresa','=', $id)
         ->update(['vaga.fechamento_vaga' => null, 'vaga.updated_at' => now() ]);

          return $retorno;
     }

       public static function findFechamento($request)
       {
  
         extract($request->all());
         $retorno = DB::table('tb_vagas')->where('id', $idvaga)->whereNotNull('fechamento_vaga')->get();
         
         return  $retorno->isEmpty() ? false : true; 
       }
        
       public static function DeleteVaga($request)
       {
          extract($request->all());
    
          $redis = Redis::connection('default');
         $json = ['id' => $id, 'dia' => Carbon::now(), 'Deletado', Auth::user() ];
         
         $redis->set('Deletado', json_encode($json));
         $retorno = DB::table('tb_vagas')->where('id', $idvaga)->update(['deleted_at' => Carbon::now()]);
        
         return  $retorno ? true : false; 
       }


       public static function upVacancy($request)
       {
         extract($request->all());
        
         $redis = Redis::connection('default');
         $json = ['id' => $id, 'dia' => Carbon::now(), 'Atualizado_vaga', Auth::user() ];
         
         $redis->set('Atualizado_vaga', json_encode($json));
    
    
         if (is_array($modeloTra)) {
            $modeloTraString = implode(', ', $modeloTra);
        } else {
            $modeloTraString = $modeloTra; 
        }

         $retorno =  DB::table('tb_vagas')
            ->where('id', $idvagas)
            ->update([
                'titulo' => $titulo,
                'descricao' => $descricao,
                'tipo_contrato' => $tipoContrato,
                'salario' => $salario,
                'requisitos' => $requisitos,
                'beneficios' => $beneficios,
                'modelo_vaga' => $modeloTraString,
                'updated_at' => now(),
            ]);

             return $retorno > 0 ? true :false; 
       }
        
     


}
