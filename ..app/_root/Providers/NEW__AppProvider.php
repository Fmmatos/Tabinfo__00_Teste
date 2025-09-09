<?php

namespace Root\Providers;

use Illuminate\Support\Facades\App;
use Vendor\Providers\__LogsQuery;

class NEW__AppProvider
{

    public static function boot(): void
    {

        // NEW
        // NEW





        // __GLOBAL__
            if (!App::runningInConsole() || !self::isMigrating()) {
                __GLOBAL__();
            }
        // __GLOBAL__





        // BOOT
            if (!App::runningInConsole() || !self::isMigrating()) {
                // __LogsQuery::boot();
            }
        // BOOT

    }

    protected static function isMigrating(): bool
    {
        $commands = ['migrate', 'migrate:install', 'migrate:fresh', 'migrate:refresh', 'migrate:reset', 'migrate:rollback', 'migrate:status'];
        $currentCommand = $_SERVER['argv'][1] ?? null;

        return in_array($currentCommand, $commands);
    }

}