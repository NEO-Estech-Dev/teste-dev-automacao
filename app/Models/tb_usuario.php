<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class tb_usuario extends Model
{
    use SoftDeletes;
    
    protected $table = 'tb_usuarios';
  
      
    public static function gettbUserInsert($phoneMask,$request,$filename,$idUserInsert)
    {
           extract($request->all());

           DB::beginTransaction();
         
          try{
          
            $insert = [

            'idUser' => $idUserInsert,
            'name' => $nome,
            'phone' => $phoneMask,
            'genero' => $genero,
            'idFomacao' => $nivelFormacao,
            'nameCurso' => $formacoes ,
            'cv' => $filename, 
            'estado' => $estado,
            'cidade' => $city,
            'created_at' => now()
            ];

            $result =  DB::table('tb_usuarios')->insert($insert);
            
               DB::commit();
              
               return $result;

    } catch (\Exception $e){

        return $e;
    }
 }
     public static function getId($id)
     {
            
          $retorno = DB::table('Users as user')
         ->leftJoin('tb_usuarios as canditados', 'canditados.idUser'  ,'=', 'user.id')
         ->select(
            'user.name as nomes',
            'user.id as idUsers',
            'user.email',
            'user.nivelUser',
            'user.deleted_at as info',
            'canditados.*'
         )->where('user.id', '=', $id)
         ->get();
          
         return $retorno;

     }

     
        public static function updatedId($request,$retornoPhome,$retornoEstado,$retornoCidade,$retornoCurso)
        {
               extract($request->all());
         $redis = Redis::connection('default');
         $json = ['id' => $id, 'dia' => Carbon::now(), 'Ativado', Auth::user() ];
         
         $redis->set('update_user', json_encode($json));
         
         DB::table('tb_usuarios')
            ->where('idUser', $id)
            ->update([
                'phone' => $retornoPhome,
                'name' => $nome,
                'idFomacao' => $retornoCurso,
                'nameCurso' => $nameCurso,
                'estado' => $retornoEstado,
                'cidade' => $retornoCidade,
                'updated_at' => now(),
            ]);

        DB::table('users')
            ->where('id', $id)
            ->update([
                'name' => $nome,
                'email' => $email
            ]);

        return true;
            
        }


}


