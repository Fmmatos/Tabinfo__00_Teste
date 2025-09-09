<?php

namespace Root\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class NEW__RouterModulesMiddleware
{

    public function handle(Request $request, Closure $next): JsonResponse
    {

        return $next($request);

    }


}
