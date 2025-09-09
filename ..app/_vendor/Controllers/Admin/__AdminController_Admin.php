<?php

namespace Vendor\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Vendor\Models\Admin\YMenuAdmin_Admin;
use Vendor\Models\XSettings;
use Vendor\Resources\__Resource;
use Vendor\Services\__ImageService;
use Vendor\Services\__ActionsService;
use Vendor\Services\Admin\__MigrateService;

class __AdminController_Admin
{

    // INDEX
        public static function index(Request $request, int $module, int $type = 0, bool $dashboard = false): JsonResponse | array
        {
            $arr = [];
            $_GET['__TABLE__'] = 1;
            $menu_admin = YMenuAdmin_Admin::json($module);

            if (isset($menu_admin->id) && self::auth_permissions($request, $menu_admin, 'list')) {
                $menu_admin = self::treatment($request, $menu_admin, 'table');
                $arr['OBJ']['menu_admin'] = $menu_admin;

                // X_SETTINGS
                    if ($menu_admin->type == 1 && !$dashboard) {
                        return self::x_settings($request, $module, $menu_admin);
                    }
                // X_SETTINGS


                // MODULES
                    else if($menu_admin->type == $type) {
                        $_MODEL = new ($menu_admin->table__);

                        $z_items_page = __ActionsService::items_page_get($request, $menu_admin);
                        $arr['OBJ']['COLUMNS'] = self::columns($request, $menu_admin, 'table');
                        $columns_all = self::columns_extra($arr['OBJ']['COLUMNS'], $menu_admin);

                        $arr = __Resource::query_custom($request, $arr, $_MODEL, $columns_all, $menu_admin, $z_items_page, 'table');
                        if (isset($arr['OBJ']['QUERY_CUSTOM'])) {
                            $arr['OBJ']['DATATABLE'] = $arr['OBJ']['QUERY_CUSTOM'];

                        } else {
                            $query = $_MODEL;

                            // QUERY_CUSTOM__FILTERS
                                if (isset($arr['OBJ']['QUERY_CUSTOM__FILTERS'])) {
                                    $query = $arr['OBJ']['QUERY_CUSTOM__FILTERS'];
                                }
                            // QUERY_CUSTOM__FILTERS

                            // RESET COOKIES
                                if (isset($request['init__'])) {
                                    foreach (COOKIES_LIST() as $key => $value) {
                                        if (compare__ini('SEARCH__', $value['name'])) {
                                            COOKIES_DELETE($value['name']);
                                        }
                                    }
                                    // COOKIES deletados, mas so para a proxima requisicao
                                    // para este requisicao, oq faz nao filtrar é o isset($request['init__']) q comanda
                                }
                            // RESET COOKIES

                            // PAGE
                                if (COOKIES('SEARCH__page') && !isset($request['init__'])) {
                                    $request['page'] = COOKIES('SEARCH__page');
                                }
                            // PAGE

                            $query = $query->select__($columns_all)->search($request, $menu_admin, $columns_all)->order($request, $menu_admin);
                            $arr['OBJ']['DATATABLE'] = $query->paginate($z_items_page)->toArray();
                        }

                        $arr['OBJ']['DATATABLE']['data'] = __Resource::fields_datatable($request, $arr, $arr['OBJ']['DATATABLE']['data'], $menu_admin);
                        $arr['OBJ']['DATATABLE']['z_items_page'] = $z_items_page;
                        $arr['OBJ']['DATATABLE']['z_table_order'] = $_GET['__Z_TABLE_ORDER__'];

                        $arr = __Resource::html_table($request, $arr, $_MODEL, $menu_admin);
                    }
                // MODULES


                // TAGS
                    $arr['OBJ']['TAGS'] = [];
                // TAGS


                // MIGRATE BACKUP DAILY
                    __MigrateService::backupDaily();
                // MIGRATE BACKUP DAILY
            }

            $arr['status'] = 200;
            if ($dashboard) {
                return $arr;
            } else {
                return json_encode__($arr, $request);
            }
        }
    // INDEX










