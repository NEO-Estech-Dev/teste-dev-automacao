<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Database\Eloquent\SoftDeletes;
use PhpParser\Node\Stmt\Catch_;
use PhpParser\Node\Stmt\Static_;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;

class User extends Authenticatable
{
    use  SoftDeletes, HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public static function getUser($email)
    {
        $retorno = DB::table('users')->where('email', $email)->get();
        
        return  $retorno->isEmpty() ? false : $retorno; 
    }

    public static function getUserInsert( $dados)
    {

         extract($dados->all());

       
        
         DB::beginTransaction();


         try{
            $insert = [
               'name' => $nome,
               'email'=> $email,
               'password' => Hash::make($password, ['rounds'=> 12]),
               'nivelUser' => $tipo,
               'created_at' => now()

            ];

             $id = DB::table('Users')->insertGetId($insert);

              DB::commit();

             return $id;
            
         } catch (\Exception $e){

            return $e;
         }

    }
       public static function clearToken($id)
       {
        return PersonalAccessToken::where('tokenable_id', $id)->delete();
       }
     
       public static function getAllUser($dados)
       {
          $retorno = DB::table('Users as user')
         ->leftJoin('personal_access_tokens as tokens', 'tokens.tokenable_id'  ,'=', 'user.id')
         ->select(
            'tokens.token',
         )->where('user.id', '=', $dados->id)
         ->get();
        
        return $retorno[0];
       }

       public static function getUserId($dados)
       { 
              
             $result =  DB::table('users')->where('id', $dados)->select('nivelUser', 'id')->get();

             return $result[0];

       }
        public static function getUsersListall($dados)
       { 
         
        ///náo exibi o id do user logado
        
         $retorno = DB::table('Users as user')
         ->leftJoin('tb_usuarios as canditados', 'canditados.idUser'  ,'=', 'user.id')
         ->leftJoin('tb_recruiter as recruiter', 'recruiter.idUserRecruiter'  ,'=', 'user.id')
         ->select(
            'recruiter.*',
            'user.name as nomes',
            'user.id as idUsers',
            'user.email',
            'user.nivelUser',
            'user.deleted_at as info',
            'canditados.*'
         )->where('user.id', '<>' ,$dados->id)
         ->get();
          
         return $retorno;
       }

       public static function delleteUserAdm($id)
       {
               //salvo no redis quando o user foi deletado
         $redis = Redis::connection('default');

         //para amanha salvar o token aqui e rucuperar para usar
         $json = ['id' => $id, 'dia' => Carbon::now(), 'deletadoPor', Auth::user() ];
         $redis->set('deletado user', json_encode($json));
          
         $retorno = DB::table('users')->where('id', $id)
           ->update(['deleted_at' => Carbon::now()]);
         return $retorno;
         
       }

       public static function getUpUsers($dados)
       {
           extract($dados->all());
         $redis = Redis::connection('default');
         $json = ['id' => $id, 'dia' => Carbon::now(), 'Ativado', Auth::user() ];
         
         $redis->set('deletado_user', json_encode($json));
        
         $retorno = DB::table('users')->where('id', $id)
        
            ->update(['deleted_at' => null]);
        
          return $retorno;

       }
     
}
