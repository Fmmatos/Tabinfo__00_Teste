<?php

namespace Vendor\Providers;

use Illuminate\Support\Facades\App;
use Laravel\Sanctum\PersonalAccessToken;

class __AppProvider
{

    public static function boot(): void
    {
        // VERIFICA SE O SISTEMA ESTÁ RODANDO NO CONSOLE PARA EVITAR ERROS
            if (App::runningInConsole()) {
                return;
            }
        // VERIFICA SE O SISTEMA ESTÁ RODANDO NO CONSOLE PARA EVITAR ERROS





        // DEFINED
            // PROG
                $prog = 0;
                $header = request()->header('Authorization-Admin');

                $tokenPlain = substr($header, 7);
                $tokenModel = PersonalAccessToken::findToken($tokenPlain);
                if ($tokenModel && $tokenModel->tokenable) {
                    $user = $tokenModel->tokenable;
                    if ($user->active) {
                        $prog = 1;
                    }
                }

                if (!App::runningInConsole() || !self::isMigrating()) {
                    if (LUGAR_ADMIN()) {
                        define('PROG', 1);
                    } else {
                        define('PROG', $prog);
                    }

                } else {
                    define('PROG', 0);
                }
            // PROG
        // DEFINED




        // CONFIG
            ini_set('display_errors', PROG);
            ini_set('memory_limit', '-1');

            if (!App::runningInConsole() || !self::isMigrating()) {
                if (isset($_SERVER['HTTP_HOST']) && ($_SERVER['HTTP_HOST'] == 'localhost:4000' || $_SERVER['HTTP_HOST'] == 'localhost:8081')) {
                    ini_set("max_execution_time", 20 * 60);
                } else {
                    ini_set("max_execution_time", 20 * 60);
                }
            } else {
                ini_set("max_execution_time", 30 * 60);
            }
        // CONFIG





        // VERIFY DOMINIO ALLOW ACCESS
            $url_front_end = preg_replace('/^https?:\/\//', '', request()->header('origin'));
            $url_bg_end = request()->server('HTTP_HOST');

            if (!(
				$url_front_end == $url_bg_end OR
				$url_bg_end == 'localhost:4000' OR
                $url_front_end == 'localhost' OR // APP
				(!$url_front_end && stripos(request()->server('HTTP_ACCEPT'), 'text/html,application/xhtml+xml,application/xml;') !== false && request()->server('REQUEST_METHOD') == 'GET') OR
                stripos($_SERVER['REQUEST_URI'], '/api/webhooks/') !== false OR
                stripos($_SERVER['REQUEST_URI'], '/api/crons/') !== false OR
                stripos($_SERVER['REQUEST_URI'], '/api/app/init') !== false OR
				PROG)
			) {
                echo 'No Access!';
                exit();
            }
        // VERIFY DOMINIO ALLOW ACCESS





        // NEW
            $class = '\Root\Providers\NEW__AppProvider';
            if (class_exists($class) && method_exists($class, 'boot')) {
                $class::boot();
            }
        // NEW

    }

    protected static function isMigrating(): bool
    {
        $commands = ['migrate', 'migrate:install', 'migrate:fresh', 'migrate:refresh', 'migrate:reset', 'migrate:rollback', 'migrate:status'];
        $currentCommand = $_SERVER['argv'][1] ?? null;

        return in_array($currentCommand, $commands);
    }

}