    // CREATE_EDIT
        public static function create_edit(Request $request, int $module, int $id = 0, int $type = 0, bool $dashboard = false): JsonResponse | array
        {
            $arr = [];
            $_GET['__EDIT__'] = 1;
            $menu_admin = YMenuAdmin_Admin::json($module);
            if (isset($menu_admin->id) && self::auth_permissions($request, $menu_admin, 'list') && $menu_admin->type == $type) {
                $menu_admin = self::treatment($request, $menu_admin, 'create_edit', $id);
                $arr['OBJ']['menu_admin'] = self::align($menu_admin);

                // MODULES
                    $_MODEL = new ($menu_admin->table__);

                    $arr['OBJ']['COLUMNS'] = self::columns($request, $menu_admin, 'create_edit', $id);

                    if ($id) {
                        $columns_all = self::columns_extra($arr['OBJ']['COLUMNS'], $menu_admin);

                        $arr = __Resource::query_custom($request, $arr, $_MODEL, $columns_all, $menu_admin, 0, 'create_edit', $id);
                        if (isset($arr['OBJ']['QUERY_CUSTOM'])) {
                            $arr['OBJ']['VALUE'] = $arr['OBJ']['QUERY_CUSTOM'];

                        } else {
                            $arr['OBJ']['VALUE'] = $_MODEL->select__($columns_all)->search($request, $menu_admin)->find($id);
                        }


                        if ($arr['OBJ']['VALUE']) {
                            $arr['OBJ']['VALUE'] = $arr['OBJ']['VALUE']->editor_all()->toArray();
                        } else {
                            $arr['OBJ']['VALUE'] = [];
                        }

                    } else {
                        $arr['OBJ']['VALUE'] = [];
                    }

                    $arr = __Resource::fields_create_edit($request, $arr, $id, $menu_admin);
                    $arr = __Resource::html_create_edit($request, $arr, $_MODEL, $menu_admin);
                // MODULES


                // QUERYS
                    $arr['OBJ']['QUERY'] = self::querys($request, $arr, $menu_admin, null, 'create_update');
                // QUERYS
            }

            $arr['status'] = 200;
            if ($dashboard) {
                return $arr;
            } else {
                return json_encode__($arr, $request);
            }
        }
    // CREATE_EDIT










    // STORE
        public static function store(Request $request, int $module, int $type = 0, bool $dashboard = false): JsonResponse | array
        {
            $arr = [];
            $_GET['__STORE__'] = 1;
            $menu_admin = YMenuAdmin_Admin::json($module);
            if (isset($menu_admin->id) && self::auth_permissions($request, $menu_admin, 'edit')) {

                // X_SETTINGS (TYPE == 1)
                    if ($menu_admin->type == 1 && !$dashboard) {
                        return self::x_settings__update($request, $module, $menu_admin);
                    }
                // X_SETTINGS (TYPE == 1)


                // MODULES
                    else if($menu_admin->type == $type) {
                        
                        DB::beginTransaction();
                        try {
                            $_MODEL = new ($menu_admin->table__);

                            __Resource::save('store_pre', $request, $arr, $_MODEL, $menu_admin);
                            
                            $request__ = __Resource::fields_store_update($request, $menu_admin);
                            $arr['response'] = $response = $_MODEL->create($request__, $menu_admin);
                            
                            __Resource::save('store_pos', $request, $arr, $_MODEL, $menu_admin, $response->id);
                            
                            $arr['status'] = 200;
                            DB::commit();
                            
                        } catch (\Throwable $th) {
                            DB::rollBack();
                            $arr = errors__($th, $arr);
                        }
                    }
                // MODULES
            }

            if ($dashboard) {
                return $arr;
            } else {
                return json_encode__($arr, $request);
            }
        }
    // STORE










    // UPDATE
        public static function update(Request $request, int $module, int $id, int $type = 0, bool $dashboard = false): JsonResponse | array
        {
            $arr = [];
            $_GET['__UPDATE__'] = 1;
            $menu_admin = YMenuAdmin_Admin::json($module);
            if (isset($menu_admin->id) && self::auth_permissions($request, $menu_admin, 'edit') && $menu_admin->type == $type) {
                // x_settings__update -> UPDATE OF X_SETTINGS IN STORE

                // MODULES
                    DB::beginTransaction();
                    try {
                        $_MODEL = new ($menu_admin->table__);

                        __Resource::save('update_pre', $request, $arr, $_MODEL, $menu_admin, $id);

                        $request__ = __Resource::fields_store_update($request, $menu_admin);
                        $_MODEL->search($request, $menu_admin)->find_id($id)->update($request__, [], $menu_admin);

                        __Resource::save('update_pos', $request, $arr, $_MODEL, $menu_admin, $id);

                        $arr['status'] = 200;
                        DB::commit();

                    } catch (\Throwable $th) {
                        DB::rollBack();
                        $arr = errors__($th, $arr);
                    }
                // MODULES
            }

            if ($dashboard) {
                return $arr;
            } else {
                return json_encode__($arr, $request);
            }

        }
    // UPDATE










