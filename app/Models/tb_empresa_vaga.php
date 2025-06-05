<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class tb_empresa_vaga extends Model
{
    use  SoftDeletes;
    protected $table = 'tb_empresa_vaga';
    
    public static function getidVaga($id)
      {

       $retornoVagas =  DB::table('tb_empresa_vaga as vagasEmp')
         ->leftjoin('tb_vagas as tbvagas', 'tbvagas.id' ,'=', 'vagasEmp.vaga_id')
         ->leftJoin('tb_recruiter as rc', 'rc.idUserRecruiter', '=', 'vagasEmp.id_empresa')
         ->select(
         'tbvagas.titulo',
         'tbvagas.descricao',
         'tbvagas.tipo_contrato',
         'tbvagas.local',
         'tbvagas.requisitos',
         'tbvagas.modelo_vaga',
         'tbvagas.fechamento_vaga  as infoVaga',
         'tbvagas.salario',
         'tbvagas.beneficios',
         'tbvagas.created_at',
         'rc.nome_empresa',
         'tbvagas.id',
         'tbvagas.deleted_at',
         )->where('vagasEmp.id_empresa',  '=', $id )
         ->whereNull('tbvagas.deleted_at')->get();



        return $retornoVagas;

      }
      public static function getAllvagas()
      {

       $retornoVagas =  DB::table('tb_empresa_vaga as vagasEmp')
         ->leftjoin('tb_vagas as tbvagas', 'tbvagas.id' ,'=', 'vagasEmp.vaga_id')
         ->leftJoin('tb_recruiter as rc', 'rc.idUserRecruiter', '=', 'vagasEmp.id_empresa')
         ->select(
         'tbvagas.titulo',
         'tbvagas.descricao',
         'tbvagas.tipo_contrato',
         'tbvagas.local',
         'tbvagas.requisitos',
         'tbvagas.modelo_vaga',
         'tbvagas.fechamento_vaga',
         'tbvagas.salario',
         'tbvagas.beneficios',
         'tbvagas.created_at',
         'rc.nome_empresa',
         'tbvagas.id'
         )->whereNull('tbvagas.deleted_at')->get();
        
          return $retornoVagas;

      }


      public static function getVagasPickup()
      {

            $retorno = DB::table('tb_candidato_vaga as cvgas')
        ->leftjoin('tb_vagas as vaga' , 'vaga.id', '=','cvgas.vaga_id')
        ->leftjoin('tb_empresa_vaga as empVagas' , 'empVagas.vaga_id', '=', 'vaga.id')
        ->leftjoin('tb_recruiter as recru' , 'recru.idUserRecruiter', '=', 'empVagas.id_empresa')
        ->leftJoin('tb_usuarios as candidatos' , 'candidatos.idUser', '=', 'cvgas.candidato_id')
        ->select(
         'recru.nome_empresa',
         'vaga.titulo',
         'vaga.tipo_contrato',
         'vaga.local',
         'cvgas.created_at',
         'vaga.modelo_vaga',
         'cvgas.id',
         'vaga.deleted_at as info',
         'candidatos.name',
         'candidatos.phone',
         'candidatos.genero',
         'candidatos.idFomacao',
         'candidatos.nameCurso',
         'candidatos.cv',
         'candidatos.estado',
         'candidatos.cidade',
         'candidatos.idUser',
          
         )
         ->get();

        
          return $retorno;
      }
}
