<?php

namespace Root\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class NEW__EventsExternalMiddleware
{

    public function handle(Request $request, Closure $next): JsonResponse
    {

        $header = $request->input('Authorization');
        if(!$header || !str_starts_with($header, 'Bearer ')){
            return response()->json(['error' => 'Não autenticado'], 401);
        }

        $tokenPlain = substr($header, 7);
        $tokenModel = PersonalAccessToken::findToken($tokenPlain);
        if(!$tokenModel || !$tokenModel->tokenable){
            return response()->json(['error' => 'Token inválido'], 401);
        }

        $user = $tokenModel->tokenable;
        $request->setUserResolver(function () use ($user) {
            return $user;
        });
        if(!$user->active){
            return response()->json(['error' => 'Usuário inativo'], 403);
        }

        return $next($request);

    }


}