    // DELETE
        public static function delete(Request $request, int $module, int $id = 0, int $type = 0, bool $dashboard = false): JsonResponse | array
        {
            $arr = [];
            $_GET['__DELETE__'] = 1;
            $menu_admin = YMenuAdmin_Admin::json($module);
            if (isset($menu_admin->id) && self::auth_permissions($request, $menu_admin, 'delete') && $menu_admin->type == $type) {

                // MODULES
                    DB::beginTransaction();
                    try {
                        $_MODEL = new ($menu_admin->table__);

                        __Resource::save('delete_pre', $request, $arr, $_MODEL, $menu_admin, $id);

                        // EXCEPTIONS
                            // ORDERS_STATUS
                                if ($menu_admin->table == 'orders_status') {
                                    if ($id && $id <= 100) {
                                        $_GET['errors'][] = 'Você não pode excluir status padrão!';

                                    } else if($request['sel_all_all']) {
                                        $_GET['errors'][] = 'Você não pode excluir alguns itens!';

                                    } else {
                                        foreach ($request['sel'] as $key => $value) {
                                            if ($value <= 100) {
                                                $_GET['errors'][] = 'Você não pode excluir status padrão!';
                                            }
                                        }
                                    }
                                }
                            // ORDERS_STATUS


                            // USER
                                if ($menu_admin->table == 'users') {
                                    if ($id && ($id == 1 || $id == 2)) {
                                        $_GET['errors'][] = 'Você não pode excluir esse usuário admin!';

                                    } else if($request['sel_all_all']) {
                                        $_GET['errors'][] = 'Você não pode excluir o usuário admin!';

                                    } else {
                                        foreach ($request['sel'] as $key => $value) {
                                            if ($value == 1 || $value == 2) {
                                                $_GET['errors'][] = 'Você não pode excluir o usuário admin!';
                                            }
                                        }
                                    }
                                }
                            // USER
                        // EXCEPTIONS


                        if (!isset($_GET['errors'])) {
                            // DELETE
                                if ($id) {
                                    $delete = $_MODEL->search($request, $menu_admin)->where('id', $id)->delete();
                                    if (!$delete) {
                                        $arr['errors'][] = 'Item não encontrado!';
                                    }

                                } else if($request['sel_all_all']) {
                                    $delete = $_MODEL->search($request, $menu_admin)->delete();
                                    if (!$delete) {
                                        $arr['errors'][] = 'Item não encontrado!';
                                    }
                
                                } else {
                                    $ids = [];
                                    foreach ($request['sel'] as $key => $value) {
                                        if ($value) {
                                            $ids[] = $key;
                                        }
                                    }
                                    if ($ids) {
                                        $_MODEL->search($request, $menu_admin)->whereIn('id', $ids)->delete();
                                    }
                                }
                            // DELETE
                        }

                        __Resource::save('delete_pos', $request, $arr, $_MODEL, $menu_admin, $id);

                        $arr['status'] = 200;
                        DB::commit();

                    } catch (\Throwable $th) {
                        DB::rollBack();
                        $arr = errors__($th, $arr);
                    }
                // MODULES
            }

            if ($dashboard) {
                return $arr;
            } else {
                return json_encode__($arr, $request);
            }
        }
    // DELETE










    // X_SETTINGS
        public static function x_settings(Request $request, int $module, object $menu_admin): JsonResponse
        {
            $arr = [];

            $arr['OBJ']['menu_admin'] = self::align($menu_admin);

            // COLUMNS
                $columns = [];
                foreach ($menu_admin->input as $key => $value) {
                    if (isset($value->name) && isset($value->check) && ($value->check === true || $value->check === 'true')) {
                        $columns[] = $value->name;
                    }
                }
            // COLUMNS


            // MODEL
                $_MODEL = new ($menu_admin->table__);
                $arr['OBJ']['VALUE'] = $_MODEL::get__($columns);
            // MODEL


            // TREATMENT
                foreach ($arr['OBJ']['VALUE'] as $key => $value) {

                    // IMAGE
                        if ($key == 'image' || compare__('image_', $key) && is_json($value)) {
                            $item = (object)[];

                            // COPY -> __Resource
                                $json_decode = json_decode($value, true);
                                if (is_array($json_decode)) {
                                    $tempArray = [];
                                    foreach ($json_decode as $key_1 => $value_1) {
                                        if ( (isset($value_1['file']) && $value_1['file'] && file_exists(DIR_P.PHOTOS.'/'.$value_1['file'])) || (isset($value_1['url']) && $value_1['url']) ) {

                                            // MAIN
                                                if ($key_1 == 0) {
                                                    $item->$key = !empty($value_1['url']) ? $value_1['url'] : __ImageService::img($value_1['file'], (array)$arr['OBJ']['VALUE'], $key);
                                                    $item->{$key.'__'} = !empty($value_1['url']) ? $value_1['url'] : DIR.PHOTOS.'/'.$value_1['file'];
                                                    // $item->{$key.'__size'} = $value_1['size'];
                                                }
                                            // MAIN

                                            // ARRAY
                                                $tempArray[] = (object)[
                                                    'image' => !empty($value_1['url']) ? $value_1['url'] : __ImageService::img($value_1['file'], (array)$arr['OBJ']['VALUE'], $key),
                                                    'image__' => !empty($value_1['url']) ? $value_1['url'] : DIR.PHOTOS.'/'.$value_1['file'],
                                                    'url' => $value_1['url'] ?? '',
                                                    'file' => $value_1['file'] ?? '',
                                                    'size' => $value_1['size'] ?? '',
                                                    'type' => $value_1['type'] ?? '',
                                                    'name' => $value_1['name'] ?? '',
                                                ];
                                            // ARRAY
                                        }
                                    }
                                    $item->{$key.'__array'} = $tempArray;
                                }
                            // COPY -> __Resource

                            foreach ($item as $key_1 => $value_1) {
                                $arr['OBJ']['VALUE']->$key_1 = $value_1;
                            }
                        }
                    // IMAGE

                }
            // TREATMENT

            $arr = __Resource::html_create_edit($request, $arr, $_MODEL, $menu_admin);

            $arr['status'] = 200;
            return json_encode__($arr, $request);
        }

