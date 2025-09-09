<?php

namespace Vendor\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Vendor\Models\Admin\YMenuAdmin_Admin;

class __ActionsService
{

    // ORDER
        public static function order(Request $request, int $module, array $arr = []): array
        {
            try {
                $arr['val'] = [];
                foreach ($request['order'] as $key => $value) {
                    if ($module) {
                        $menu_admin = YMenuAdmin_Admin::json($module);
                        $_MODEL = new ($menu_admin->table__);
                    } else {
                        $menu_admin = $arr['OBJ']['menu_admin'];
                        $_MODEL = $menu_admin->table__;    
                    }

                    // TYPE
                        if (isset($menu_admin->type_items) && $menu_admin->type_items) {
                            $_MODEL = $_MODEL->where('type', $menu_admin->type_items);
                        }
                    // TYPE

                    try {
                        $_MODEL->find($key)->update([ "order" => $value ]);
                    } catch (\Throwable $th) {
                    }

                    $arr['val'][$key] = $value;
                }
                $arr['status'] = 200;

                DB::commit();

            } catch (\Throwable $th) {
                $arr = errors__($th, $arr);
            }

            return $arr;
        }
    // ORDER










    // ACTIONS
        public static function actions(Request $request, int $module, string $type, string $class, array $arr = []): array
        {
            $arr['type'] = $type;

            try {
                
                // ADMIN
                    if ($module) {
                        $AdminController = new $class();
                        $menu_admin = YMenuAdmin_Admin::json($module);
                        $_MODEL = new ($menu_admin->table__);
                    }
                // ADMIN
                
                // ROOT
                    else {
                        $menu_admin = $arr['OBJ']['menu_admin'];
                        $_MODEL = $menu_admin->table__;        
                    }
                // ROOT

                // FILTER
                    // TYPE
                        if (isset($menu_admin->type_items) && $menu_admin->type_items) {
                            $_MODEL = $_MODEL->where('type', $menu_admin->type_items);
                        }
                    // TYPE
                // FILTER


                $ok = 0;
                $ids = sel__ids($request, $_MODEL);


                // ACTIVE
                    if ($type == 'active') {
                        // ADMIN
                            if (LUGAR_ADMIN()) {
                                if (isset($menu_admin->id) && $AdminController->auth_permissions($request, $menu_admin, 'columns_active')) {
                                    $arr['val'] = self::active($_MODEL, $ids);
                                    if (count($arr['val'])>0) {
                                        $ok = 1;
                                    }
                                }        
                            }
                        // ADMIN

                        // ROOT
                            else {
                                $arr['val'] = self::active($_MODEL, $ids);
                                if (count($arr['val'])>0) {
                                    $ok = 1;
                                }        
                            }
                        // ROOT
                    }
                    else if($type == 'active_1' || $type == 'active_2') {
                        $arr['val'] = self::active($_MODEL, $ids, $type);
                        if (count($arr['val'])>0) {
                            $ok = 1;
                        }
                    }
                // ACTIVE


                // STAR
                    else if($type == 'star_1' || $type == 'star_2' || $type == 'star_3' || $type == 'star_4' || $type == 'star_5') {
                        // ADMIN
                            if (LUGAR_ADMIN()) {
                                if (isset($menu_admin->id) && $AdminController->auth_permissions($request, $menu_admin, $type)) {
                                    $arr['val'] = self::star($_MODEL, $menu_admin, $ids, $type);
                                    if (count($arr['val'])>0) {
                                        $ok = 1;
                                    }
                                }
                            }
                        // ADMIN

                        // ROOT
                            else {
                                $arr['val'] = self::star($_MODEL, $menu_admin, $ids, $type);
                                if (count($arr['val'])>0) {
                                    $ok = 1;
                                }
                            }
                        // ROOT
                    }
                // STAR


                // CLONE
                    else if($type == 'clone') {
                        // ADMIN
                            if (LUGAR_ADMIN()) {
                                if (isset($menu_admin->id) && $AdminController->auth_permissions($request, $menu_admin, 'create')) {
                                    if (isset($menu_admin->id) && $AdminController->auth_permissions($request, $menu_admin, 'clone')) {
                                        $arr['val'] = self::clone($_MODEL, $menu_admin, $ids);
                                        if (count($arr['val'])>0) {
                                            $ok = 1;
                                        }

                                        // YMENUADMIN (MENU_ADMIN)
                                            if ($menu_admin->id == 1) {
                                                foreach ($arr['val'] as $key_1 => $value_1) {
                                                    // JSON
                                                        $old = DIR_F.'/_root/z_Json/menu_admin/'.$value_1->id_old.'.json';
                                                        $new = DIR_F.'/_root/z_Json/menu_admin/'.$value_1->id.'.json';
                                                        copy($old, $new);
                                                    // JSON
                                                }
                                            }
                                        // YMENUADMIN (MENU_ADMIN)
                                    }
                                }
                            }
                        // ADMIN

                        // ROOT
                            else {
                                if (in_array('create', $menu_admin->info??[])) {
                                    if (in_array('clone', $menu_admin->info??[])) {
                                        $arr['val'] = self::clone($_MODEL, $menu_admin, $ids);
                                        if (count($arr['val'])>0) {
                                            $ok = 1;
                                        }
                                    }
                                }        
                            }
                        // ROOT
                    }
                // CLONE


                if (!$ok) {
                    $arr['errors'][] = 'Item não encontrado ou ação não permitida!';
                }

                $arr['status'] = 200;
                DB::commit();

            } catch (\Throwable $th) {
                $arr = errors__($th, $arr);
            }

            return $arr;
        }
    // ACTIONS










