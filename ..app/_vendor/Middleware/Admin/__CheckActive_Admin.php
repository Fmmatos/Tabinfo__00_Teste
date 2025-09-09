<?php

namespace Vendor\Middleware\Admin;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class __CheckActive_Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle(Request $request, Closure $next): JsonResponse
    {
        if (Auth::check() && Auth::user()->active == 1) {
            return $next($request);
        }

        return response()->json([
            'message' => 'Você não tem permissão para acessar esta área.',
        ], 403);
    }
}