        public static function x_settings__update(Request $request, int $module, object $menu_admin): JsonResponse
        {
            $arr = [];

            $arr['OBJ']['menu_admin'] = self::align($menu_admin);

            // MODEL
                $request__ = $request->all();
                $_MODEL = new ($menu_admin->table__);

                // COLUMNS
                    $columns = [];
                    foreach ($menu_admin->input as $key => $value) {
                        if (isset($value->name) && isset($value->check) && ($value->check === true || $value->check === 'true')) {
                            $columns[] = $value;
                        }
                    }
                // COLUMNS


                // VALIDATE / TREATMENT
                    foreach ($columns as $key => $value) {
                        // REQUIRED
                            // REQUIRED
                                if (isset($value->tags) && compare__('required', $value->tags)) {
                                    if (!(isset($request__[$value->name]) && $request__[$value->name]) && $request__[$value->name] !== "0") {
                                        $_GET['errors'][] = 'Preencha o '.$value->label.' Corretamente!';
                                    }
                                }
                            // REQUIRED
                        // REQUIRED


                        // TREATMENT
                            // FIELDS
                                if (isset($request[$value->name])) {
                                    // DATA
                                        if ($value->type == 'date') {
                                            $request__[$value->name] = date__($request[$value->name], 'Y-m-d');
                                        }
                                        if ($value->type == 'datetime-local') {
                                            $request__[$value->name] = date__($request[$value->name], 'Y-m-d H:i:s');
                                        }   
                                    // DATA
                
                
                                    // PRICE
                                        if ($value->type == 'price') {
                                            $request__[$value->name] = price_($request[$value->name]);
                                        }
                                    // PRICE
                
                
                                    // INT
                                        if ($value->type == 'number') {
                                            $request__[$value->name] = (int)$request[$value->name];
                                        }
                                    // INT
                
                
                                    // CHECK
                                        if ($value->type == 'checkbox') {
                                            $request__[$value->name] = json_encode($request[$value->name]);
                                        }
                                    // CHECK

                
                                    // NULL
                                        if ( !(isset($request[$value->name]) && $request[$value->name] !== null && $request[$value->name] != 'null') ) {
                                            if ($value->type == 'categories' || $value->type == 'subcategories' || $value->type == 'number' || $value->type == 'select') {
                                                $request__[$value->name] = 0;
                                            } else if($value->type == 'price') {
                                                $request__[$value->name] = 0;
                                            } else if($value->type == 'date') {
                                                $request[$value->name] = null;
                                            } else if($value->type == 'datetime-local') {
                                                $request[$value->name] = null;
                                            } else if($value->type == 'file') {
                                            } else if($value->type == 'checkbox') {
                                            } else if($value->type == 'radio') {
                                            } else if($value->type == 'json_fields') {
                                                $request[$value->name] = null;
                                            } else {
                                                $request__[$value->name] = '';
                                            }
                                        }
                                    // NULL
                                }
                            // FIELDS


                            // IMAGE / FILE
                                if ($value->type == 'image' || $value->type == 'file' || $value->name == 'image' || compare__('image_', $value->name)) {
                                    $request__[$value->name] = [ 'request' => $request, 'type' => $value->type ]; // -> $value['request']->hasFile($key);
                                }
                            // IMAGE / FILE
                        // TREATMENT
                    }
                // VALIDATE / TREATMENT


                // SAVE
                    foreach ($columns as $key => $value) {
                        $query = XSettings::where('fields', $value->name)->first();

                        if (!(isset($query->id) && $query->id)) {
                            $query = XSettings::create([
                                'fields' => $value->name,
                                'value' => '',
                            ]);
                        }

                        // FIELDS
                            $return = '';

                            // IMAGE
                                if (isset($request__[$value->name]['request'])) {
                                    $array = __Resource::save_file($request__, clone $_MODEL, $query, $value->name, $menu_admin);
                                    if ($array) {
                                        $return = json_encode($array);
                                    }
                                }
                            // IMAGE

                            // ELSE
                                else {
                                    $return = $request__[$value->name];
                                }
                            // ELSE
                        // FIELDS


                        // UPDATE
                            if (isset($query->id) && $query->id) {
                                // IMAGE_SHARING
                                    if ($value->name == 'image_sharing') {
                                        $array = json_decode($return);
                                        if (isset($array[0]->file) && $array[0]->file) {
                                            $getimagesize = getimagesize(DIR_P.PHOTOS.'/'.$array[0]->file);

                                            $max = 200;
                                            if ($getimagesize[0] > $max || $getimagesize[1] > $max) {
                                                $_GET['errors'][] = 'A Foto Compartilhamento contém '.$getimagesize[0].'px de largura e '.$getimagesize[1].'px de altura, deve ser menor que '.$max.'px de largura e de altura!';

                                            } else {
                                                XSettings::where('fields', 'image_sharing_mime')->first()->update(['value' => $getimagesize['mime']]);
                                                XSettings::where('fields', 'image_sharing_width')->first()->update(['value' => $getimagesize[0]]);
                                                XSettings::where('fields', 'image_sharing_height')->first()->update(['value' => $getimagesize[1]]);
                                            }
                                        }
                                    }
                                // IMAGE_SHARING


                                // SAVE
                                    if (!isset($_GET['errors'])) {
                                        XSettings::find_id($query->id)->update([
                                            'value' => $return
                                        ]);
                                    }
                                // SAVE
                            }
                        // UPDATE
                    }
                // SAVE
            // MODEL

            $arr['status'] = 200;
            return json_encode__($arr, $request);
        }
    // X_SETTINGS



















