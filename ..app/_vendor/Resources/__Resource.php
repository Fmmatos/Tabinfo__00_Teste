<?php

namespace Vendor\Resources;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Root\Resources\NEW__Resource;
use Vendor\Models\Admin\YMenuAdmin_Admin;
use Vendor\Models\ZText;
use Vendor\Rules\__CnpjRule;
use Vendor\Rules\__CpfRule;
use Vendor\Rules\__ExistRule;
use Vendor\Rules\__PasswordRule;
use Vendor\Services\__ImageService;
use Vendor\Services\__UploadService;
use Vendor\Controllers\Admin\__AdminController_Admin;

class __Resource
{

    // QUERY_CUSTOM (CREATE QUERY)
        public static function query_custom(Request $request, array $arr, Model $_MODEL, array $columns_all, object $menu_admin, int $z_items_page, string $type, int $id = 0): array
        {
            // RESOURCES (MENU ADMIN)
                // TABLE
                if($type == 'table'){

                    // QUERY_CUSTOM
                        // QUERYS
                            $querys = [];
                            if(isset($menu_admin->resources->query_custom)){
                                foreach($menu_admin->resources->query_custom as $key_2 => $value_2) {
                                    $ok = false;

                                    if($value_2->type == 'model'){
                                        $query = $_MODEL;
                                        $ok = true;
                                    }
                                    if($value_2->type == 'menu_admin' && $value_2->menu_admin){
                                        $menu_admin__ = YMenuAdmin_Admin::find($value_2->menu_admin);
                                        if(isset($menu_admin__->table__)){
                                            $query = new ($menu_admin__->table__);
                                            $ok = true;
                                        }
                                    }
                                    if($value_2->type == 'table'){
                                        $query = DB::table($value_2->table);
                                        $ok = true;
                                    }

                                    if($ok){
                                        $query = $query::select__($columns_all)->search($request, $menu_admin);
                                        if(isset($value_2->code) AND $value_2->code){
                                            eval('$query = '.$value_2->code.';');
                                        }
                                        $querys[] = $query;
                                    }
                                }
                            }
                        // QUERYS

                        if(count($querys)){
                            // UNION ALL
                                $query = array_shift($querys);
                                foreach ($querys as $key => $value_2) {
                                    $query = $query->unionAll($value_2);
                                }
                            // UNION ALL


                            // DATA
                                $query = $query->order($request, $menu_admin);
                                $arr['OBJ']['QUERY_CUSTOM'] = $query->paginate($z_items_page)->toArray();
                            // DATA
                        }
                    // QUERY_CUSTOM

                    // QUERY_CUSTOM__FILTERS
                        if(isset($menu_admin->resources->query_custom)){
                            if(isset($menu_admin->resources->query_custom)){
                                foreach($menu_admin->resources->query_custom as $key_2 => $value_2) {
                                    if($value_2->type == 'filters'){
                                        if(isset($value_2->code) AND $value_2->code){
                                            $query = $_MODEL;
                                            eval('$query = '.$value_2->code.';');
                                            $arr['OBJ']['QUERY_CUSTOM__FILTERS'] = $query;
                                        }
                                    }
                                }
                            }
                        }
                    // QUERY_CUSTOM__FILTERS
                    }
                // TABLE


                // CREATE_EDIT
                    if($type == 'create_edit'){
                    }
                // CREATE_EDIT
            // RESOURCES (MENU ADMIN)

            return $arr;
        }
    // QUERY_CUSTOM (CREATE QUERY)










    // AUTOCOMPLETE
        public static function autocomplete(Request $request, object $menu_admin, string $table, string $busca): array
        {
            $arr = [];
            $arr['items'] = [];

            // RESOURCES (MENU ADMIN)
                if(isset($menu_admin->resources->autocomplete)){
                    foreach($menu_admin->resources->autocomplete as $key_2 => $value_2) {
                        if(isset($value_2->function) && $value_2->function){
                            $function = replace('(', '', $value_2->function);
                            $function = replace(')', '', $function);
                            if(class_exists('Root\Resources\NEW__Resource')){
                                if(method_exists('Root\Resources\NEW__Resource', $function)){
                                    $arr = NEW__Resource::{$function}($request, $arr, $menu_admin, $table, $busca);
                                }
                            }
                        }
                    }
                }
            // RESOURCES (MENU ADMIN)

            return $arr;
        }
    // AUTOCOMPLETE










