<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class tb_recruite extends Model
{
    use SoftDeletes;
    protected $table = 'tb_recruiter';


    public static function getRecruiterInsert($phoneMask, $request, $idUserInsert)
    {

        extract($request->all());

        DB::beginTransaction();


        try {

            $insert = [
                'idUserRecruiter' => $idUserInsert,
                'phone' => $phoneMask,
                'nome_empresa' => $nempresa,
                'created_at' => now()
            ];

            $result = DB::table('tb_recruiter')->insert($insert);

            DB::commit();

            return $result;
        } catch (\Exception $e) {

            return $e;
        }
    }

    public static function getuser($id)
    {
        $retornoQuery =  DB::table('tb_recruiter as recru')
            ->leftjoin('users as user', 'user.id', '=', 'recru.idUserRecruiter')
            ->select(
                'recru.idUserRecruiter',
                'recru.phone',
                'recru.nome_empresa',
                'user.id',
                'user.name',
                'user.email'
            )
            ->where('user.id', '=', $id)->get();


        return  $retornoQuery;
    }

    public static function upRecruiter($dados,$newPhone)
    {

        extract($dados->all());


        $redis = Redis::connection('default');
        //para amanha salvar o token aqui e rucuperar para usar
        $json = ['id' => $id, 'dia' => Carbon::now(), 'Atualizado por', Auth::user()];
        $redis->set('Atualizado', json_encode($json));

        DB::table('tb_recruiter')
            ->where('idUserRecruiter', $id)
            ->update([
                'phone' => $newPhone,
                'nome_empresa' => $nameEmpresa
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