    // ACTIONS
        public static function actions(Request $request, int $module, string $type): JsonResponse
        {
            $arr = [];
            if ($type == 'order') {
                $arr = __ActionsService::order($request, $module);

            } else if($type == 'items_page_update') {
                $arr = __ActionsService::items_page_update($request, $module);

            } else {
                $arr = __ActionsService::actions($request, $module, $type, self::class);
            }

            return json_encode__($arr, $request);
        }
    // ACTIONS




















    // AUTH
        // public static function auth(Request $request): array
        // {
        //     $return = [];
        //     $return['permissions'] = explode('-', $request->user()->currentAccessToken()->tokenable->permissions);
        //     $return['permissions_all'] = $request->user()->currentAccessToken()->tokenable->permissions_all;
        //     return $return;
        // }
        public static function auth_permissions(Request $request, object $menu_admin, string $action): bool
        {
            $return = false;

            // VERIFY PERMISSIONS
                // ADMIN
                    if (LUGAR_ADMIN()) {
                        // ADMIN DEFAULT
                            if ($request->user()->id == 1 || $request->user()->id == 2) {
                                $return = true;
                            }
                        // ADMIN DEFAULT


                        // PERMISSIONS_ALL
                            if ($request->user()->currentAccessToken()->tokenable->permissions_all == 1) {
                                $return = true;
                            }
                        // PERMISSIONS_ALL


                        // PERMISSIONS
                            if ($request->user()->currentAccessToken()->tokenable->permissions) {
                                $permissions = sql_json_list(json_decode($request->user()->currentAccessToken()->tokenable->permissions, 1));
                                foreach ($permissions as $key => $value) {
                                    if (in_array($menu_admin->id, $permissions??[])) {
                                        $return = true;
                                    }
                                }
                            }
                        // PERMISSIONS

                    }
                // ADMIN
                    

                // DASHBOARD
                    else {
                        $return = PERMISSIONS($request);
                    }
                // DASHBOARD
            // VERIFY PERMISSIONS


            // VERIFY ACTIONS
                // ADD ACTIONS TO X_SETTINGS
                    if ($menu_admin->table == 'x_settings') {
                        if ($action == 'create') {
                            $action = 'edit';
                        }
                    }
                // ADD ACTIONS TO X_SETTINGS


                // VERIFY ACTIONS (list always passes / $action in $menu_admin->info)
                    if ( !($action == 'list' || in_array($action, $menu_admin->info??[])) ) {
                        $return = false;
                    }
                // VERIFY ACTIONS


                // HIDE
                    if (!$menu_admin->active && !in_array('hide', $menu_admin->info??[])) {
                        $return = false;
                    }
                // HIDE
            // VERIFY ACTIONS
   
            return $return;
        }
    // AUTH










    // ALIGN
        public static function align(object|array $array): object|array
        {
            $return = clone $array;

            $return->input = [ 'left' => [], 'right' => [] ];
            foreach ($array->input as $key => $value) {
                if (isset($value->name) || (isset($value->type) && $value->type == 'info')) {
                    if (isset($value->check) && ($value->check === true || $value->check === 'true') && $value->type != 'column') {
                        $value->align = $value->align ?? 'left';
                        $return->input[$value->align][] = $value;
                    }
                }
            }

            return $return;
        }
    // ALIGN










