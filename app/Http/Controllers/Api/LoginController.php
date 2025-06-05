<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Mockery\Expectation;

class LoginController extends Controller
{
    
    public function index(Request $request) : JsonResponse
    {
      
        $user = User::find($request->id);

     if (!$user) {
        
         return response()->json(['Status' => false, 'message' => 'Usuário não encontrado'], 404);
      }
    
        $token = $user->createToken('token-api')->plainTextToken;

         return response()->json(['Status' => 2, 'token' => $token, 'user' => $user],201);

      
    }

    public function loggout(User $user)
    {
        try{
  
             $user->tokens()->delete();

             
              response()->json(['Status' => true,'message' => 'Deslogado com sucesso'],200);



        }catch (Expectation $e){
            
                response()->json(['Status' => false,'message' => 'Falha em deslogar ' .$e],400);
        }
        
    }
}


