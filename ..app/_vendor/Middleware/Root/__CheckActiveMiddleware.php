<?php

namespace Vendor\Middleware\Root;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Root\Middleware\NEW__Middleware;

class __CheckActiveMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle(Request $request, Closure $next): mixed
    {
        if (Auth::check() && Auth::user()->active == 1) {

            // NEW
                NEW__Middleware::boot($request);
            // NEW
            
            return $next($request);
        }

        return response()->json([
            'message' => 'Você não tem permissão para acessar esta área.',
        ], 403);
    }
}
