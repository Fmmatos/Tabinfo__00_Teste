<?php

namespace Root\Middleware;

use Illuminate\Support\Facades\Auth;

class NEW__Middleware
{

    // BOOT
        public static function boot(): void
        {

            if(Auth::check()){

            }

        }
    // BOOT

}