    // SEARCH
        public static function search(Builder $query, Model $model, Request $request, object $menu_admin, array $columns_all): Builder
        {
            $fillable__ = $model->fillable__();

            // SEARCH
                if (!isset($request['init__']) && isset_COOKIES('SEARCH__search')) {
                    $search = COOKIES('SEARCH__search');
                    $search = replace('%', '', $search);
                    $search = replace("'", '', $search);

                    $YMenuAdmin_Admin = YMenuAdmin_Admin::orderBy('id', 'DESC')->get()->keyBy('id');

                    // TREATMENT (COL || COL_REL)
                        $columns = __AdminController_Admin::columns($request, $menu_admin, 'table');

                        $col = [];
                        foreach ($columns as $key => $value) {
                            if ($value->type != 'sel') {
                                if (in_array($value->name, $fillable__)) {
                                    $col[] = $value;
                                }
                            }
                        }
                    // TREATMENT (COL || COL_REL)


                    // EXACTLY
                        // ID
                            if (compare__('search', $search)) {
                                $query->where($model->table.'.id', replace('#', '', $search));

                            }
                        // ID


                        // PRICE
                            else if (compare__(CURRENCY, $search)) {
                                foreach($columns_all as $key => $value) {
                                    if(isset($value->name) && ($value->type == 'price' || $value->name == 'price' || compare__('price_', $value->name))){
                                        $query->where($model->table.'.'.$value->name, price_($search));
                                    }
                                }
                            }
                        // PRICE
                    // EXACTLY


                    // LIKE / REL
                        else {
                            $query->where(function ($query) use ($search, $model, $col, $YMenuAdmin_Admin) {
                                $query->where($model->table.'.id', '=', 0);

                                // FIELDS
                                    foreach ($col as $key => $value) {
                                        if ($value->type != 'sel') {

                                            // ACTIVE
                                                if ($value->name == 'active') {
                                                    if (compare__('ativo', $search)) {
                                                        $query->orWhere($model->table.'.'.$value->name, 1);

                                                    } else if(compare__('desativado', $search)) {
                                                        $query->orWhere($model->table.'.'.$value->name, 0);
                                                    }
                                                }
                                            // ACTIVE





                                            // PRICE
                                                else if ($value->type == 'price') {
                                                    $treatment = replace(CURRENCY, '', $search);
                                                    if(compare__(',', $value->name)){
                                                        $treatment = replace('.', '', $treatment);
                                                        $treatment = replace(',', '.', $treatment);
                                                    }
                                                    $treatment = trim($treatment);

                                                    if (is_number($treatment)) {
                                                        $query->orWhere($model->table.'.'.$value->name, $treatment);
                                                    }
                                                }
                                            // PRICE





                                            // DATE
                                                else if ($value->type == 'date' || $value->type == 'datetime' || $value->type == 'datetime-local' || $value->name == 'created_at') {
                                                    $data = str_replace(' ', '/', $search);
                                                    $data = str_replace(':', '/', $data);
                                                    $data = explode('/', $data);
                                                    $data_final = $data[0];
                                                    if (isset($data[1]))	$data_final = $data[1].'-'.$data[0];
                                                    if (isset($data[2]))	$data_final = $data[2].'-'.$data[1].'-'.$data[0];
                                                    if (isset($data[3]))	$data_final = $data[2].'-'.$data[1].'-'.$data[0].' '.$data[3];
                                                    if (isset($data[4]))	$data_final = $data[2].'-'.$data[1].'-'.$data[0].' '.$data[3].':'.$data[4];
                                                    if (isset($data[5]))	$data_final = $data[2].'-'.$data[1].'-'.$data[0].' '.$data[3].':'.$data[4].':'.$data[5];

                                                    $query->orWhere($model->table.'.'.$value->name, 'like', '%'.$data_final.'%');
                                                }
                                            // DATE





                                            // CHECKBOX
                                                else if ($value->type == 'checkbox' || (isset($value->tag) && compare__('multiple', $value->tag))) {
                                                    // ***
                                                }
                                            // CHECKBOX





                                            // SELECT / RADIO
                                                else if ($value->type == 'select' || $value->type == 'radio' || $value->type == 'subcategories' || $value->type == 'column' || $value->type == 'query') {
                                                    
                                                    // |->>
                                                        if (isset($value->extra) && compare__('|->>', $value->extra)) {
                                                            $extra = explode('|->>', $value->extra);
                                                            $extra = explode('; ', $extra[1]);

                                                            $filter = [];
                                                            foreach ($extra as $key_2 => $value_2) {
                                                                $ex = explode(': ', $value_2);

                                                                if (isset($ex[1])) {
                                                                    $filter[$ex[0]] = $ex[1];
                                                                }
                                                            }

                                                            foreach ($filter as $key_2 => $value_2) {
                                                                if (stripos((not('accents', lower($value_2))), (not('accents', lower($search)))) !== false) {
                                                                    $query->orWhere($model->table.'.'.$value->name, '=', $key_2);
                                                                }
                                                            }
                                                        }
                                                    // |->>


                                                    // OPTIONS
                                                        if (isset($value->options) && $value->options) {
                                                            if (isset($YMenuAdmin_Admin[$value->options]->table__)) {
                                                                $menu_admin_model = new $YMenuAdmin_Admin[$value->options]->table__;

                                                                $query_cate = $menu_admin_model->select(['id'])->where('name', 'like', '%'.$search.'%');
                                                                $query->orWhereIn($model->table.'.'.$value->name, $query_cate);
                                                            }
                                                        }
                                                    // OPTIONS

                                                }
                                            // SELECT / RADIO





                                            // CATEGORIES
                                                else if ($value->type == 'categories' && isset($value->options)) {
                                                    if (isset($YMenuAdmin_Admin[$value->options]->table__)) {
                                                        $menu_admin_model = new $YMenuAdmin_Admin[$value->options]->table__;

                                                        // CATEGORIES
                                                            $query_cate = $menu_admin_model->select(['id'])->where('name', 'like', '%'.$search.'%');
                                                            $query->orWhereIn($model->table.'.'.$value->name, $query_cate);
                                                        // CATEGORIES

                                                        // SUBCATEGORIES
                                                            $query_subcate = $menu_admin_model->select(['id'])->whereIn('subcategories', $query_cate);
                                                            $query->orWhereIn($model->table.'.'.$value->name, $query_subcate);
                                                        // SUBCATEGORIES

                                                    }
                                                }
                                            // CATEGORIES





                                            // JSON_FIELDS
                                                else if ($value->type == 'json_fields') {
                                                    $query->where($model->table.'.'.$value->name, 'LIKE', '%"'.$search.'":"true"%')
                                                    ->orWhere($model->table.'.'.$value->name, 'LIKE', '%"'.$search.'":true%');                                
                                                }
                                            // JSON_FIELDS





                                            // EDITOR
                                                else if ($value->type == 'editor') {
                                                    // NOT SEARCH
                                                }
                                            // EDITOR





                                            // ELSE
                                                else {
                                                    $query->orWhere($model->table.'.'.$value->name, 'like', '%'.$search.'%');
                                                }
                                            // ELSE

                                        }
                                    }
                                // FIELDS

                            });
                        }
                    // LIKE / REL
                }
            // SEARCH





            // SEARCH TOP / ADV
                // DATE
                    if (
                        !isset($request['init__'])
                        &&(
                            (COOKIES('SEARCH__search_date_ini') && COOKIES('SEARCH__search_date_ini') != '' && COOKIES('SEARCH__search_date_ini') != 'null')
                            || (COOKIES('SEARCH__search_date_fim') && COOKIES('SEARCH__search_date_fim') != '' && COOKIES('SEARCH__search_date_fim') != 'null')
                            || (COOKIES('SEARCH__search_date_field') && COOKIES('SEARCH__search_date_field') != '' && COOKIES('SEARCH__search_date_field') != 'null')
                        )
                    ) {
                        $query = $query->where(function ($query) use ($model) {
                                $columns = COOKIES('SEARCH__search_date_field') ? COOKIES('SEARCH__search_date_field') : 'created_at';
                            if (COOKIES('SEARCH__search_date_ini')) {
                                $query->where($model->table.'.'.$columns, '>=', COOKIES('SEARCH__search_date_ini'));
                            }
                            if (COOKIES('SEARCH__search_date_fim')) {
                                $query->where($model->table.'.'.$columns, '<=', COOKIES('SEARCH__search_date_fim').' 23:59:59');
                            }
                        });
                    }
                // DATE


                // EXIST IN FILLABLE__
                    // SEARCH_DINAMIC_
                        if (!isset($request['init__'])) {
                            foreach(COOKIES_LIST() as $key => $value) {
                                if (isset($value['name']) && $value['name'] && compare__ini('SEARCH__search_dinamic_', $value['name'])) {
                                    $columns = str_replace('SEARCH__search_dinamic_', '', $value['name']);

                                    $ok = 0;
                                    foreach($fillable__ as $key_1 => $value_1) {
                                        if ($value_1 == $columns) {
                                            $ok = 1;
                                        }
                                    }

                                    if ($ok == 1 && isset($value['value']) && $value['value']) {
                                        $query = $query->where($model->table.'.'.$columns, $value['value']);
                                    }
                                }
                            }
                        }

                        // |->VALUE__
                            if (isset($menu_admin->input)) {
                                foreach($menu_admin->input as $key => $value) {
                                    if (isset($value->search) && $value->search) {
                                        if (isset($value->name) && isset($value->check) && ($value->check === true || $value->check === 'true')) {
                                            if (isset($value->extra) && compare__('|->value__->', $value->extra)) {
                                                if (!isset($request['search_dinamic_'.$value->name])) {
                                                    $extra = extra($value->extra, '|->value__');

                                                    $ok = 0;
                                                    foreach($fillable__ as $key_1 => $value_1) {
                                                        if ($value_1 == $value->name) {
                                                            $ok = 1;
                                                        }
                                                    }

                                                    if ($ok == 1) {
                                                        // IF COOKIES EXIST
                                                            if (COOKIES('SEARCH__'.$value->name) && !isset($request['init__'])) {
                                                                $extra[1] = COOKIES('SEARCH__'.$value->name);
                                                            }
                                                        // IF COOKIES EXIST

                                                        // FILTER
                                                            if (compare__('|', $extra[1])) {
                                                                $extra[1] = explode('|', $extra[1]);
                                                                $query = $query->whereIn($model->table.'.'.$value->name, $extra[1]);

                                                            } else {
                                                                $query = $query->where($model->table.'.'.$value->name, $extra[1]);
                                                            }
                                                        // FILTER
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        // |->VALUE__
                    // EXIST IN FILLABLE__
                // SEARCH_DINAMIC_
            // SEARCH TOP / ADV





            // MENU ADMIN
                // TYPE
                    if (isset($menu_admin->type_items) && $menu_admin->type_items) {
                        $query = $query->where('type', $menu_admin->type_items);
                    }
                // TYPE


                // TAGS
                    if (isset($request['GET'][1]) && $request['GET'][1] == 'menu_admin') {
                        if (isset($request['GET']['t']) && $request['GET']['t'] != null) {
                            $query = $query->where('type', $request['GET']['t']);
                        }
                    }
                // TAGS


                // FILTER
                    if (isset($menu_admin->filter) && $menu_admin->filter) {
                        eval($menu_admin->filter.';');
                    }
                // FILTER


                // ITEMS__
                    if (isset($request['GET']['items__']) && $request['GET']['items__'] && isset($request['GET']['items__']) && $request['GET']['items__']) {
                        $ex = explode('-', $request['GET']['items__']);
                        if (isset($ex[2]) && $ex[2]) {
                            $query->where($model->table.'.'.$ex[2], $ex[1]);
                        }
                    }
                // ITEMS__


                // USERS
                    if ($menu_admin->id == 6) {
                        if ($request->user()->id != 1 && $request->user()->id != 2) {
                            $query = $query->where('users.id', '!=', 2);
                        }
                        $query = $query->where('users.id', '!=', 1);
                    }
                // USERS
            // MENU ADMIN


            // RESOURCES (MENU ADMIN)
                if(isset($menu_admin->resources->search)){
                    foreach($menu_admin->resources->search as $key_2 => $value_2) {
                        if(isset($value_2->function) && $value_2->function){
                            $function = replace('(', '', $value_2->function);
                            $function = replace(')', '', $function);
                            if(class_exists('Root\Resources\NEW__Resource')){
                                if(method_exists('Root\Resources\NEW__Resource', $function)){
                                    $query = NEW__Resource::{$function}($query, $model, $request, $menu_admin);
                                }
                            }
                        }
                    }
                }
            // RESOURCES (MENU ADMIN)


            // SQL
                sql($query); $_GET['pre_fixed']['query'] = \Vendor\Resources\__Resource::toSql($query->toSql(), $query->getBindings());
            // SQL

            return $query;
        }
    // SEARCH










    // FIELDS
        // FIELDS
            public static function fields(Model $item, Model $model): void
            {
                /*
                * value = val
                * item = object
                * value_1 = array
                */
                $array = collection__to__array($item);

                // TABLE__TYPE
                    $array['table__type'] = $item->type ? $item->type : $model->table;
                // TABLE__TYPE


                // TREATMENT
                    if ( !(isset($array['table__type']) && ($array['table__type'] == 'x_settings' || $array['table__type'] == 'users' || $array['table__type'] == 'y_menu_admin_categories') ) ) {

                        // TABLE__
                            if (!str_starts_with($model->table, 'x_') && !str_starts_with($model->table, 'y_') && !str_starts_with($model->table, 'z_')) {
                                if (!isset($_GET['__FIND_ID__'])) {
                                    if ( !(isset($array['table__']) AND $array['table__']) ) {
                                        $array['table__'] = $model->table;
                                    }
                                }
                            }    
                        // TABLE__

                       
                        foreach ($array as $key => $value) {
                            // TREATMENT
                                // IMAGE
                                    if ($key == 'image' || compare__('image_', $key) && is_json($value)) {
                                        $json_decode = json_decode($value, true);

                                        $item->$key = '';
                                        $item->{$key.'__'} = '';
                                        $item->{$key.'__original'} = $json_decode[0]['file']??'';

                                        if (is_array($json_decode)) {
                                            $tempArray = [];
                                            foreach ($json_decode as $key_1 => $value_1) {
                                                if ( (isset($value_1['file']) && $value_1['file'] && file_exists(DIR_P.PHOTOS.'/'.$value_1['file'])) || (isset($value_1['url']) && $value_1['url']) ) {

                                                    // MAIN
                                                        if ($key_1 == 0) {
                                                            // URL
                                                                if (!empty($value_1['url'])) {
                                                                    if ($item->id == 10) {
                                                                        $item->$key = '';
                                                                        $item->{$key.'__'} = '';
                                                                        $item->{$key.'__original'} = $value;

                                                                    } else {
                                                                        $item->$key = $value_1['url'];
                                                                        $item->{$key.'__'} = $value_1['url'];
                                                                        $item->{$key.'__original'} = $value;
                                                                    }
                                                                }
                                                            // URL

                                                            // IMAGE
                                                                else {
                                                                    $item->$key = __ImageService::img($value_1['file'], $array, $key);
                                                                    $item->{$key.'__'} = DIR.PHOTOS.'/'.$value_1['file'];
                                                                    $item->{$key.'__original'} = $value;
                                                                    // $item->{$key.'__size'} = $value_1['size'];
    
                                                                }
                                                            // IMAGE
                                                        }
                                                    // MAIN

                                                    // ARRAY
                                                        $tempArray[] = (object)[
                                                            'image' => !empty($value_1['url']) ? $value_1['url'] : __ImageService::img($value_1['file'], $array, $key),
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
                                    }
                                // IMAGE


                                // PRICE
                                    else if($key == 'price' || compare__('price_', $key)) {
                                        $key__ = $key.'__';
                                        $item->$key = price($value);
                                        $item->$key__ = decimal((float)$value);
                                    }
                                // PRICE


                                // DESCRIPTION
                                    else if($key == 'description') {
                                        $key__ = $key.'__';
                                        $item->$key = $value;
                                        $item->$key__ = nl2br($value);
                                    }
                                // DESCRIPTION


                                // EDITOR
                                    else if($key == 'editor' || compare__('editor_', $key)) {
                                        $item->$key = editor_base64($value);
                                    }
                                // EDITOR


                                // ORDERS (STATUS)
                                    else if($model->table == 'orders_status' && $key == 'type') {
                                        if ($item->id <= 100) {
                                            $type = '';
                                            if ($item->type == 'init') {        $type = ' (Inicial)'; }
                                            if ($item->type == 'analyzes') {    $type = ' (Em Analise)'; }
                                            if ($item->type == 'canceled') {    $type = ' (Cancelamento)'; }
                                            if ($item->type == 'refund') {      $type = ' (Estorno)'; }
                                            if ($item->type == 'rejected') {    $type = ' (Recusado)'; }
                                            if ($item->type == 'paid') {        $type = ' (Pago)'; }
                                            if ($item->type == 'sent') {        $type = ' (Enviado)'; }
                                            $item->$key = '-->HTML-><div class="fwb5 c_blue">Padrão'.$type.'</div>';
                                        } else {
                                            $item->$key = '';
                                        }
                                    }
                                // ORDERS (STATUS)
                            // TREATMENT





                            // TREATMENTS FINALS
                                // JSON
                                    if (is_json($item->$key) && !is_number($item->$key)) {
                                        $item->$key = json_decode($item->$key);
                                    }
                                // JSON


                                // TABLE__ (SE O PRIMEIRO NAO FOR VAI AQUI)
                                    if ($key == 'id' && !str_starts_with($model->table, 'x_') && !str_starts_with($model->table, 'y_') && !str_starts_with($model->table, 'z_')) {
                                        if (!isset($_GET['__FIND_ID__'])) {
                                            if ( !(isset($item->table__) AND $item->table__) ) {
                                                $item->table__ = $model->table;
                                            }
                                        }
                                    }
                                // TABLE__ (SE O PRIMEIRO NAO FOR VAI AQUI)
                            // TREATMENTS FINALS





                            // NEW
                                $item = NEW__Resource::fields($item, $key, $value, $model->table);
                            // NEW
                        }

                    }
                // TREATMENT 
            }
        // FIELDS










        // DATATABLE
            public static function fields_datatable(Request $request, array $arr, array $array, object $menu_admin): array
            {
                /*
                * key = name
                * value = val
                * value__ = array
                */


                // QUERYS OPTIONS
                    $ids = [];
                    foreach ($array as $key => $value) {
                        $ids[] = $value['id'];
                    }

                    $QUERYS = __AdminController_Admin::querys($request, $arr, $menu_admin, $ids, 'table');
                // QUERYS OPTIONS





                // FIELDS
                    $options_all = (array)$menu_admin->input;

                    $clone = [ ...$array ];
                    foreach ($clone as $key__ => $value__) {
                        foreach ($value__ as $key => $value) {
                            foreach ($options_all as $key_1 => $value_1) {
                                if (isset($value_1->name) && $value_1->name == $key) {
                                    if ( !(isset($value_1->extra) && compare__('|->no_fields', $value_1->extra)) ) {

                                        if (0) {}



                                        // |->
                                            // DATE
                                                else if(isset($value_1->extra) && compare__('|->date', $value_1->extra)) {
                                                    $ex = explode('->', $value_1->extra);
                                                    $ex[2] = replace('Y-m-d', DATE_FORMAT, $ex[2]);
                                                    $array[$key__][$key] = isset($ex[2]) ? date__($value, $ex[2]) : date__($value);
                                                }
                                            // DATE





                                            // ITENS
                                                else if(isset($value_1->extra) && compare__('|->items', $value_1->extra)) {
                                                    $val_1 = 0;
                                                    if (isset($QUERYS[$value_1->options])) {
                                                        foreach ($QUERYS[$value_1->options] as $key_2 => $value_2) {
                                                            $table = $menu_admin->table;
                                                            $extra = extra($value_1->extra, '|->items');
                                                            if (isset($ex[1]) && $ex[1]) {
                                                                $table = $ex[1];
                                                            }

                                                            $ok = false;
                                                            foreach($value_2->toArray() as $key_3 => $value_3) {
                                                                if($key_3 == $table){
                                                                    $ok = true;
                                                                }
                                                            }

                                                            if ($ok && $table && isset($value_2->{$table})) {
                                                                if ($value__['id'] == $value_2->{$table}) {
                                                                    $val_1++;
                                                                }
                                                            }
                                                        }
                                                    }
                                                    $array[$key__]['items__'.$value_1->order.'__'.$key] = $val_1;

                                                    $modulo = YMenuAdmin_Admin::find($value_1->options);
                                                    $array[$key__]['items__'.$value_1->order.'__'.$key.'__'] = $modulo;
                                                }
                                            // ITENS





                                            // PRICE
                                                else if(isset($value_1->extra) && $value_1->extra == '|->price') {
                                                    $array[$key__][$key] = price($value, 1);
                                                }
                                            // PRICE
                                        // |->





                                        // |->>
                                            else if(isset($value_1->extra) && compare__('|->>', $value_1->extra) && !compare__('|->year', $value_1->extra)) {
                                                // MOUNTH
                                                    if (isset($value_1->extra) && compare__('|->month', $value_1->extra)) {
                                                        $array[$key__][$key] = month($value);
                                                    }
                                                // MOUNTH

                                                // CHECKBOX / MULTIPLE
                                                    else if($value_1->type == 'checkbox' || (isset($value_1->tags) && compare__('multiple', $value_1->tags)) ) {
                                                        $val_1 = [];

                                                        if ($value && (is_object($value) || is_array($value)) ) {
                                                            $ex = explode('; ', replace('|->>', '', $value_1->extra));
                                                            foreach ($ex as $key_2 => $value_2) {
                                                                $ex_1 = explode(': ', $value_2);

                                                                $vals = [];
                                                                foreach ($value as $key_3 => $value_3) {

                                                                    // CHECKBOX
                                                                        if ($value_1->type == 'checkbox') {
                                                                            if ($value_3 === true || $value_3 === 'true') {
                                                                                $vals[] = $key_3;
                                                                            }
                                                                        }
                                                                    // CHECKBOX

                                                                    // MULTIPLE (SELECT)
                                                                        else {
                                                                            $vals[] = $value_3;
                                                                        }
                                                                    // MULTIPLE (SELECT)

                                                                }

                                                                if (isset($ex_1[1]) && in_array($ex_1[0], $vals)) {
                                                                    $val_1[] = '&bull; '.$ex_1[1];
                                                                }
                                                            }
                                                        }

                                                        $array[$key__][$key] = $val_1 ? '-->HTML->'.implode('<br>', $val_1) : '- - - -';
                                                    }
                                                // CHECKBOX / MULTIPLE

                                                // OPTIONS EXTRA
                                                    else {
                                                        $val_1 = '';
                                                        $extra = explode('|->>', $value_1->extra);
                                                        $ex = explode('; ', $extra[1]);
                                                        foreach ($ex as $key_2 => $value_2) {
                                                            $ex_1 = explode(': ', $value_2);
                                                            if (isset($ex_1[1]) && $value == $ex_1[0]) {
                                                                $val_1 = $ex_1[1];
                                                            }
                                                        }

                                                        $array[$key__][$key] = $val_1;
                                                    }
                                                // OPTIONS EXTRA
                                            }
                                        // -->





                                        // TABLE->
                                            else if(isset($value_1->extra) && compare__('table->', $value_1->extra)) {
                                                $ex = explode('->', $value_1->extra);

                                                $array[$key__][$key] = 'table->';
                                            }
                                        // TABLE->





                                        // DATE
                                            else if(compare__fim('_at', $key)) {
                                                $array[$key__][$key] = date__(date('c', strtotime($value)), DATE_FORMAT.' H:i');
                                            }

                                            else if(isset($value_1->type) && $value_1->type == 'date') {
                                                if ($value != null && $value > 0) {
                                                    $array[$key__][$key] = date(DATE_FORMAT, strtotime($value));
                                                }
                                            }
                                            else if(isset($value_1->type) && $value_1->type == 'datetime-local') {
                                                if ($value != null && $value > 0) {
                                                    $array[$key__][$key] = date(DATE_FORMAT.' H:i', strtotime($value));
                                                }
                                            }
                                            else if(isset($value_1->type) && $value_1->type == 'datetime') {
                                                if ($value != null && $value > 0) {
                                                    $array[$key__][$key] = date(DATE_FORMAT.' H:i', strtotime($value));
                                                }
                                            }

                                            else if(isset($value_1->type) && $value_1->type == 'month') {
                                                if ($value != null && $value > 0) {
                                                    $array[$key__][$key] = date(DATE_FORMAT_MONTH, strtotime($value));
                                                }
                                            }

                                            else if($value_1->type == 'column' && isset($value_1->name) && ($value_1->name == 'datetime' || compare__('datetime_', $value_1->name))) {
                                                $array[$key__][$key] = date__($value, DATE_FORMAT.' H:i');
                                            }
                                            else if($value_1->type == 'column' && isset($value_1->name) && ($value_1->name == 'date' || compare__('date_', $value_1->name))) {
                                                $array[$key__][$key] = date__($value, DATE_FORMAT);
                                            }
                                        // DATE





                                        // OPTIONS / CATEGORIES
                                            else if(isset($value_1->options) && $value_1->options>0) {
                                                $val_1 = '- - - -';

                                                if (isset($QUERYS[$value_1->options])) {
                                                    // CHECKBOX / MULTIPLE
                                                        // if ($value_1->type == 'checkbox' || (isset($value_1->tags) && compare__('multiple', $value_1->tags)) || is_object($value) || is_array($value) ) {
                                                        if (is_object($value) || is_array($value)) {
                                                            $val_1__ = [];
                                                            if ($value && (is_object($value) || is_array($value)) ) {    
                                                                foreach ($value as $key_2 => $value_2) {
                                                                    // CHECKBOX
                                                                        if ($value_1->type == 'checkbox') {
                                                                            if ($value_2 === true || $value_2 === 'true') {
                                                                                foreach ($QUERYS[$value_1->options] as $key_3 => $value_3) {
                                                                                    if ($key_2 == $value_3->id) {
                                                                                        $val_1__[] = '&bull; '.$value_3->name;
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    // CHECKBOX

                                                                    // MULTIPLE (SELECT)
                                                                        else {
                                                                            foreach ($QUERYS[$value_1->options] as $key_3 => $value_3) {
                                                                                if ($value_2 == $value_3->id) {
                                                                                    $val_1__[] = '&bull; '.$value_3->name;
                                                                                }
                                                                            }
                                                                        }
                                                                    // MULTIPLE (SELECT)

                                                                }
                                                            }

                                                            $val_1 = $val_1__ ? '-->HTML->'.implode('<br>', $val_1__) : '- - - -';
                                                        }
                                                    // CHECKBOX / MULTIPLE

                                                    // CATEGORIES
                                                        else if($value_1->name == 'categories') {
                                                            $val_1 = '-->HTML->';
                                                            foreach ($QUERYS[$value_1->options] as $key_2 => $value_2) {
                                                                if ($value == $value_2->id) {
                                                                    $val_1 .= self::fields_menu_admin_final_categories($QUERYS[$value_1->options], $value_2);
                                                                    $val_1 .= $value_2->name;
                                                                }
                                                            }
                                                        }
                                                    // CATEGORIES

                                                    // ONLY
                                                        else {
                                                            foreach ($QUERYS[$value_1->options] as $key_2 => $value_2) {
                                                                if (!is_object($value) && !is_array($value)) {
                                                                    if ($value == $value_2->id) {
                                                                        $val_1 = $value_2->name;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    // ONLY
                                                }

                                                $array[$key__][$key] = $val_1;
                                            }
                                        // OPTIONS / CATEGORIES





                                        // SET -> set->de set[price_old] por set[price];
                                            else if(isset($value_1->extra) && compare__('|->set->', $value_1->extra)) {
                                                $val_1 = '';
                                                $ex = explode('|->set->', $value_1->extra);
                                                $ex = explode(' |', $ex[1]);
                                                $ex = explode('set[', $ex[0]);
                                                foreach ($ex as $key_3 => $value_3) {
                                                    $ex1 = explode(']', $value_3);
                                                    if (isset($value__[$ex1[0]])) {
                                                        $val_1 .= $value__[$ex1[0]];
                                                    } else {
                                                        // $val_1 .= $ex1[0];
                                                    }
                                                    if (isset($ex1[1])) {
                                                        $val_1 .= $ex1[1];
                                                    }
                                                }

                                                $array[$key__][$key] = '-->HTML->'.$val_1;
                                            }
                                        // SET





                                        // FUNCTION-> function($value__) { return $value__["price"]; };
                                            else if(isset($value_1->extra) && compare__('function(', $value_1->extra)) {
                                                try {
                                                    $function = eval("return $value_1->extra;");
                                                    $array[$key__][$key] = '-->HTML->'.$function($value__);

                                                } catch (\Throwable $th) {
                                                    $array[$key__][$key] = '';
                                                }
                                            }
                                        // FUNCTION





                                        // CUSTOMERS_ADDRESS_2
                                            else if($menu_admin->table == 'customers_address_2') {
                                                // CODE_1
                                                    if ($key == 'code_1') {
                                                        $code_1 = crip_2(base64_decode($value__['code_1']));
                                                        $ex = explode('|', $code_1);
                                                        $array[$key__]['code_1'] = '-->HTML->'.substr($ex[0], -6);
                                                    }
                                                // CODE_1

                                                // CODE_2
                                                    if ($key == 'code_2') {
                                                        $code_2 = crip_1(base64_decode($value__['code_2']));
                                                        $array[$key__]['code_2'] = '-->HTML->'.ucwords($code_2);
                                                    }
                                                // CODE_2
                                            }
                                        // CUSTOMERS_ADDRESS_2





                                        // ORDERS
                                            else if($menu_admin->table == 'orders') {
                                                // NAME
                                                    if ($key == 'name') {
                                                        $array[$key__]['name'] = '-->HTML->';
                                                        if (isset($value) && is_array($value)) {
                                                            foreach ($value as $k => $v) {
                                                                $array[$key__]['name'] .= '<div class="lh20">';
                                                                    if (isset($v->name)) {
                                                                        $array[$key__]['name'] .= ($k+1).'. '.$v->name;
                                                                    }
                                                                    if (isset($v->qty) && isset($v->price__)) {
                                                                        $array[$key__]['name'] .= ' ('.$v->qty.'x '.price($v->price__, 1).')';
                                                                    }
                                                                $array[$key__]['name'] .= '</div>';
                                                            }
                                                        }
                                                    }
                                                // NAME
                                            }
                                        // ORDERS





                                        // ONLINE__
                                            if($key == 'online__'){
                                                $array[$key__][$key] = '-->HTML->'.(($value__['last_acess'] && $value__['last_acess'] >= date('Y-m-d H:i:s', strtotime(date('c').' -1 hours'))) ? '<div class="fwb6 c_green">ONLINE</div>' : '<div></div>');
                                            }
                                        // ONLINE__





                                        // RESOURCES (MENU ADMIN)
                                            if(isset($menu_admin->resources->fields_datatable)){
                                                $value__obj = (object)$value__;
                                                $table = $array[0]['table__'] ?? '';
                                
                                                foreach($menu_admin->resources->fields_datatable as $key_2 => $value_2) {
                                                    if($value_2->columns == $key){
                                                        if(isset($value_2->function) && $value_2->function){
                                                            $function = replace('(', '', $value_2->function);
                                                            $function = replace(')', '', $function);
                                                            if(class_exists('Root\Resources\NEW__Resource')){
                                                                if(method_exists('Root\Resources\NEW__Resource', $function)){
                                                                    $array = NEW__Resource::{$function}($request, $array, $key__, $value__obj, $key, $value, $menu_admin, $table, $QUERYS);
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        // RESOURCES (MENU ADMIN)

                                    }
                                }
                            }
                        }
                    }
                // FIELDS

                return $array;
            }
            public static function fields_menu_admin_final_categories(object $array, object $value_2): string
            {
                if ($value_2->subcategories && isset($array[$value_2->subcategories]->id)) {
                    foreach($array as $key => $value) {
                        if ($value->id == $value_2->subcategories) {
                            return $value->name.' ><br> ';
                        }
                    }
                    return '';
                }
                return '';
            }
        // DATATABLE










        // CREATE_EDIT
            public static function fields_create_edit(Request $request, array $arr, string $id, object $menu_admin): array
            {

                // TREATMENT
                    // MENU_ADMIN
                        // JSON_FIELDS

                            // JOIN ALL MULTIPLE
                                $json_fields = [];
                                foreach($arr['OBJ']['menu_admin']->input as $key => $values) {
                                    foreach($values as $align => $value) {
                                        if ($value->type == 'json_fields' && isset($value->tags) && compare__('multiple_', $value->tags)) {
                                            if (preg_match('/\b(multiple_[0-9])\b/i', $value->tags, $m)) {
                                                $m = $m[0];

                                                $json_fields[$m] = (object)[];
                                                $json_fields[$m]->array = [];

                                                foreach($arr['OBJ']['menu_admin']->input as $key_1 => $values_1) {
                                                    foreach($values_1 as $align_1 => $value_1) {
                                                        if ($value_1->type == 'json_fields' && isset($value_1->tags) && compare__('multiple_', $value_1->tags)) {
                                                            for($i = 1; $i <= 10; $i++) {
                                                                if (compare__('multiple_'.$i, $value_1->tags) && compare__('multiple_'.$i, $value->tags)) {
                                                                    $json_fields[$m]->array[] = (array)$value_1;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            // JOIN ALL MULTIPLE


                            // CREATE ONE JSON_FIELDS FOR EACH MULTIPLE
                                $multiples = [];
                                foreach($arr['OBJ']['menu_admin']->input as $key => $values) {
                                    foreach($values as $align => $value) {
                                        if ($value->type == 'json_fields') {

                                            if (isset($value->tags) && preg_match('/\b(multiple_[0-9])\b/i', $value->tags, $m)) {
                                                if (!in_array($m[0], $multiples)) {
                                                    $arr['OBJ']['menu_admin']->input[$key][$align] = (object)[
                                                        'check' => $value->check,
                                                        'type' => $value->type,
                                                        'title' => $value->title??null,
                                                        'multiple' => $m[0],
                                                        'tags' => $value->tags??null,
                                                        'extra' => $value->extra??null,
                                                        'options' => $value->options??null,
                                                        'order' => $value->order??null,
                                                        'array' => $json_fields[$m[0]]->array??[]
                                                    ];
                                                    $multiples[] = $m[0];
                                                
                                                } else {
                                                    unset($arr['OBJ']['menu_admin']->input[$key][$align]);
                                                }

                                            } else {
                                                $arr['OBJ']['menu_admin']->input[$key][$align] = (object)[
                                                    'check' => $value->check,
                                                    'type' => $value->type,
                                                    'title' => $value->title??null,
                                                    'tags' => $value->tags??null,
                                                    'extra' => $value->extra??null,
                                                    'options' => $value->options??null,
                                                    'order' => $value->order??null,
                                                    'array' => [$value]
                                                ];
                                            }

                                        }
                                    }
                                }
                            // CREATE ONE JSON_FIELDS FOR EACH MULTIPLE

                        // JSON_FIELDS
                    // MENU_ADMIN
                // TREATMENT


                // RESOURCES (MENU ADMIN)
                    if(isset($menu_admin->resources->fields_create_edit)){
                        foreach($menu_admin->resources->fields_create_edit as $key_2 => $value_2) {
                            if(isset($value_2->function) && $value_2->function){
                                $function = replace('(', '', $value_2->function);
                                $function = replace(')', '', $function);
                                if(class_exists('Root\Resources\NEW__Resource')){
                                    if(method_exists('Root\Resources\NEW__Resource', $function)){
                                        $arr = NEW__Resource::{$function}($request, $arr, $menu_admin);
                                    }
                                }
                            }
                        }
                    }
                // RESOURCES (MENU ADMIN)

                return $arr;
            }
        // CREATE_EDIT
    // FIELDS










    // SELECT__
        public static function select__(Builder $query, Model $model, string $table__, array $columns_all): Builder
        {
            $table = $model->table;
            $table__ = MODEL__ROOT__OR__ALL($table__);

            // TREATMENT
                $columns = [];
                $columns_others = [];
                $fillable__ = $model->fillable__();

                foreach ($columns_all as $key => $value) {
                    //  MENU_ADMIN
                    if (is_object($value) && isset($value->name) && $value->name != 'sel') {
                        if (in_array($value->name, $fillable__)) {
                            $columns[] = $value->name;
                        } else {
                            $columns_others[] = $value->name;
                        }
                    }
                    //  MENU_ADMIN

                    // NORMAL
                        if (is_string($value)) {
                            if (in_array($value, $fillable__)) {
                                $columns[] = $value;
                            } else {
                                $columns_others[] = $value;
                            }
                        }
                    // NORMAL
                }
            // TREATMENT


            // NO REPEATED
                $columns = array_unique($columns);
            // NO REPEATED


            // SELECT
                $select = [$table.'.id'];
                if ($columns) {
                    foreach ($columns as $key => $value) {
                        $select[] = $table.'.'.$value;
                    }

                } else {
                    $select = [$table.'.*'];
                }
            // SELECT

            // SELECT OTHERS
                foreach ($columns_others as $key => $value) {
                    $select[] = DB::raw("'' as ".$value);
                }
            // SELECT OTHERS


            $query = $query->select($select);

            return $query;
        }
    // SELECT__










    // EDITOR
        public static function editor(Builder  $query, Model $model, string $table__, string $type): Builder
        {
            $table = $model->table;
            $select_atual = $query->getQuery()->columns;
            $editor = $type ? 'editor_'.$type : 'editor';
            $table__ = MODEL__ROOT__OR__ALL($table__);

            // CONFIG
                if ($select_atual) {
                    $select = [];
                    foreach ($select_atual as $key => $value) {
                        if (compare__($table, $value)) {
                            $select[] = $value;
                        } else {
                            $select[] = $table.'.'.$value;
                        }
                    }

                } else {
                    $select = [$table.'.*'];
                }
            // CONFIG


            // TXT
                $select[] = 'text_'.$editor.'.value as '.$editor;
                $query = $query->select($select);

                $query = $query->leftJoin('z_text as text_'.$editor, function($join) use($table__, $table, $editor) {
                    $join->on('text_'.$editor.'.id__', $table.'.id')
                    ->where('text_'.$editor.'.table__', $table__)
                    ->where('text_'.$editor.'.fields', $editor);
                });
            // TXT

            return $query;
        }
    // EDITOR










    // ORDER
        public static function order(Builder $query, Model $model, Request $request, object $menu_admin): Builder
        {
            $menu_admin_orderby = [];
            $ex = explode(',', $menu_admin->orderby??'');
            foreach ($ex as $key => $value) {
                if (trim($value)) {
                    $menu_admin_orderby[] = trim($value);
                }
            }

            $columns_order = $menu_admin_orderby;
            if (!isset($request['init__']) && isset_COOKIES('SEARCH__orderBy')) {
                $columns_order = json_decode(COOKIES('SEARCH__orderBy'));
            }

            $_GET['__Z_TABLE_ORDER__'] = [];
            foreach ($columns_order as $key => $value) {
                $value = replace('`', '', $value);
                $value = trim($value);
                $ex = explode(' ', $value);
                if (isset($ex[1]) && $ex[1]) {
                    $_GET['__Z_TABLE_ORDER__'][] = '`'.$ex[0].'` '.$ex[1];
                }
            }

            if ($_GET['__Z_TABLE_ORDER__']) {
                $orderBy = implode(',', $_GET['__Z_TABLE_ORDER__']);

            } else {
                $options_all = (array)$menu_admin->input;

                $cols = [];
                foreach ($options_all as $key => $value) {
                    if (isset($value->name) && isset($value->check) && ($value->check === true || $value->check === 'true')) {
                        if (isset($value->order) && $value->order) {
                            $cols[] = $value->name;
                        }
                    }
                }

                $orderBy = [];
                if (isset($menu_admin->info) && in_array('columns_order', $menu_admin->info)) {
                    $orderBy[] = '`order` ASC';
                }
                $orderBy[] = '`name` ASC';
                $_GET['__Z_TABLE_ORDER__'] = $orderBy;
                $orderBy = implode(',', $orderBy);
            }

            return $query->orderByRaw($orderBy);
        }
    // ORDER










    // SAVE
        // VALIDATE
            public static function save_validate(array|Request $request, Model $query, ?object $menu_admin = null): array
            {
                $request__ = is_array($request) ? [...$request] : [...$request->all()];
                
                if ($query->table != 'z_text') {

                    // REQUEST__
                        foreach ($request__ as $key => $value) {
                            if (self::exception($key)) {

                                // ARRAY
                                    if (is_array($value) && isset($value['request'])) {

                                        // IMAGE
                                            if ($value['request']->hasFile($key)) {
                                                foreach($value['request']->file($key) as $key_1 => $value_1) {

                                                    // SIZE
                                                        $size = __UploadService::treatment_size($value_1);
                                                        if ($size) {
                                                            $_GET['errors'][] = $size;
                                                        }
                                                    // SIZE

                                                    // TYPE
                                                        $type = __UploadService::treatment_type($value_1, $value['type'], $menu_admin, $key);
                                                        if ($type && !isset($_GET['errors'])) {
                                                            $_GET['errors'][] = $type;
                                                        }
                                                    // TYPE

                                                }
                                            }
                                        // IMAGE

                                        // REMOVE ARRAY
                                            unset($request__[$key]);
                                        // REMOVE ARRAY

                                    }
                                // ARRAY
                            }
                        }
                    // REQUEST__





                    // MENU_ADMIN
                        if (isset($menu_admin->input)) {
                            foreach ($menu_admin->input as $key => $value) {
                                if (isset($value->name) && isset($value->check) && ($value->check === true || $value->check === 'true')) {

                                    // REQUIRED
                                        if (isset($value->tags) && compare__('required', $value->tags)) {
                                            // CHECKBOX
                                                if ($value->type == 'checkbox') {
                                                    $checkbox = [];
                                                    if (isset($request[$value->name]) && $request[$value->name] && is_json($request[$value->name])) {
                                                        $val = json_decode($request[$value->name]);
                                                        if (is_object($val)) {
                                                            foreach ($val as $key => $value) {
                                                                if ($value === true || $value === "true") {
                                                                    $checkbox[$key] = $value;
                                                                }
                                                            }
                                                        }
                                                    }
                                                    if (!(count($checkbox)>0)) {
                                                        $_GET['errors'][] = 'Preencha pelo menos 1 opção de '.$value->label.'!';
                                                    }
                                                }
                                            // CHECKBOX

                                            // ELSE
                                                else if(
                                                    $value->type != 'password'
                                                    && !compare__('pf ', $value->tags)
                                                    && !compare__('pj ', $value->tags)
                                                    && !isset($_GET['NO_REQUIRED'])
                                                ) {
                                                    if (!(isset($value->type) && $value->type == 'column')) {
                                                        if (!(isset($value->extra) && compare__('|->no_new', $value->extra))) {
                                                            if (!(isset($value->extra) && compare__('|->no_edit', $value->extra))) {
                                                                if (!(isset($value->extra) && compare__('|->value_rel', $value->extra))) {
                                                                    if (
                                                                        !isset($request[$value->name])
                                                                        && $request[$value->name] !== "0"
                                                                        && $request[$value->name] !== 0
                                                                    ) {
                                                                        $_GET['errors'][] = 'Preencha o '.$value->label.' Corretamente!';
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            // ELSE
                                        }
                                    // REQUIRED


                                    // VALUE_REL
                                        if (isset($value->extra) && compare__('|->value_rel', $value->extra)) {
                                            $extra = extra($value->extra, '|->value_rel');
                                            $ex = explode('|', $extra[2]);
                                            if (
                                                !(isset($request[$extra[1]]) && isset($ex[0]) && $request[$extra[1]] == $ex[0])
                                                && !(isset($request[$extra[1]]) && isset($ex[1]) && $request[$extra[1]] == $ex[1])
                                                && !(isset($request[$extra[1]]) && isset($ex[2]) && $request[$extra[1]] == $ex[2])
                                                && !(isset($request[$extra[1]]) && isset($ex[3]) && $request[$extra[1]] == $ex[3])
                                                && !(isset($request[$extra[1]]) && isset($ex[4]) && $request[$extra[1]] == $ex[4])
                                                && !(isset($request[$extra[1]]) && isset($ex[5]) && $request[$extra[1]] == $ex[5])
                                                && !(isset($request[$extra[1]]) && isset($ex[6]) && $request[$extra[1]] == $ex[6])
                                                && !(isset($request[$extra[1]]) && isset($ex[7]) && $request[$extra[1]] == $ex[7])
                                                && !(isset($request[$extra[1]]) && isset($ex[8]) && $request[$extra[1]] == $ex[8])
                                                && !(isset($request[$extra[1]]) && isset($ex[9]) && $request[$extra[1]] == $ex[9])
                                            ) {
                                                unset($request[$value->name]);
                                                unset($request__[$value->name]);
                                            }
                                        }
                                    // VALUE_REL


                                    // FULLNAME
                                        if (isset($value->tags) && compare__('fullname', $value->tags)) {
                                            $pass = 1;
                                            $name = fullname(trim($request[$value->name]));
                                            $surname = fullname(trim($request[$value->name]), 1);
                                            if (mb_strlen($name)<=2 || mb_strlen($surname)<=2) {
                                                $pass = 0;
                                            }
                                            if (!$pass) {
                                                $_GET['errors'][] = 'Preencha o '.$value->name.' Completo!';
                                            }
                                        }
                                    // FULLNAME


                                    // VALIDATE
                                        // VALIDATE CPF / CNPJ
                                            // VERIFY
                                                $pf_pj = '';
                                                if (isset($menu_admin->info) && in_array('create_pf_pj', $menu_admin->info)) {
                                                    if ($request['cpf'] && ($request['cnpj'] == '' || $request['cnpj'] == null)) {
                                                        $pf_pj = 'pf';
                                                        if ($value->name == 'cnpj') {
                                                            $value->tags = '';   
                                                        }
                                                    }
                                                    if ($request['cnpj'] && ($request['cpf'] == '' || $request['cpf'] == null)) {
                                                        $pf_pj = 'pj';
                                                        if ($value->name == 'cpf') {
                                                            $value->tags = '';   
                                                        }
                                                    }
                                                }
                                            // VERIFY

                                            
                                            // VALIDATE_CPF
                                                if (isset($value->tags) && compare__('validate_cpf', $value->tags) && $pf_pj != 'pj') {
                                                    $__CpfRule = new __CpfRule();
                                                    $message = '';
                                                    $__CpfRule->validate($value->name, $request[$value->name], function($msg) use (&$message) {
                                                        $message = $msg;
                                                    });
                                                    if ($message) {
                                                        $_GET['errors'][] = $message;
                                                    }
                                                }
                                            // VALIDATE_CPF


                                            // VALIDATE_CNPJ
                                                // if (isset($value->tags) && compare__('validate_cnpj', $value->tags) && $pf_pj != 'pf') {
                                                //     $__CnpjRule = new __CnpjRule();
                                                //     $pass = $__CnpjRule->passes($value->name, $request[$value->name]);
                                                //     if (!$pass) {
                                                //         $_GET['errors'][] = 'CNPJ inválido, insira outro CNPJ!';
                                                //     }
                                                // }
                                            // VALIDATE_CNPJ
                                        // VALIDATE CPF / CNPJ


                                        // VALIDATE__ (EXIST)
                                            if (isset($value->tags) && compare__('validate_exist', $value->tags)) {
                                                $pass = __ExistRule::admin__passes($value->name, $request[$value->name], $query, $menu_admin, $query->id);
                                                if (!$pass) {
                                                    $_GET['errors'][] = 'Este '.$value->name.' inserido já está cadastrado, insira outro '.$value->name.'!';
                                                }
                                            }
                                        // VALIDATE__ (EXIST)


                                        // VALIDATE_PASSWORD
                                            if (isset($value->tags) && compare__('validate_password', $value->tags) && isset($request['password'])) {
                                                $pass = __PasswordRule::admin__passes($value->name, $request['password'], $request);
                                                if ($pass !== '') {
                                                    $_GET['errors'][] = $pass;
                                                }
                                            }
                                        // VALIDATE_PASSWORD
                                    // VALIDATE


                                    // RESOURCES (MENU ADMIN)
                                        if(isset($menu_admin->resources->save_validate)){
                                            foreach($menu_admin->resources->save_validate as $key_2 => $value_2) {
                                                if(isset($value_2->function) && $value_2->function){
                                                    $function = replace('(', '', $value_2->function);
                                                    $function = replace(')', '', $function);
                                                    if(class_exists('Root\Resources\NEW__Resource')){
                                                        if(method_exists('Root\Resources\NEW__Resource', $function)){
                                                            NEW__Resource::{$function}($request, $query, $menu_admin, $key, $value);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    // RESOURCES (MENU ADMIN)

                                }
                            }
                        }
                    // MENU_ADMIN

                }

                unset($request__['password_confirmation']);

                return $request__;
            }
        // VALIDATE





        // SAVE
            public static function save(string $type, Request $request, array $arr, Model $model, object $menu_admin, int $id = 0): void
            {

                // RESOURCES (MENU ADMIN)
                    if(isset($menu_admin->resources->save)){
                        foreach($menu_admin->resources->save as $key_2 => $value_2) {
                            $type_ = json_decode($value_2->type);
                            if (in_array($type, (array)($type_??[]))) {
                                if(isset($value_2->function) && $value_2->function){
                                    $function = replace('(', '', $value_2->function);
                                    $function = replace(')', '', $function);
                                    if(class_exists('Root\Resources\NEW__Resource')){
                                        if(method_exists('Root\Resources\NEW__Resource', $function)){
                                            NEW__Resource::{$function}($request, $arr, $type, $model, $menu_admin, $id);
                                        }
                                    }
                                }
                            }
                        }
                    }
                // RESOURCES (MENU ADMIN)

            }
        // SAVE





        // REL / EDITOR
            public static function save_rel_editor(Model $query, array|Request $request, array $columns): void
            {
                if (!preg_match('/\bx_\w*/', $query->getTable()) && !preg_match('/\by_\w*/', $query->getTable()) && !preg_match('/\bz_\w*/', $query->getTable())) {

                    $table__ = MODEL__ROOT__OR__ALL(get_class($query));
                    $id__ = $query->id;

                    $array = [];
                    foreach ($request as $key => $value) {
                        if (!in_array($key, $columns))
                        $array[$key] = $request[$key];
                    }

                    // Z_EDITOR
                        foreach ($array as $key => $value) {
                            if ($key == 'editor' || compare__('editor_', $key)) {
                                $request__ = [
                                    'table__' => $table__,
                                    'id__' => $id__,
                                    'fields' => $key,
                                    'value' => base64_encode( replace(DIR, '', $value) ),
                                    'updated_at' => now(),
                                ];

                                $query = ZText::where('table__', $table__)->where('id__', $id__)->orderBy('id', 'asc')->where('fields', $key);
                                if ($query->first()) {
                                    $query->update($request__);
                                } else {
                                    ZText::create($request__);
                                }
                            }
                        }
                    // Z_EDITOR
                }
            }
        // REL / EDITOR





        // FILE
            public static function save_file(array | Request $request__, Model $model, Model $query, string $x_settings__name = '', ?object $menu_admin = null): array
            {
                foreach ($request__ as $key => $value) {
                    if (!$x_settings__name || ($x_settings__name && $x_settings__name == $key)) {

                        if (is_array($value) && self::exception($key) && $key != '__GLOBAL__') {
                            $files_save = [];

                            // EXISTING
                                // __IMAGE_ADD__
                                    $images_exists = $value['request'][$key] ?? null;
                                    if(isset($_GET['__IMAGE_ADD__'])){
                                        foreach($_GET['__IMAGE_ADD__'] as $key_1 => $value_1) {
                                            if($value_1 == $key){
                                                $query_1 = $model->find($query->id);

                                                $col = is_number($key_1) ? $value_1 : $key_1;
                                                if(isset($query_1->{$col.'__array'})){
                                                    foreach($query_1->{$col.'__array'} as $key_2 => $value_2) {
                                                        $count = count($images_exists);
                                                        $images_exists[$count] = json_encode($value_2);
                                                    }
                                                }

                                            }
                                        }
                                    }
                                // __IMAGE_ADD__

                                if (isset($images_exists) && !is_string($images_exists)) {
                                    foreach ($images_exists as $key_1 => $value_1) {
                                        if (is_string($value_1)) {
                                            $json_decode = json_decode($value_1);

                                            // URL
                                                if (isset($json_decode->url) && $json_decode->url) {
                                                    $files_save[$key_1] = ['url' => $json_decode->url];
                                                }
                                            // URL

                                            // FILE
                                                else if (isset($json_decode->file) && $json_decode->file) {
                                                    $files_save[$key_1] = ['file' => $json_decode->file];

                                                    // SIZE
                                                        if (isset($json_decode->size) && $json_decode->size) {
                                                            $files_save[$key_1]['size'] = $json_decode->size;
                                                        }
                                                    // SIZE

                                                    // TYPE
                                                        if (isset($json_decode->type) && $json_decode->type) {
                                                            $files_save[$key_1]['type'] = $json_decode->type;
                                                        }
                                                    // TYPE

                                                    // NAME
                                                        if (isset($json_decode->name) && $json_decode->name) {
                                                            $files_save[$key_1]['name'] = $json_decode->name;
                                                        }
                                                    // NAMETYPE
                                                }
                                            // FILE
                                        }
                                    }
                                }
                            // EXISTING

                        
                            // IMAGE / FILES
                                if (isset($value['request']) && $value['request']->hasFile($key)) {
                                    foreach($value['request']->file($key) as $key_1 => $value_1) {
                                        $type = __UploadService::treatment_type_ext($value_1);

                                        $response = __UploadService::save($model, $value_1, $query);
                                        if (isset($response['file']) && $response['file']) {
                                            // FILE
                                                $files_save[$key_1] = ['file' => $response['file']];
                                            // FILE

                                            // SIZE
                                                if (isset($response['size']) && $response['size']) {
                                                    $files_save[$key_1]['size'] = $response['size'];
                                                }
                                            // SIZE

                                            // TYPE
                                                $files_save[$key_1]['type'] = $type;
                                            // TYPE

                                            // NAME
                                                if (isset($response['name']) && $response['name']) {
                                                    $files_save[$key_1]['name'] = $response['name'];
                                                }
                                            // NAME
                                        }
                                    }
                                }
                            // IMAGE / FILES





                            // RETURN
                                if ($x_settings__name) {
                                    return $files_save;
                                }
                            // RETURN

                            // SAVE
                                else {
                                    $column = !empty($value['column']) ? $value['column'] : $key;
                                    $query->update([
                                        $column => json_encode($files_save)
                                    ]);
                                }
                            // SAVE
                        }

                    }
                }
                return [];
            }
        // FILE





        // FIELDS_STORE_UPDATE
            public static function fields_store_update(Request $request, object $menu_admin): array
            {
                $return = [];

                $fields = [];
                foreach ($menu_admin->input as $key => $value) {
                    if (isset($value->name) && $value->name && isset($value->check) && $value->check) {
                        $fields[] = $value;
                    }
                }


                // COLUMNS_SAVE
                    if (isset($menu_admin->columns_save) && $menu_admin->columns_save) {
                        $ex = explode(', ', $menu_admin->columns_save);
                        foreach ($ex as $key => $value) {
                            $ex_1 = explode('=', $value);
                            if (isset($ex_1[1])) {
                                $fields[] = (object)[
                                    'check' => 'true',
                                    'type' => $ex_1[0],
                                    'name' => $ex_1[1],
                                ];
                            }
                        }
                    }
                // COLUMNS_SAVE


                // USERS
                    // PERMISSIONS
                        if ($menu_admin->id == 6) {
                            $fields[] = (object)[
                                'check' => 'true',
                                'type' => 'checkbox',
                                'name' => 'permissions',
                            ];
                            $fields[] = (object)[
                                'check' => 'true',
                                'type' => 'select',
                                'name' => 'permissions_all',
                            ];

                        }
                    // PERMISSIONS
                // USERS


                // FIELDS
                    $model = new ($menu_admin->table__);
                    $fillable__all = $model->fillable__(true);
                    foreach ($fields as $key => $value) {
                        if (isset($request[$value->name])) {
                            if (!(isset($value->extra) && compare__('|->no_save', $value->extra))) {
                                $return[$value->name] = $request[$value->name];

                                // DATA
                                    if ($value->type == 'date') {
                                        $return[$value->name] = date__($request[$value->name], 'Y-m-d');
                                    }
                                    if ($value->type == 'datetime-local') {
                                        $return[$value->name] = date__($request[$value->name], 'Y-m-d H:i:s');
                                    }   
                                // DATA


                                // PRICE
                                    if ($value->type == 'price') {
                                        $return[$value->name] = price_($request[$value->name]);
                                    }
                                // PRICE


                                // INT
                                    if ($value->type == 'number') {
                                        $return[$value->name] = (int)$request[$value->name];
                                    }
                                // INT


                                // CHECK
                                    if ($value->type == 'checkbox') {
                                        if ($request[$value->name]) {
                                            $return[$value->name] = json_encode((object)sql_json_true($request[$value->name]));

                                        } else {
                                            $return[$value->name] = '';
                                        }
                                    }
                                // CHECK


                                // PASSWORD
                                    if ($value->type == 'password' && isset($request['password_confirmation'])) {
                                        $return['password_confirmation'] = $request['password_confirmation'];
                                    }
                                // PASSWORD


                                // NULL
                                    // if ( !(isset($request[$value->name]) && $request[$value->name] !== null && $request[$value->name] != 'null') ) {
                                    //     if ($value->type == 'categories' || $value->type == 'subcategories' || $value->type == 'number' || $value->type == 'select') {
                                    //         $return[$value->name] = 0;
                                    //     } else if($value->type == 'price') {
                                    //         $return[$value->name] = 0;
                                    //     } else if($value->type == 'date') {
                                    //         $return[$value->name] = null;
                                    //     } else if($value->type == 'datetime-local') {
                                    //         $return[$value->name] = null;
                                    //     } else if($value->type == 'file') {
                                    //         $return[$value->name] = null;
                                    //     } else if($value->type == 'checkbox') {
                                    //         $return[$value->name] = null;
                                    //     } else if($value->type == 'radio') {
                                    //         $return[$value->name] = null;
                                    //     } else if($value->type == 'json_fields') {
                                    //         $return[$value->name] = null;
                                    //     } else {
                                    //         $return[$value->name] = '';
                                    //     }
                                    // }
                                // NULL

                            }
                        }


                        // IMAGE / FILE
                            if ($value->type == 'image' || $value->type == 'file' || $value->name == 'image' || compare__('image_', $value->name)) {
                                $return[$value->name] = [ 'request' => $request, 'type' => $value->type ]; // -> $value['request']->hasFile($key);
                            }
                        // IMAGE / FILE


                        // TYPING
                            foreach($fillable__all as $column) {
                                if($column['name'] == $value->name && isset($return[$value->name])) {
                                    $field_value = $return[$value->name];
                                    $column_type = strtolower($column['type']);
                                    $is_nullable = ($column['null'] == 'YES');
                                    
                                    // INT VALIDATION
                                        if(strpos($column_type, 'int') !== false || strpos($column_type, 'bigint') !== false || strpos($column_type, 'tinyint') !== false) {
                                            if($field_value !== null && $field_value !== '') {
                                                if(!is_numeric($field_value)) {
                                                    $return[$value->name] = $field_value ? 1 : 0;
                                                    // $_GET['errors'][] = "Campo '{$value->name}' deve ser um número inteiro. Valor recebido: {$field_value}";

                                                } else {
                                                    $return[$value->name] = (int)$field_value;
                                                }
                                            } else if(!$is_nullable && ($field_value === null || $field_value === '')) {
                                                $_GET['errors'][] = "Campo '{$value->name}' não pode ser nulo";
                                            }
                                        }
                                    // INT VALIDATION


                                    // DECIMAL/FLOAT VALIDATION
                                        else if(strpos($column_type, 'decimal') !== false || strpos($column_type, 'float') !== false || strpos($column_type, 'double') !== false) {
                                            if($field_value !== null && $field_value !== '') {
                                                if(!is_numeric($field_value)) {
                                                    $_GET['errors'][] = "Campo '{$value->name}' deve ser um número. Valor recebido: {$field_value}";
                                                }
                                                $return[$value->name] = (float)$field_value;
                                            } else if(!$is_nullable && ($field_value === null || $field_value === '')) {
                                                $_GET['errors'][] = "Campo '{$value->name}' não pode ser nulo";
                                            }
                                        }
                                    // DECIMAL/FLOAT VALIDATION


                                    // DATE VALIDATION
                                        else if(strpos($column_type, 'date') !== false && strpos($column_type, 'datetime') === false) {
                                            if($field_value !== null && $field_value !== '') {
                                                // VERIFICA SE É UMA DATA VÁLIDA
                                                $date = \DateTime::createFromFormat('Y-m-d', $field_value);
                                                if(!$date || $date->format('Y-m-d') !== $field_value) {
                                                    // TENTA CONVERTER PARA FORMATO Y-M-D
                                                    $date = date__($field_value, 'Y-m-d');
                                                    if(!$date || $date == '1970-01-01') {
                                                        $_GET['errors'][] = "Campo '{$value->name}' deve ser uma data válida no formato Y-m-d. Valor recebido: {$field_value}";
                                                    }
                                                    $return[$value->name] = $date;
                                                }
                                            } else if(!$is_nullable && ($field_value === null || $field_value === '')) {
                                                $_GET['errors'][] = "Campo '{$value->name}' não pode ser nulo";
                                            }
                                        }
                                    // DATE VALIDATION


                                    // DATETIME VALIDATION
                                        else if(strpos($column_type, 'datetime') !== false || strpos($column_type, 'timestamp') !== false) {
                                            if($field_value !== null && $field_value !== '') {
                                                // VERIFICA SE É UMA DATETIME VÁLIDA
                                                $datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $field_value);
                                                if(!$datetime || $datetime->format('Y-m-d H:i:s') !== $field_value) {
                                                    // TENTA CONVERTER PARA FORMATO Y-M-D H:I:S
                                                    $datetime = date__($field_value, 'Y-m-d H:i:s');
                                                    if(!$datetime || $datetime == '1970-01-01 00:00:00') {
                                                        $_GET['errors'][] = "Campo '{$value->name}' deve ser uma data/hora válida no formato Y-m-d H:i:s. Valor recebido: {$field_value}";
                                                    }
                                                    $return[$value->name] = $datetime;
                                                }
                                            } else if(!$is_nullable && ($field_value === null || $field_value === '')) {
                                                $_GET['errors'][] = "Campo '{$value->name}' não pode ser nulo";
                                            }
                                        }
                                    // DATETIME VALIDATION


                                    // VARCHAR/TEXT VALIDATION (APENAS VERIFICA NULL)
                                        else if(strpos($column_type, 'varchar') !== false || strpos($column_type, 'text') !== false || strpos($column_type, 'char') !== false) {
                                            if(!$is_nullable && ($field_value === null || $field_value === '')) {
                                                $_GET['errors'][] = "Campo '{$value->name}' não pode ser vazio";
                                            }
                                            // VERIFICA TAMANHO MÁXIMO PARA VARCHAR
                                            if(strpos($column_type, 'varchar') !== false) {
                                                preg_match('/varchar\((\d+)\)/', $column_type, $matches);
                                                if(isset($matches[1])) {
                                                    $max_length = (int)$matches[1];
                                                    if(strlen($field_value) > $max_length) {
                                                        $_GET['errors'][] = "Campo '{$value->name}' excede o tamanho máximo de {$max_length} caracteres";
                                                    }
                                                }
                                            }
                                        }
                                    // VARCHAR/TEXT VALIDATION (APENAS VERIFICA NULL)


                                    // ENUM VALIDATION
                                        else if(strpos($column_type, 'enum') !== false) {
                                            if($field_value !== null && $field_value !== '') {
                                                // EXTRAI OS VALORES PERMITIDOS DO ENUM
                                                preg_match('/enum\((.*)\)/', $column_type, $matches);
                                                if(isset($matches[1])) {
                                                    $allowed_values = str_replace("'", "", $matches[1]);
                                                    $allowed_values = explode(',', $allowed_values);
                                                    if(!in_array($field_value, $allowed_values)) {
                                                        $_GET['errors'][] = "Campo '{$value->name}' deve ser um dos valores: " . implode(', ', $allowed_values);
                                                    }
                                                }
                                            } else if(!$is_nullable && ($field_value === null || $field_value === '')) {
                                                $_GET['errors'][] = "Campo '{$value->name}' não pode ser nulo";
                                            }
                                        }
                                    // ENUM VALIDATION
                                }
                            }
                        // TYPING

                    }
                // FIELDS


                // JSON_FIELDS
                    $json_fields = [];
                    foreach($request->input() as $key => $value) {
                        if (compare__('__json_fields__1', $key)) {
                            $json_fields[] = replace('__json_fields__1', '__json_fields__', $key);
                        }
                    }

                    foreach($json_fields as $key => $value) {
                        $temp = [];
                        for($i = 1; $i <= 200; $i++) {
                            if (isset($request[$value.$i])) {
                                $temp[$i] = trim($request[$value.$i]) ?? '';
                            }
                        }

                        $name = replace('__json_fields__', '', $value);
                        $return[$name] = json_encode($temp);
                    }
                // JSON_FIELDS


                // USERS
                    if ($menu_admin->id == 6) {
                        $return['users'] = request()->user()->id;
                    }
                // USERS


                // SAVE
                    if (isset($menu_admin->save) && $menu_admin->save) {
                        $save = eval('return '.$menu_admin->save.';');
                        foreach($save as $key => $value) {
                            $return[$key] = $value;
                        }
                    }
                // SAVE


                return $return;
            }
        // FIELDS_STORE_UPDATE
    // SAVE




















    // HTML
        // TABLE
            public static function html_table(Request $request, array $arr, Model $model, object $menu_admin): array
            {

                // RESOURCES (MENU ADMIN)
                    if(isset($menu_admin->resources->html_table)){
                        foreach($menu_admin->resources->html_table as $key_2 => $value_2) {
                            if(isset($value_2->function) && $value_2->function){
                                $function = replace('(', '', $value_2->function);
                                $function = replace(')', '', $function);
                                if(class_exists('Root\Resources\NEW__Resource')){
                                    if(method_exists('Root\Resources\NEW__Resource', $function)){
                                        $arr = NEW__Resource::{$function}($request, $arr, $model, $menu_admin);
                                    }
                                }
                            }
                        }
                    }
                // RESOURCES (MENU ADMIN)

                return $arr;
            }
        // TABLE










        // CREATE_EDIT
            public static function html_create_edit(Request $request, array $arr, Model $model, object $menu_admin): array
            {

                // RESOURCES (MENU ADMIN)
                    if(isset($menu_admin->resources->html_create_edit)){
                        foreach($menu_admin->resources->html_create_edit as $key_2 => $value_2) {
                            if(isset($value_2->function) && $value_2->function){
                                $function = replace('(', '', $value_2->function);
                                $function = replace(')', '', $function);
                                if(class_exists('Root\Resources\NEW__Resource')){
                                    if(method_exists('Root\Resources\NEW__Resource', $function)){
                                        $arr = NEW__Resource::{$function}($request, $arr, $model, $menu_admin);
                                    }
                                }
                            }
                        }
                    }
                // RESOURCES (MENU ADMIN)

                return $arr;
            }
        // CREATE_EDIT
    // HTML




















    // EXCEPTION
        static function exception(String $key): bool
        {
            if ($key != '_method' && $key != 'GET' && $key != '__THUMBS_FORMATS__') {
                return true;
            }
            return false;
        }
   // EXCEPTION




















    // TOSQL
        static function toSql($query, $bindings)
        {
            foreach ($bindings as $binding) {
                $value = is_numeric($binding) ? $binding : "'$binding'";
                $query = preg_replace('/\?/', $value, $query, 1);
            }
            return $query;
        }
    // TOSQL

}