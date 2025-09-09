<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Root\Controllers\CronsController;
use Root\Controllers\CustomersController;
use Root\Controllers\Dashboard\CustomersDashboardController;
use Root\Controllers\Dashboard\HomeDashboardController;
use Root\Controllers\HomeController;
use Root\Controllers\LoginController;
use Root\Middleware\NEW__EventsExternalMiddleware;
use Root\Middleware\NEW__RouterModulesMiddleware;
use Vendor\Controllers\Root\App\__AppController;
use Vendor\Controllers\Root\Dashboard\__ModulesDashboardController;
use Vendor\Mail\__Mail;
use Vendor\Services\__UseFullService;
use Vendor\Services\__ZTextService;
use Vendor\Services\Admin\__FactorySeedersService;
use Vendor\Services\Admin\__MigrateService;

    // ALL
        if (Schema::hasTable('x_settings')) {
            $_GET['info'] = \Vendor\Models\XSettings::get__();
        }
    // ALL





    // ROOT

        // AUTH
            // Route::middleware(['auth:sanctum', 'check.active'])->group(function(){

                // HOME
                        Route::prefix('/home')->group( function() {
                            Route::post('/index', [HomeController::class, "index"]);
                        });
                // HOME
            // });
        // AUTH





        // DEFAULT
            // Route::prefix('/cart')->group( function() { ROUTER__CART(); });
            // Route::prefix('/pay')->group( function() { ROUTER__PAY(); });

            // AUTH
                // Route::middleware(['auth:sanctum', 'check.active'])->group(function(){
                //     Route::prefix('/account')->group( function() { ROUTER__ACCOUNT(); });
                    // Route::prefix('/address')->group( function() { ROUTER__ORDERS(); });
                    // Route::prefix('/orders')->group( function() { ROUTER__ORDERS(); });
                // });
            // AUTH
        // DEFAULT





        // LOGIN
            Route::prefix('/login')->group( function() { ROUTER__LOGIN(LoginController::class); });
            // Route::prefix('/sign_up')->group( function() { ROUTER__SIGN_UP(CustomersController::class); });
        // LOGIN
    // ROOT




















    // DASHBOARD
        Route::prefix('/dashboard')->group( function() {

            Route::middleware(['auth:sanctum', 'check.active'])->group(function(){

                // ALL
                    // PAGES
                        // HOME
                            Route::post('/index', [HomeDashboardController::class, "index"]);
                        // HOME
                    // PAGES





                    // MODULES
                        ROUTER__MODULES('customers', 1001);
                        ROUTER__MODULES('address', 1003);
                    // MODULES





                    // CRONS
                        // Route::prefix('/crons')->group( function() {
                        // });
                    // CRONS





                    // DEFAULT
                        // Route::prefix('/cart')->group( function() { ROUTER__CART(); });
                        // Route::prefix('/pay')->group( function() { ROUTER__PAY(); });

                        // AUTH
                            Route::prefix('/account')->group( function() { ROUTER__ACCOUNT(1); });
                            // Route::prefix('/orders')->group( function() { ROUTER__ORDERS(); });
                        // AUTH
                    // DEFAULT
                // ALL





                // OTHERS
                    // Route::post('/autocomplete', [__UseFullService::class, "autocomplete"]);
                    // Route::post('/ckeditor/upload', [__ZTextService::class, "ckeditor_upload"]);
                // OTHERS
            });





            // LOGIN
                Route::prefix('/login')->group( function() { ROUTER__LOGIN(LoginController::class); });
                Route::prefix('/sign_up')->group( function() { ROUTER__SIGN_UP(CustomersController::class); });
            // LOGIN
        });
    // DASHBOARD




















    // CRONS
        // Route::prefix('/crons')->group( function() {
        // });
    // CRONS




















    // EVENTS EXTERNAL
        // NEW__EventsExternalMiddleware
            Route::post('/export/{type}', [__UseFullService::class, "export"])->middleware(NEW__EventsExternalMiddleware::class);

            Route::prefix('/qrcode')->group( function()
            {
                Route::get('/{text}', [__UseFullService::class, "qrcode"]);
                Route::get('/{text}/{size}', [__UseFullService::class, "qrcode"]);

                // Route::get('/{text}', [__UseFullService::class, "qrcode"])->middleware(NEW__EventsExternalMiddleware::class);
                // Route::get('/{text}/{size}', [__UseFullService::class, "qrcode"])->middleware(NEW__EventsExternalMiddleware::class);
            });
        // NEW__EventsExternalMiddleware

        Route::post('/zipcode', [__UseFullService::class, "zipcode"]);
        Route::post('/city', [__UseFullService::class, "city"]);

        Route::post('/capture', [__UseFullService::class, "capture"]);

        Route::prefix('/download')->group( function()
        {
            Route::get('/{file}', [__UseFullService::class, "download"]);
            Route::get('/{file}/{name}', [__UseFullService::class, "download"]);
        });
    // EVENTS EXTERNAL




















    // WEBHOOKS
        // Route::prefix('/webhooks')->group(function ()
        // {
        // });
    // WEBHOOKS




















    // APP
        Route::prefix('/app')->group( function()
        {
            Route::post('/init', [__AppController::class, "init"]);
        });
    // APP
            




















    // ADMIN
        require_once __DIR__.'/ApiRoutes_Admin.php';
    // ADMIN




















    // TESTE
        Route::get('/teste', function(Request $request)
        {
            echo 'ok';
        });

        Route::get('/info', function(Request $request){ phpinfo(); });

        Route::get('/mail/{mail}', function(string $mail)
        {
            $_GET['MAIL_TESTE'] = 1;
            __Mail::send([], 'simple', [
                'to'=> $mail,
                'from'=> 'teste@gmail.com',
                'subject'=> 'Teste',
                'message'=> 'teste email',
            ]);

            pre(GET_pre_fixed_read());
        });
    // TESTE


















































    // FUNCTIONS

        // MODULES
            function ROUTER__MODULES(string $pg, int $modulo, bool $middleware = false): void
            {
                Route::prefix('/'.$pg.'')->group( function() use($pg, $modulo, $middleware) {
                    Route::post('', ROUTER__MODULES_FUNCTIONS($pg, $modulo, 'index'))->middleware($middleware ? NEW__RouterModulesMiddleware::class : null);
                    Route::post('/index', ROUTER__MODULES_FUNCTIONS($pg, $modulo, 'index'))->middleware($middleware ? NEW__RouterModulesMiddleware::class : null);
                    Route::post('/index/{id}', ROUTER__MODULES_FUNCTIONS($pg, $modulo, 'create_edit'))->middleware($middleware ? NEW__RouterModulesMiddleware::class : null);
                    Route::put('/store', ROUTER__MODULES_FUNCTIONS($pg, $modulo, 'store'))->middleware($middleware ? NEW__RouterModulesMiddleware::class : null);
                    Route::put('/{id}/update', ROUTER__MODULES_FUNCTIONS($pg, $modulo, 'update'))->middleware($middleware ? NEW__RouterModulesMiddleware::class : null);
                    Route::delete('/delete/{id?}', ROUTER__MODULES_FUNCTIONS($pg, $modulo, 'delete'))->middleware($middleware ? NEW__RouterModulesMiddleware::class : null);
                    Route::put('/actions/{type}', ROUTER__MODULES_FUNCTIONS($pg, $modulo, 'actions'))->middleware($middleware ? NEW__RouterModulesMiddleware::class : null);
                });
            };
            function ROUTER__MODULES_FUNCTIONS(string $pg, int $modulo, string $method): \Closure
            {
                return function (Request $request, ...$args) use ($pg, $modulo, $method) {
                    $controller = new __ModulesDashboardController($pg, $modulo);
                    return call_user_func_array([$controller, $method], array_merge([$request], $args));
                };
            }
        // MODULES










        // --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------










        // ACCOUNT
            function ROUTER__ACCOUNT(int $dashboard = 0): void
            {
                if($dashboard){
                    Route::post('/index', [CustomersDashboardController::class, "create_edit"]);
                    Route::put('/update', [CustomersDashboardController::class, "update"]);

                } else {
                    Route::post('/index', [CustomersController::class, "create_edit"]);
                    Route::put('/update', [CustomersController::class, "update"]);
                }
            }
        // ACCOUNT





        // CART
            function ROUTER__CART(): void
            {
                // Route::post('/index', [CartController::class, "index"]);
                // Route::put('/save', [CartController::class, "save"]);
                // Route::put('/qty', [CartController::class, "qty"]);
                // Route::put('/delete', [CartController::class, "delete"]);

                // Route::post('/position/{type}', [CartController::class, "position"]);
                // Route::post('/shipping', [CartController::class, "shipping"]);

                // Route::post('/index/success/{id}', [PayCartController::class, "cart_success"]);
                // Route::post('/index/rejected/{id}', [PayCartController::class, "cart_rejected"]);
            }
        // CART





        // PAY
            function ROUTER__PAY(): void
            {
                // Route::put('/{method}', [PayCartController::class, "cart_pay"]);
                // Route::post('/js', [PayCartController::class, "cart_pay_js"]);
            }
        // PAY





        // ORDERS
            function ROUTER__ORDERS(): void
            {
                // Route::post('/index', [OrdersController::class, "index"]);
                // Route::post('/index/{id}', [OrdersController::class, "index"]);
            }
        // ORDERS





        // LOGIN / SIGN_UP
            function ROUTER__LOGIN(mixed $controller): void
            {
                Route::post('/index', [$controller, "index"]);
                Route::post('/auth', [$controller, "login"]);
                Route::post('/index/logout', [$controller, "index"]);
                Route::post('/logout', [$controller, "index"]);

                Route::post('/forget_password', [$controller, "forget_password"]);
                Route::post('/forget_password/resend_code', [$controller, "forget_password_resend_code"]);
                Route::post('/forget_password/verify_code', [$controller, "forget_password_verify_code"]);
                Route::put('/forget_password/update', [$controller, "forget_password_update"]);
            }
            function ROUTER__SIGN_UP(mixed $controller): void
            {
                Route::post('/index', [$controller, "index"]);
                Route::post('/index/{type}', [$controller, "index"]);
                Route::put('/store', [$controller, "store"]);
            }
        // LOGIN / SIGN_UP










        // --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------










        // CURL
            function ROUTER__CURL(Request $request): void
            {
                ini_set('display_errors', 1);
                $json = json_decode($_POST['json']);

                $headers = [];
                foreach ($json->headers as $key => $value) {
                    $headers[] = $key.': '.$value;
                }

                $method = compare__('"_method\":\"PUT\"', $_POST['json']) ? 'PUT' : 'POST';
                $array = array(
                    CURLOPT_URL => $_POST['url'],
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => $method,
                    CURLOPT_POSTFIELDS => $json->body,
                    CURLOPT_HTTPHEADER => $headers,
                );
                if($method == 'PUT')
                {
                    // pre($array);
                }

                $curl = curl_init();
                curl_setopt_array($curl, $array);
                $response = curl_exec($curl);
                curl_close($curl);
                echo $response;
            }
        // CURL

    // FUNCTIONS




















    // OTHERS
        // CURL
            Route::post('/curl_error', function(Request $request)
            {
                ROUTER__CURL($request);
            });
        // CURL





        // MENU_ADMIN__ORDERBY
            Route::get('/menu_admin__orderby', function(Request $request)
            {
                $jsonPath = __DIR__ . '/../z_Json/menu_admin';
                $result = [];
                
                if (is_dir($jsonPath)) {
                    $files = glob($jsonPath . '/*.json');
                    
                    foreach ($files as $file) {
                        $filename = basename($file, '.json');
                        $content = file_get_contents($file);
                        $json = json_decode($content, true);
                        
                        DB::table('y_menu_admin')
                            ->where('id', $filename)
                            ->update([
                                'type_items' => $json['type_items']??null,
                                'filter' => $json['filter']??null,
                                'orderby' => $json['orderby']??null
                            ]);
                    }
                }
            });
        // MENU_ADMIN__ORDERBY





        // MIGRATE
            Route::get('/migrate', function(Request $request)
            {
                $result = __MigrateService::generateAllMigrations();
                $backup = __MigrateService::backupItems();
                __MigrateService::registerAllMigrationInDatabase();
                
                return response()->json([
                    'status' => 200,
                    'message' => 'Migrations and backups created successfully',
                    'migration_files' => $result['files'],
                    // 'backup_files' => $backup['files'],
                    'total_tables' => $result['total_tables']
                ]);
            });
        // MIGRATE
        
        
        // FACTORY AND SEEDERS
            Route::get('/factories', function(Request $request)
            {
                $result = __FactorySeedersService::generateAll();
                
                return response()->json([
                    'status' => 200,
                    'message' => 'Factories and seeders created successfully',
                    'files' => $result['files'],
                    'total_tables' => $result['total_tables']
                ]);
            });
            
            Route::get('/seeders', function(Request $request)
            {
                $result = __FactorySeedersService::seedDatabase();
                
                return response()->json($result);
            });
            
            Route::get('/seeders/{table}', function(Request $request, string $table)
            {
                $result = __FactorySeedersService::seedTable($table);
                
                return response()->json($result);
            });
        // FACTORY AND SEEDERS





        // ANY
            Route::any('{any}', function(Request $request)
            {
                return response()->json([
                    'message' => 'Route Not Found.',
                ], 404);
            })->where('any', '.*');
        // ANY
    // OTHERS
