<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Root\Controllers\Admin\AdminController_Admin;
use Vendor\Controllers\Admin\__LoginController_Admin;
use Vendor\Services\__UseFullService;
use Vendor\Services\__ZTextService;
use Vendor\Controllers\Admin\__AdminController_Admin;
use Vendor\Controllers\Admin\__MenuAdminController_Admin;

    Route::prefix('/admin')->group( function() {

        // TESTE
            Route::get('/teste', function(Request $request){
                echo 'OK';
            });
        // TESTE










        // LOGIN
            Route::prefix('/login')->group( function() {
                Route::post('/index', [__LoginController_Admin::class, "index"]);
                Route::post('/logout', [__LoginController_Admin::class, "index"]);
                Route::post('/auth', [__LoginController_Admin::class, "login"]);
                Route::post('/forget_password', [__LoginController_Admin::class, "forget_password"]);
                Route::post('/forget_password/resend_code', [__LoginController_Admin::class, "forget_password_resend_code"]);
                Route::post('/forget_password/verify_code', [__LoginController_Admin::class, "forget_password_verify_code"]);
                Route::put('/forget_password/update', [__LoginController_Admin::class, "forget_password_update"]);
            });
        // LOGIN










        // ADMIN (AUTH)
            Route::middleware(['auth:sanctum', 'check.active_admin'])->group(function(){
                // LOGOUT
                    Route::post('/login/index/logout', [__LoginController_Admin::class, "logout"]);
                // LOGOUT              





                // MODULES
                    Route::post('/index', [__AdminController_Admin::class, "home"]);
                    // Route::post('/home/index', [__AdminController_Admin::class, "home"]);

                    Route::prefix('/modules')->group( function() {
                        Route::post('/{module}', [__AdminController_Admin::class, "index"]);
                        Route::post('/index/{module}', [__AdminController_Admin::class, "index"]);
                        Route::post('/index/{module}/{id}', [__AdminController_Admin::class, "create_edit"]);

                        Route::prefix('/{module}')->group( function() {
                            Route::put('/store', [__AdminController_Admin::class, "store"]);
                            Route::put('/{id}/update', [__AdminController_Admin::class, "update"]);
                            Route::delete('/delete/{id?}', [__AdminController_Admin::class, "delete"]);

                            Route::put('/actions/{type}', [__AdminController_Admin::class, "actions"]);
                        });
                    });
                // MODULES





                // MENU_ADMIN
                    Route::prefix('/menu_admin')->group( function() {
                        Route::post('', [__MenuAdminController_Admin::class, "index"]);
                        Route::post('/index', [__MenuAdminController_Admin::class, "index"]);
                        Route::post('/index/{id}', [__MenuAdminController_Admin::class, "create_edit"]);
                        // Route::put('/index/{id}/resouces/{type}/{position}', [__MenuAdminController_Admin::class, "resouces"]);
                        Route::put('/store', [__MenuAdminController_Admin::class, "store"]);
                        Route::put('/{id}/update', [__MenuAdminController_Admin::class, "update"]);
                        Route::delete('/delete/{id?}', [__MenuAdminController_Admin::class, "delete"]);

                        Route::put('/index/actions/{type}', [__MenuAdminController_Admin::class, "actions"]);
                        Route::put('/actions/{type}', [__MenuAdminController_Admin::class, "actions"]);

                        Route::put('/column_update', [__MenuAdminController_Admin::class, "column_update"]);
                        Route::put('/column_reorder', [__MenuAdminController_Admin::class, "column_reorder"]);
                        
                        Route::post('/indexes_create', [__MenuAdminController_Admin::class, "indexes_create"]);
                        Route::put('/indexes_rename', [__MenuAdminController_Admin::class, "indexes_rename"]);
                        Route::delete('/indexes_delete', [__MenuAdminController_Admin::class, "indexes_delete"]);
                        
                        Route::put('/foreign_key_rename', [__MenuAdminController_Admin::class, "foreign_key_rename"]);
                        Route::put('/foreign_key_update', [__MenuAdminController_Admin::class, "foreign_key_update"]);
                        Route::post('/foreign_key_create', [__MenuAdminController_Admin::class, "foreign_key_create"]);
                        Route::delete('/foreign_key_delete', [__MenuAdminController_Admin::class, "foreign_key_delete"]);
                    });
                // MENU_ADMIN





                // ADMIN OTHERS
                    // ORDERS
                        Route::put('/orders_actions/{type}', [__UseFullService::class, "orders_actions"]);
                        Route::delete('/orders_actions/{type}', [__UseFullService::class, "orders_actions"]);
                    // ORDERS

                    Route::post('/google_maps/search_address', [__UseFullService::class, "google_maps_search_address"]);

                    Route::post('/autocomplete', [__UseFullService::class, "autocomplete"]);

                    Route::post('/city', [__UseFullService::class, "city"]);
                    Route::post('/zipcode', [__UseFullService::class, "zipcode"]);

                    Route::post('/ckeditor/upload', [__ZTextService::class, "ckeditor_upload"]);
                // ADMIN OTHERS

            });
        // ADMIN (AUTH)
    });
