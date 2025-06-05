<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\JwtService;
use Closure;
use Illuminate\Http\Request;
use Lcobucci\JWT\UnencryptedToken;
use Symfony\Component\HttpFoundation\Response;

class JwtAuthMiddleware
{
    protected JwtService $jwtService;

    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        if(!$request->headers->has('Authorization')) {
            return response()->json(['error' => 'Token não informado'], status: Response::HTTP_UNAUTHORIZED);
        }

        $auth = $request->headers->get('Authorization');
        list($type, $tokenStr) = explode(' ', $auth, 2);
        if ($type != 'Bearer') {
            return response()->json(['error' => 'Token não informado'], status: Response::HTTP_UNAUTHORIZED);
        }

        try {
            if (!$this->jwtService->validateToken($tokenStr)) {
                return response()->json(['error' => 'Token inválido'], Response::HTTP_UNAUTHORIZED);
            }

            $uncrytedToken = $this->jwtService->parseToken($tokenStr);
            $tokenClaims = $uncrytedToken->claims();

            $userId = $tokenClaims->get('sub');
            if (!$userId) {
                return response()->json(['error' => 'Token inválido'], Response::HTTP_UNAUTHORIZED);
            }

            $user = User::find($userId);
            if (is_null($user)) {
                return response()->json(['error' => 'Usuário não encontrado'], status: Response::HTTP_UNAUTHORIZED);
            }

            $request->setUserResolver(fn () => $user);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Token inválido'], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