    // ACTIVE
        public static function active(object $_MODEL, array $ids, string $type = 'active'): array
        {
            $return = [];
            foreach ($ids as $key => $id) {
                if ($id) {
                    $_MODEL__ = clone $_MODEL;
                    $query = $_MODEL__->find($id);
                    $val = $query->$type ? 0 : 1;

                    try {
                        $query->update([ $type => $val ]);
                    } catch (\Throwable $th) {
                    }

                    $return[$id] = $val;
                }
            }
            return $return;
        }
    // ACTIVE










    // STAR
        public static function star(object $_MODEL, object $menu_admin, array $ids, string $type): array
        {
            $return = [];
            foreach ($ids as $key => $id) {

                $_MODEL__ = clone $_MODEL;
                $response = $_MODEL__->select(['id', $type])->find($id);
                $val = (!$response->{$type}) ?? 0;

                $_MODEL->find_id($id)->update([
                    $type => $val
                ]);

                $return[$id] = $val;
            }

            return $return;
        }
    // STAR










    // CLONE
        public static function clone(object $_MODEL, object $menu_admin, array $ids): array
        {
            $return = [];
            foreach ($ids as $key => $id) {
                if ($id) {
                    $_MODEL__ = clone $_MODEL;
                    $_GET['no_fields'] = 1;
                    $query = $_MODEL__->find($id);
                    unset($_GET['no_fields']);
                    $new_query = $query->replicate();
                    if ($new_query->save()) {
                        $new_query->id_old = $id;
                        $return[] = $new_query;

                        // Z_TEXT
                            __ZTextService::clone($menu_admin, $id, $new_query->id);
                        // Z_TEXT
                    }
                }
            }

            return $return;
        }
    // CLONE










    // ITEMS_PAGE
        public static function items_page_get(Request $requst, object $menu_admin): int
        {
            $return = ADMIN__DATATABLE__PER_PAGE;

            if (in_array('items_page', $menu_admin->info??[])) {
                if (COOKIES('ITEMS_PAGE')) {
                    if (isset($requst['init__']) && (int)COOKIES('ITEMS_PAGE') > 0 && (int)COOKIES('ITEMS_PAGE') <= 100) {
                        $return = (int)COOKIES('ITEMS_PAGE');

                    } else if(!isset($requst['init__']) && (int)COOKIES('ITEMS_PAGE') > 0) {
                        $return = (int)COOKIES('ITEMS_PAGE');

                    } else {
                        COOKIES_DELETE('ITEMS_PAGE');
                    }
                }
            }
            
            return $return;
        }

        public static function items_page_update(Request $request, int $module): array|object
        {
            $arr = [];

            DB::beginTransaction();
            try {
                $menu_admin = YMenuAdmin_Admin::find($module)->menu_admin_items_page();
                $admin_columns = $menu_admin->where('users', Auth::user()->id)->orderBy('id')->first('id');
                if ($admin_columns) {
                    $menu_admin->update([
                        'users' => Auth::user()->id,
                        'value' => $request['items_page']
                    ]);
                } else {
                    $menu_admin->create([
                        'users' => Auth::user()->id,
                        'value' => $request['items_page']
                    ]);
                }

                $arr['OBJ']['items_page'] = $request['items_page'];

                $arr['status'] = 200;
                DB::commit();

            } catch (\Throwable $th) {
                $arr = errors__($th, $arr);
            }

            return $arr;
        }
    // ITEMS_PAGE

}