    // COLUMNS
        public static function columns(Request $request, object|array $menu_admin, string $type, int $id = 0): array
        {
            $return = [];

            // MENU_ADMIN
                if ($menu_admin->id == 1) {
                    $return = [
                        (object)[
                            'type' => 'sel',
                            'name' => 'sel',
                            'table_align' => 'tac',

                        ],
                        (object)[
                            'type' => 'text',
                            'label' => 'Nome',
                            'name' => 'name',
                            'table_align' => 'tal',
                        ],
                        (object)[
                            'type' => 'ckeck',
                            'label' => 'Ativo',
                            'name' => 'active',
                            'table_align' => 'tac',
                        ],
                        (object)[
                            'type' => 'text',
                            'label' => 'Id',
                            'name' => 'id',
                            'table_align' => 'tac',
                        ],
                        (object)[
                            'type' => 'text',
                            'label' => 'Icone',
                            'name' => 'icon',
                            'table_align' => 'tac',
                        ],
                        (object)[
                            'type' => 'order',
                            'label' => 'Ordem',
                            'name' => 'order',
                            'table_align' => 'tac',
                        ],
                        (object)[
                            'type' => 'categories',
                            'label' => 'Categorias',
                            'name' => 'categories',
                            'table_align' => 'tac',
                        ],
                    ];
                }
            // MENU_ADMIN


            // ELSE
                else {
                    $return[0] = (object)["name" => "sel", "type" => "sel", "check" => true, "table_align" => "tac"];

                    // GET COLUMNS
                        foreach ($menu_admin as $key => $value) {
                            // ACTIVE
                                if ($key == 'columns_active_n' && in_array('columns_active', $menu_admin->info??[])) {
                                    $return[$value] = (object)["label" => "Ativo", "name" => "active", "type" => "ckeck", "check" => true, "table_align" => "tac"];
                                }
                            // ACTIVE

                            // ORDER
                                if ($key == 'columns_order_n' && in_array('columns_order', $menu_admin->info??[])) {
                                    $return[$value] = (object)["label" => "Ordem", "name" => "order", "type" => "order", "check" => true, "table_align" => "tac"];
                                }
                            // ORDER
                        }


                        // STAR
                            if (in_array('star_1', $menu_admin->info??[]) || in_array('star_2', $menu_admin->info??[]) || in_array('star_3', $menu_admin->info??[]) || in_array('star_4', $menu_admin->info??[]) || in_array('star_5', $menu_admin->info??[])) {
                                $return['z_star'] = (object)["label" => "Ações", "name" => "star", "type" => "actions", "check" => true, "table_align" => "tac", "info" => star_info($menu_admin)];

                                foreach ($menu_admin->info as $key_1 => $value_1) {
                                    if (compare__('star_', $value_1)) {
                                        $return[$value_1] = (object)["label" => "Star", "name" => $value_1, "type" => "no", "check" => true, "table_align" => "tac"];
                                    }
                                }
                            }
                        // STAR


                        // INPUT
                            foreach ($menu_admin->input??[] as $key => $value) {
                                if (isset($value->name) && $value->name  && self::columns__verify_no($value)) {
                                    if (isset($value->check) && ($value->check === true || $value->check === 'true')) {
                                        if ($value->type != 'search') {

                                            // COLUNNS_CHECK
                                                if ($type == 'create_edit') {
                                                    $value->order = $value->name;
                                                }
                                            // COLUNNS_CHECK

                                            if (isset($value->order) && $value->order) {

                                                // TABLE_ALIGN
                                                    $value->table_align = 'tac';
                                                    if ($value->name == 'id' || $value->name == 'name') {
                                                        $value->table_align = 'tal';
                                                    }
                                                    if (isset($value->order) && !compare__('->', $value->order)) {
                                                        $ex = explode('-', $value->order);
                                                        if (isset($ex[1]) && $ex[1]) {
                                                            if ($ex[1] == 'l' || $ex[1] == 'L') {
                                                                $value->table_align = 'tal';
                                                            }
                                                            if ($ex[1] == 'c' || $ex[1] == 'C') {
                                                                $value->table_align = 'tac';
                                                            }
                                                            if ($ex[1] == 'r' || $ex[1] == 'R') {
                                                                $value->table_align = 'tar';
                                                            }
                                                        }
                                                    }
                                                // TABLE_ALIGN

                                                $value->order = replace('-l', '', $value->order);
                                                $value->order = replace('-L', '', $value->order);
                                                $value->order = replace('-c', '', $value->order);
                                                $value->order = replace('-C', '', $value->order);
                                                $value->order = replace('-r', '', $value->order);
                                                $value->order = replace('-R', '', $value->order);
                                                $return[$value->order] = $value;
                                            }

                                        }
                                    }
                                }
                            }
                        // INPUT
                    // GET COLUMNS
                }
            // ELSE

            return $return;
        }

        public static function columns_extra(array $columns, object $menu_admin): array
        {
            if (isset($menu_admin->columns_extra)) {
                $ex = explode(', ', $menu_admin->columns_extra);
                foreach ($ex as $key => $value) {
                    $columns[$value] = (object)["label" => $value, "name" => $value, "type" => "text", "check" => true, "table_align" => "tac"];
                }
            }
            return $columns;
        }

        public static function columns__verify_no(object $value): bool
        {
            if (isset($value->type)) {
                if (!compare__('editor', $value->type) && $value->type != 'hidden' && $value->type != 'info' && $value->type != 'password') {
                    return true;
                }
            }
            return false;
        }
    // COLUMNS









