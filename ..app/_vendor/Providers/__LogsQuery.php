<?php

namespace Vendor\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// class __LogsQuery
// {
//     public static function boot(): void
//     {

//         DB::listen(function ($query) {
//             Log::info(
//                 $query->sql,
//                 $query->bindings,
//                 $query->time
//             );
//         });
//     }

// }

class __LogsQuery
{
protected static bool $cleaned = false;

public static function boot(): void
{
    DB::listen(function ($query) {
        $logFile = storage_path('logs/query.log');

        $entry = vsprintf(
            "[%s] SQL: %s | Bindings: %s | Time: %sms\n",
            [
                now()->toDateTimeString(),
                $query->sql,
                json_encode($query->bindings),
                $query->time,
            ]
        );

        // Apaga tudo e escreve apenas na primeira vez
        if (!self::$cleaned) {
            file_put_contents($logFile, $entry); // sobrescreve
            self::$cleaned = true;
        } else {
            file_put_contents($logFile, $entry, FILE_APPEND); // append nas próximas
        }
    });
}
}