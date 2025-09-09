<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Vendor\Providers\__AppProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // evita erro “Specified key was too long …” em MariaDB/MySQL
        Schema::defaultStringLength(191);

        __AppProvider::boot();
    }
}