    // QUERYS
        public static function querys(Request $request, array $arr, object $menu_admin, array | null $ids, string $type): array
        {
            $return = [];
            $options_all = $menu_admin->input;

            if ($type == 'table') {
                $ids = $ids ?: [0];
            }

            try {
                $query_menu_admin = new ($menu_admin->table__);
                $fillable = $query_menu_admin->fillable__();
                foreach ($options_all??[] as $key => $value) {
                    if (isset($value->check) && ($value->check === true || $value->check === 'true')) {
                        if (
                            $type == 'table'
                            || ($type == 'create_update' && $value->type != 'column' && $value->type != 'query' && $value->type != 'search')
                        ) {

                            // AUTOCOMPLETE
                                if ($type == 'create_update' && isset($value->tags) && compare__('autocomplete', $value->tags)) {
                                    if (!isset($return[$value->options])) {
                                        $YMenuAdmin = YMenuAdmin_Admin::find($value->options);
                                        $query = new ($YMenuAdmin->table__);
                                        $response = $query->select('id', 'name')->find($arr['OBJ']['VALUE'][$value->name]??0);
                                        if (isset($response->id)) {
                                            $return[$value->options][] = [
                                                "id" => $response->id,
                                                "name" => $response->name,
                                                "table__" => $response->table__ ?? $YMenuAdmin->table__ ?? '',
                                            ];
                                        }
                                    }
                                }
                            // AUTOCOMPLETE
                                
                            // ELSE
                                else {
                                    if (isset($value->options) && $value->options>0) {
                                        if (!isset($return[$value->options])) {
                                            $value->extra = $value->extra ?? '';

                                            $YMenuAdmin = YMenuAdmin_Admin::find($value->options);
                                            if (isset($YMenuAdmin->table__)) {
                                                $query = new ($YMenuAdmin->table__);
                                                $fillable__query = $query->fillable__();

                                                // COLUMNS
                                                    $columns = ['id', 'name'];
                                                    if (isset($value->extra) && compare__('|->options_columns', $value->extra)) {
                                                        $extra = extra($value->extra, '|->options_columns');
                                                        $ex_1 = explode(', ', $extra[1]);
                                                        foreach ($ex_1 as $key_1 => $value_1) {
                                                            if (compare__(' ', trim($value_1))) {
                                                                break;
                                                            }
                                                            $columns[] = trim($value_1);
                                                        }
                                                    }
                                                    if (isset($value->extra) && compare__('|->rel', $value->extra)) {
                                                        $extra = extra($value->extra, '|->rel');
                                                        if (!empty($extra[2])) {
                                                            $columns[] = trim($extra[2]);
                                                        }
                                                    }
                                                // COLUMNS


                                                // CATEGORIES
                                                    $categories_table = ($type == 'table' && isset($value->name) && $value->name == 'categories');
                                                    if ($categories_table) {
                                                        $columns[] = 'subcategories';
                                                    }
                                                // CATEGORIES
                
                
                                                // TYPE_ITEMS
                                                    if (isset($YMenuAdmin->type_items) && $YMenuAdmin->type_items && !$categories_table) {
                                                        $query = $query->where('type', $YMenuAdmin->type_items);
                                                    }
                                                // TYPE_ITEMS
                
                
                                                // FILTRO ADMIN
                                                    if (isset($YMenuAdmin->filter) && $YMenuAdmin->filter && !$categories_table) {
                                                        eval('$query = '.$YMenuAdmin->filter.';');
                                                    }
                                                // FILTRO ADMIN


                                                // ORDERBY
                                                    if (isset($YMenuAdmin->orderby) && $YMenuAdmin->orderby) {
                                                        $ex = explode(',', $YMenuAdmin->orderby);
                                                        foreach($ex as $key_1 => $value_1) {
                                                            if ($value_1) {
                                                                $ex = explode(' ', $value_1);
                                                                if (!empty($ex[0]) && !empty($ex[1])) {
                                                                    $query = $query->orderByRaw('`'.$ex[0].'`'.$ex[1]);
                                                                }
                                                            }
                                                        }
                                                    }
                                                // ORDERBY


                                                // FILTRO IDS
                                                    if (
                                                        $ids
                                                        && !$categories_table
                                                        && $value->type != 'query'
                                                        && !($value->type == 'checkbox'
                                                            || (isset($value->tags) && compare__('multiple', $value->tags))
                                                        )
                                                        && !($value->type == 'select' // select / search_top / not autocomplete
                                                            && (isset($value->search) && compare__('top', $value->search))
                                                            && !(isset($value->tags) && compare__('autocomplete', $value->tags))
                                                        )
                                                        && !compare__('|->options_all', $value->extra)
                                                        && !compare__('|->items', $value->extra)
                                                    ) {
                                                        $columns_id = 'id';
                                                        $columns_id_rel = $value->name ?? '';

                                                        // OPTIONS_WHEREIN
                                                            if (compare__('|->options_whereIn', $value->extra)) {
                                                                $extra = extra($value->extra, '|->options_whereIn');
                                                                // COLUNAS
                                                                    if (isset($extra[1]) && $extra[1]) {
                                                                        $columns_id = trim($extra[1]);
                                                                    }
                                                                // COLUNAS

                                                                // IDS
                                                                    if (isset($extra[2]) && $extra[2]) {
                                                                        $columns_id_rel = trim($extra[2]);
                                                                    }
                                                                // IDS
                                                            }
                                                        // OPTIONS_WHEREIN

                                                        if (in_array($columns_id_rel, $fillable??[])) {
                                                            $query_select = new $menu_admin->table__;
                                                            $query_select = $query_select->select($columns_id_rel)->whereIn('id', $ids)->whereNotNull($columns_id_rel);
                                                            $query = $query->whereIn($columns_id, $query_select);

                                                        } else {
                                                            $query = $query->where('id', 0);
                                                        }
                                                    }
                                                // FILTRO IDS


                                                // ITEMS / REL
                                                    if (compare__('|->items', $value->extra)) {
                                                        $table = $menu_admin->table;
                                                        $extra = extra($value->extra, '|->items');
                                                        if (isset($extra[1]) && $extra[1]) {
                                                            $table = $extra[1];
                                                        }

                                                        if ($table && $ids) {
                                                            $query = $query->select(['id', 'name', $table])->whereIn($table, $ids);
                                                        }
                                                    }
                                                // ITEMS / REL


                                                // GET
                                                    if (is_collection($query) || is_array($query)) {
                                                        $return[$value->options] = $query;

                                                    } else {
                                                        if (in_array('order', $fillable__query??[])) {
                                                            $return[$value->options] = $query->orderBy('order')->orderBy('name')->get($columns);

                                                        } else {
                                                            $return[$value->options] = $query->orderBy('name')->get($columns);
                                                        }
                                                    }
                                                // GET
                
                
                                                // CATEGORIES
                                                    // CREATE / UPDATE
                                                        if (
                                                            $type == 'create_update'
                                                            && isset($value->name) && $value->name == 'categories'
                                                            && isset($value->tags) && compare__('all', $value->tags)
                                                        ) {
                                                            $query_sub_1 = new ($YMenuAdmin->table__);
                                                            $sub_1 = $query_sub_1->select(['id', 'name', 'type', 'subcategories'])->where('type', '>', 0)->orderBy('name')->get($columns);
                
                                                            $temp = $return[$value->options];
                                                            $return[$value->options] = [];
                                                            foreach ($temp as $key_1 => $value_1) {
                                                                $value_1->name = '<b>'.$value_1->name.'</b>';
                                                                $return[$value->options][] = $value_1;
                
                                                                foreach ($sub_1 as $key_2 => $value_2) {
                                                                    for($i = 1; $i <= 10; $i++) {
                                                                        if ($value_2->type == $i) {
                                                                            if ($value_1->id == $value_2->subcategories) {
                                                                                $name = $value_2->name;
                
                                                                                $value_2->name = ' ';
                                                                                for($y = 1; $y <= $i; $y++) {
                                                                                    $value_2->name .= '&nbsp;&nbsp;&nbsp;-';
                                                                                }
                                                                                $value_2->name .= ' '.$name;
                                                                                $return[$value->options][] = $value_2;
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    // CREATE / UPDATE
                                                // CATEGORIES
                                            }
                                        }
                                    }
                                }
                            // ELSE
                    }
                    }
                }

            } catch (\Throwable $th) {
                // $_GET['errors__try']['__AdminController_Admin::querys'] = $th;
                throw($th);
            }


            // RESOURCES (MENU ADMIN)
                // $return = __Resource::querys($request, $return, $menu_admin, $ids, $type);
            // RESOURCES (MENU ADMIN)


            // TREATMENT
                foreach($return as $key => $value) {
                    foreach($value as $key_1 => $value_1) {
                        $value_1->options = $key;
                    }
                }
            // TREATMENT


            $_GET['__QUERYS__'] = $return;

            return $return;
        }  
    // QUERYS










    // TREATMENT
        public static function treatment(Request $request, object $menu_admin, string $type, int $id = 0): object
        {
            $return = clone $menu_admin;

            // CREATE_EDIT
                if ($type == 'table') {
                }
            // CREATE_EDIT


            // CREATE_EDIT
                if ($type == 'create_edit') {
                    $return->input = [];

                    foreach($menu_admin->input as $key => $value) {
                        if (
                            (!$id && !compare__('|->no_new', $value->extra??''))
                            || ($id && !compare__('|->no_edit', $value->extra??''))
                        ) {
                            $return->input[$key] = $value;
                        }
                    }
                }
            // CREATE_EDIT

            return $return;
        }
    // TREATMENT










    // HOME
        public static function home(Request $request): JsonResponse
        {
            $arr = [];

            
            $arr['status'] = 200;
            return json_encode__($arr, $request);
        }
    // HOME

}
