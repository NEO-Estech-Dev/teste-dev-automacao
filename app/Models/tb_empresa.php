<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class tb_empresa extends Model
{
   use SoftDeletes;
    protected $table = 'tb_empresa_vaga';
    

    public static function inserAll($idvaga,$idRe)
    {

        
         DB::beginTransaction();


         try{
            $insert = [
               'id_empresa' => $idRe,
               'vaga_id'=> $idvaga,
               'created_at' => now()

            ];

              DB::table('tb_empresa_vaga')->insertGetId($insert);
 
             DB::commit();

             return true;
            
         } catch (\Exception $e){

           
            return $e;
         }


    }
}
