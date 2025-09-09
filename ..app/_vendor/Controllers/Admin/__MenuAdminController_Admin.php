<?php

namespace Vendor\Controllers\Admin;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\In;
use Vendor\Models\Admin\YMenuAdmin_Admin;
use Vendor\Models\Admin\YMenuAdminCategories_Admin;
use Vendor\Services\__ActionsService;
use Vendor\Services\Admin\__MigrateService;

class __MenuAdminController_Admin
{

    // INDEX
        public function index(Request $request): JsonResponse
    {
            if ($request->user()->id == 1) {

                $menu_admin = YMenuAdmin_Admin::json(1, false);
                $menu_admin->info = ["excluir", "create", "edit", "order", "columns_active", "pdf", "excel", "delete", "clone"];
                $arr['OBJ']['menu_admin'] = $menu_admin;

                // MODEL
                    // TABLE
                        $arr['OBJ']['COLUMNS'] = __AdminController_Admin::columns($request, $menu_admin, 'table');
                        $arr['OBJ']['DATATABLE'] = YMenuAdmin_Admin::with('categories')->where('id', '!=', 1)->search($request, $menu_admin)->orderBy('order', 'ASC')->orderBy('name', 'ASC')->paginate(100)->toArray();
                    // TABLE


                    // CATEGORIES
                        foreach ($arr['OBJ']['DATATABLE']['data'] as $key => $value) {
                            $arr['OBJ']['DATATABLE']['data'][$key]['categories'] = $arr['OBJ']['DATATABLE']['data'][$key]['categories']['name'] ?? '';
                        }
                    // CATEGORIES


                    // TAGS
                        $arr['OBJ']['TAGS'] = [
                            (object)[
                                'id' => null,
                                'name' => 'Tudo',
                            ],
                            (object)[
                                'id' => '0',
                                'name' => 'Modulos',
                            ],
                            (object)[
                                'id' => '1',
                                'name' => 'Modulo Único',
                            ],
                            (object)[
                                'id' => '2',
                                'name' => 'Dashboard',
                            ],
                            (object)[
                                'id' => '99',
                                'name' => 'Outros',
                            ],
                            (object)[
                                'id' => '1000',
                                'name' => 'BD',
                            ],
                        ];
                    // TAGS
                // MODEL
            }

            $arr['status'] = 200;
            return json_encode__($arr, $request);
        }
    // INDEX










    // CREATE_EDIT
        public function create_edit(Request $request, int $id = 0, array $arr = [], bool $return_array = false): JsonResponse | array
        {
            if ($request->user()->id == 1) {
                $arr['OBJ']['menu_admin'] = YMenuAdmin_Admin::json(1, false);

                // EDIT
                    if ($id) {
                        $arr['OBJ']['VALUE'] = YMenuAdmin_Admin::json($id, false);

                        $table = $arr['OBJ']['VALUE']->table;
                        $arr['OBJ']['TABLE']['TABLE'] = $table;
                        $arr['OBJ']['TABLE']['COLUMNS'] = DB::select("SHOW COLUMNS FROM `$table`");
                        
                        // GET INDEXES
                            $indexes = DB::select("SHOW INDEX FROM `$table`");
                            
                            // GET ALL FOREIGN KEY NAMES
                                $foreignKeyNames = DB::select("
                                    SELECT DISTINCT CONSTRAINT_NAME 
                                    FROM information_schema.KEY_COLUMN_USAGE
                                    WHERE TABLE_SCHEMA = ?
                                    AND TABLE_NAME = ?
                                    AND REFERENCED_TABLE_NAME IS NOT NULL
                                ", [DB::getDatabaseName(), $table]);
                                
                                $fkNames = array_map(function($fk) {
                                    return $fk->CONSTRAINT_NAME;
                                }, $foreignKeyNames);
                            // GET ALL FOREIGN KEY NAMES
                            
                            $indexList = [];
                            foreach ($indexes as $index) {
                                // SKIP INDEXES THAT ARE FOREIGN KEYS
                                    if (in_array($index->Key_name, $fkNames)) {
                                        continue;
                                    }
                                // SKIP INDEXES THAT ARE FOREIGN KEYS
                                
                                if (!isset($indexList[$index->Key_name])) {
                                    $indexList[$index->Key_name] = [
                                        'name' => $index->Key_name,
                                        'unique' => !$index->Non_unique,
                                        'type' => $index->Index_type,
                                        'columns' => []
                                    ];
                                }
                                $indexList[$index->Key_name]['columns'][] = $index->Column_name;
                            }
                            $arr['OBJ']['TABLE']['INDEXES'] = array_values($indexList);
                        // GET INDEXES
                        
                        // GET FOREIGN KEYS
                            $foreignKeys = DB::select("
                                SELECT 
                                    kcu.CONSTRAINT_NAME,
                                    kcu.COLUMN_NAME,
                                    kcu.REFERENCED_TABLE_NAME,
                                    kcu.REFERENCED_COLUMN_NAME,
                                    rc.DELETE_RULE,
                                    rc.UPDATE_RULE
                                FROM information_schema.KEY_COLUMN_USAGE kcu
                                LEFT JOIN information_schema.REFERENTIAL_CONSTRAINTS rc 
                                    ON kcu.CONSTRAINT_NAME = rc.CONSTRAINT_NAME 
                                    AND kcu.TABLE_SCHEMA = rc.CONSTRAINT_SCHEMA
                                WHERE kcu.TABLE_SCHEMA = ?
                                AND kcu.TABLE_NAME = ?
                                AND kcu.REFERENCED_TABLE_NAME IS NOT NULL
                            ", [DB::getDatabaseName(), $table]);
                            $arr['OBJ']['TABLE']['FOREIGN_KEYS'] = $foreignKeys;
                        // GET FOREIGN KEYS
                    }
                // EDIT

                // CREATE
                    else{
                        $arr['OBJ']['VALUE'] = (object)[
                            'type' => 0,
                            'info' => [ "create", "edit", "excel", "pdf", "columns_active" ],
                            'columns_active_n' => "10",
                            'input' => (object)[
                                (object)[
                                    'check' => "true",
                                    'type' => "text",
                                    'wr' => "wr6",
                                    'align' => "left",
                                    'label' => "Nome",
                                    'name' => "name",
                                    'tags' => "required",
                                ],
                                (object)[
                                    'check' => "true",
                                    'type' => "file",
                                    'wr' => "wr6",
                                    'align' => "right",
                                    'label' => "Foto",
                                    'name' => "image",
                                ],
                                (object)[
                                    'type' => "text",
                                    'wr' => "wr6",
                                    'align' => "left",
                                ],
                                (object)[
                                    'type' => "text",
                                    'wr' => "wr6",
                                    'align' => "left",
                                ],
                                'txt_meta' => (object)[
                                    'check' => "true",
                                    'type' => "textarea",
                                    'wr' => "wr12",
                                    'align' => "left",
                                    'label' => "Descrição",
                                    'name' => "description",
                                ],
                                'txt' => (object)[
                                    'check' => "true",
                                    'type' => "editor",
                                    'wr' => "wr12",
                                    'align' => "left",
                                    'label' => "Descrição Completa",
                                    'name' => "editor",
                                ],
                            ]
                        ];
                    }
                // CREATE


                $arr['OBJ']['QUERY']['menu_admin_all'] = YMenuAdmin_Admin::orderBy('name', 'ASC')->get();
                foreach($arr['OBJ']['QUERY']['menu_admin_all'] as $key => $value) {
                    $arr['OBJ']['QUERY']['menu_admin_all'][$key]->table__1 = name__function_to_table($value->table__);
                }

                $arr['OBJ']['QUERY']['menu_admin_categoriries'] = YMenuAdminCategories_Admin::orderBy('name', 'ASC')->get();
            }

            if ($return_array) {
                return $arr;
                
            } else {
                $arr['status'] = 200;
                return json_encode__($arr, $request);
            }
        }
    // CREATE_EDIT










    // STORE
        public function store(Request $request): JsonResponse
    {
            $arr = [];
            if ($request->user()->id == 1) {
                $arr['OBJ']['menu_admin'] = YMenuAdmin_Admin::json(1, false);

                DB::beginTransaction();
                try {

                    $request__ = self::fields_store_update($request, $arr['OBJ']['menu_admin']);
                    $response = YMenuAdmin_Admin::create($request__);

                    $arr['status'] = 200;
                    DB::commit();

                } catch (\Throwable $th) {
                    DB::rollBack();
                    $arr = errors__($th, $arr);
                }
                
                if (isset($arr['status']) && isset($response->id)) {
                    self::json__write($request, $response->id);
                }
            }

            return json_encode__($arr, $request);
        }
    // STORE










    // UPDATE
        public function update(Request $request, int $id): JsonResponse
    {
            $arr = [];
            if ($request->user()->id == 1) {
                $arr['OBJ']['menu_admin'] = YMenuAdmin_Admin::json(1, false);

                DB::beginTransaction();
                try {

                    $request__ = self::fields_store_update($request, $arr['OBJ']['menu_admin']);
                    YMenuAdmin_Admin::find_id($id)->update($request__);

                    $arr['status'] = 200;
                    DB::commit();

                } catch (\Throwable $th) {
                    DB::rollBack();
                    $arr = errors__($th, $arr);
                }

                if (isset($arr['status'])) {
                    self::json__write($request, $id);
                }
            }

            return json_encode__($arr, $request);
        }
    // UPDATE










    // DELETE
        public function delete(Request $request, int $id = 0): JsonResponse
    {
            $arr = [];
            if ($request->user()->id == 1) {

                DB::beginTransaction();
                try{
                    // DELETE
                        if ($id) {
                            YMenuAdmin_Admin::where('id', $id)->delete();
        
                        // } else if($request['sel_all_all']) {
                        //     YMenuAdmin_Admin::orderBy('id', 'ASC')->delete();

                        } else{
                            $ids = [];
                            foreach ($request['sel'] as $key => $value) {
                                if ($value) {
                                    $ids[] = $key;
                                }
                            }
                            if ($ids) {
                                YMenuAdmin_Admin::whereIn('id', $ids)->delete();
                            }
                        }
                    // DELETE

                    $arr['status'] = 200;
                    DB::commit();

                } catch (\Throwable $th) {
                    DB::rollBack();
                    $arr = errors__($th, $arr);
                }
            }

            return json_encode__($arr, $request);
        }
    // DELETE




















    // ACTIONS
        public function actions(Request $request, string $type): JsonResponse | array
    {
            if ($request->user()->id == 1) {
                if ($type == 'order') {
                    return __ActionsService::order($request, 1);

                } else{
                    return __ActionsService::actions($request, 1, $type, self::class);
                }
            }
            return [];
        }
    // ACTIONS




















    // AUTH
        public function auth_permissions(Request $request, object $menu_admin, string $action): bool
    {
            return true;
        }
    // AUTH




















    // FIELDS_STORE_UPDATE
        public static function fields_store_update(Request $request, object $menu_admin): array
    {
            $request__ = [];
            foreach ($menu_admin->fillable__() as $key => $value) {
                if (isset($request[$value]) && $value != 'active' && $value != 'order') {
                    $request__[$value] = $request[$value];
                }
            }
            return $request__;
        }
    // FIELDS_STORE_UPDATE










    // TREATMENT_PRE_SAVE
        public static function treatment_pre_save(Request $request): array
        {
            $return = $request->all();
            $table = name__function_to_table($return['table__']);

            // FIELDS
                foreach($return['input'] as $key => $value) {
                    if(isset($value['name'])){
                        if(compare__fim('__', $value['name'])){
                            $return['input'][$key]['extra'] = $return['input'][$key]['extra'] ?? '';

                            if(!compare__('|->no_search', $return['input'][$key]['extra']) || !compare__('|->no_save', $return['input'][$key]['extra'])){
                                $return['input'][$key]['extra'] = replace('|->no_search', '', $return['input'][$key]['extra']);
                                $return['input'][$key]['extra'] = replace('|->no_save', '', $return['input'][$key]['extra']);

                                $return['input'][$key]['extra'] = trim($return['input'][$key]['extra'].' |->no_search |->no_save');
                            }
                        }
                    }
                }
            // FIELDS


            // SORTABLE
                if ($return['sortable']) {
                    $sortable = json_decode($return['sortable'], true);
                    if ($sortable) {
                        $input = [];
                        foreach ($sortable as $key => $value) {
                            if (is_number($value)) {
                                if (isset($return['input'][$value])) {
                                    $input[] = $return['input'][$value];
                                }
                            }
                        }
                        $input['txt_meta'] = $return['input']['txt_meta'];
                        $input['txt'] = $return['input']['txt'];

                        $return['input'] = $input;
                    }
                }
            // SORTABLE


            // CREATE MODEL
                // ROOT
                    if ( (!file_exists(DIR_F.'/_root/Models/'.$return['table__'].'.php') && !file_exists(DIR_F.'/_vendor/Models/'.$return['table__'].'.php')) || (isset($return['save_button']) && $return['save_button'] == 'force_create_model_again')) {
                        // CHECK IF TABLE ENDS WITH _categories TO USE APPROPRIATE BASE MODEL
                            $CATEGORIES = (substr($table, -11) === '_categories');
                            $model_itens = $CATEGORIES ? DIR_F.'/_vendor/Models/ItemsCategories.php' : DIR_F.'/_vendor/Models/Items.php';
                            $model_new = DIR_F.'/_root/Models/'.$return['table__'].'.php';
                        // CHECK IF TABLE ENDS WITH _categories TO USE APPROPRIATE BASE MODEL
                        
                        // CREATE BACKUP IF FILE EXISTS
                            if (file_exists($model_new)) {
                                $backup_dir = DIR_F.'/_root/Models/old/';
                                
                                // Create backup directory if it doesn't exist
                                if (!is_dir($backup_dir)) {
                                    mkdir($backup_dir, 0777, true);
                                }
                                
                                // Generate backup filename with date format
                                $date = date('Y_m_d_H_i_s');
                                $backup_filename = $date . '__' . $return['table__'] . '.php';
                                $backup_path = $backup_dir . $backup_filename;
                                
                                // Copy file to backup
                                copy($model_new, $backup_path);
                            }
                        // CREATE BACKUP IF FILE EXISTS
                        
                        $file_content = file_get_contents($model_itens);

                        // REPLACE MODEL NAME BASED ON WHICH BASE MODEL WE'RE USING
                            $base_model_name = $CATEGORIES ? 'ItemsCategories' : 'Items';
                            $base_table_name = $CATEGORIES ? 'items_categories' : 'items';
                            
                            $new_content = replace($base_model_name, $return['table__'], $file_content);
                            $new_content = replace("'".$base_table_name."'", "'".$table."'", $new_content);
                            $new_content = replace("namespace Vendor\Models;", "namespace Root\Models;\n\nuse Vendor\Models\__Model;", $new_content);
                        // REPLACE MODEL NAME BASED ON WHICH BASE MODEL WE'RE USING

                        // ADD RELATIONSHIPS BASED ON FOREIGN KEYS
                            $relationships = '';
                            $addedRelationships = []; // Track added relationships to avoid duplicates


                            // IF IT'S A CATEGORIES TABLE, MARK THAT RELATIONSHIPS ALREADY EXIST
                                if ($CATEGORIES) {
                                    // Categories table already has categories_array() and subcategories_array() from base model ItemsCategories
                                    // We just need to replace the model references in the content
                                    $new_content = replace("'Root\\Models\\ItemsCategories'", "'Root\\Models\\{$return['table__']}'", $new_content);
                                    
                                    // Mark these relationships as already added so we don't duplicate them
                                    $addedRelationships[] = 'categories_array';
                                    $addedRelationships[] = 'subcategories_array';
                                }
                            // IF IT'S A CATEGORIES TABLE, MARK THAT RELATIONSHIPS ALREADY EXIST

                            // GET FOREIGN KEYS FOR THIS TABLE (BELONGS TO)
                                $foreignKeys = DB::select("
                                    SELECT 
                                        COLUMN_NAME,
                                        REFERENCED_TABLE_NAME,
                                        REFERENCED_COLUMN_NAME
                                    FROM information_schema.KEY_COLUMN_USAGE
                                    WHERE TABLE_SCHEMA = ?
                                    AND TABLE_NAME = ?
                                    AND REFERENCED_TABLE_NAME IS NOT NULL
                                ", [DB::getDatabaseName(), $table]);
                                
                                if (!empty($foreignKeys)) {
                                    if (empty($relationships)) {
                                        $relationships .= "\n\n    // RELATIONSHIPS";
                                    }
                                    
                                    foreach ($foreignKeys as $fk) {
                                        $column = $fk->COLUMN_NAME;
                                        $referencedTable = $fk->REFERENCED_TABLE_NAME;
                                        $referencedColumn = $fk->REFERENCED_COLUMN_NAME;
                                        
                                        // SKIP SUBCATEGORIES COLUMN FOR CATEGORIES TABLES (ALREADY HANDLED ABOVE)
                                            if ($CATEGORIES && $column === 'subcategories') {
                                                continue;
                                            }
                                        // SKIP SUBCATEGORIES COLUMN FOR CATEGORIES TABLES (ALREADY HANDLED ABOVE)
                                        
                                        // CONVERT TABLE NAME TO MODEL NAME (SNAKE_CASE TO PASCALCASE)
                                            $modelName = str_replace(' ', '', ucwords(str_replace('_', ' ', $referencedTable)));
                                        // CONVERT TABLE NAME TO MODEL NAME (SNAKE_CASE TO PASCALCASE)
                                        
                                        // CREATE RELATIONSHIP NAME
                                            $relationshipName = $column;
                                            
                                            // If column ends with _id, remove it for the relationship name
                                            if (substr($relationshipName, -3) === '_id') {
                                                $relationshipName = substr($relationshipName, 0, -3);
                                            }
                                        // CREATE RELATIONSHIP NAME
                                        
                                        // CHECK IF THIS RELATIONSHIP ALREADY EXISTS
                                            if (!in_array($relationshipName, $addedRelationships)) {
                                                $addedRelationships[] = $relationshipName;
                                                
                                                $relationships .= "\n        public function {$relationshipName}()";
                                                $relationships .= "\n        {";
                                                $relationships .= "\n            return \$this->belongsTo('Root\\Models\\{$modelName}', '{$column}', '{$referencedColumn}');";
                                                $relationships .= "\n        }\n";
                                            }
                                        // CHECK IF THIS RELATIONSHIP ALREADY EXISTS
                                    }
                                }
                            // GET FOREIGN KEYS FOR THIS TABLE (BELONGS TO)
                            
                            // GET TABLES THAT REFERENCE THIS TABLE (HAS MANY)
                                $referencingTables = DB::select("
                                    SELECT DISTINCT
                                        TABLE_NAME,
                                        COLUMN_NAME,
                                        REFERENCED_COLUMN_NAME
                                    FROM information_schema.KEY_COLUMN_USAGE
                                    WHERE TABLE_SCHEMA = ?
                                    AND REFERENCED_TABLE_NAME = ?
                                    AND TABLE_NAME != ?
                                ", [DB::getDatabaseName(), $table, $table]); // Exclude self-references
                                
                                if (!empty($referencingTables)) {
                                    if (empty($relationships)) {
                                        $relationships .= "\n\n    // RELATIONSHIPS";
                                    }
                                    
                                    foreach ($referencingTables as $ref) {
                                        $referencingTable = $ref->TABLE_NAME;
                                        $referencingColumn = $ref->COLUMN_NAME;
                                        $localColumn = $ref->REFERENCED_COLUMN_NAME;
                                        
                                        // CONVERT TABLE NAME TO MODEL NAME
                                            $modelName = str_replace(' ', '', ucwords(str_replace('_', ' ', $referencingTable)));
                                        // CONVERT TABLE NAME TO MODEL NAME
                                        
                                        // CREATE RELATIONSHIP NAME
                                            if ($CATEGORIES) {
                                                // If categories table ends with _categories and referencing table is same without _categories
                                                // e.g., customers_categories referenced by customers table
                                                $table_without_categories = substr($table, 0, -11); // remove '_categories'
                                                if ($referencingTable === $table_without_categories) {
                                                    $relationshipName = $referencingTable;
                                                } else {
                                                    $relationshipName = $referencingTable;
                                                }
                                            } else {
                                                $relationshipName = $referencingTable;
                                            }
                                        // CREATE RELATIONSHIP NAME
                                        
                                        // CHECK IF THIS RELATIONSHIP ALREADY EXISTS
                                            if (!in_array($relationshipName, $addedRelationships)) {
                                                $addedRelationships[] = $relationshipName;
                                                
                                                // ADD PROPER COMMENT FOR CATEGORIES RELATIONSHIP
                                                    if ($CATEGORIES && $referencingTable === substr($table, 0, -11)) {
                                                        $relationships .= "\n\n        // SHOW ARRAY OF " . strtoupper($referencingTable);
                                                    }
                                                // ADD PROPER COMMENT FOR CATEGORIES RELATIONSHIP
                                                
                                                $relationships .= "\n            public function {$relationshipName}()";
                                                $relationships .= "\n            {";
                                                $relationships .= "\n                return \$this->hasMany('Root\\Models\\{$modelName}', '{$referencingColumn}', '{$localColumn}');";
                                                $relationships .= "\n            }";
                                                
                                                if ($CATEGORIES && $referencingTable === substr($table, 0, -11)) {
                                                    $relationships .= "\n        // SHOW ARRAY OF " . strtoupper($referencingTable);
                                                }
                                                $relationships .= "\n";
                                            }
                                        // CHECK IF THIS RELATIONSHIP ALREADY EXISTS
                                    }
                                }
                            // GET TABLES THAT REFERENCE THIS TABLE (HAS MANY)
                            
                            // GET SELF REFERENCES (BOTH BELONGS TO AND HAS MANY)
                                $selfReferences = DB::select("
                                    SELECT 
                                        COLUMN_NAME,
                                        REFERENCED_COLUMN_NAME
                                    FROM information_schema.KEY_COLUMN_USAGE
                                    WHERE TABLE_SCHEMA = ?
                                    AND TABLE_NAME = ?
                                    AND REFERENCED_TABLE_NAME = ?
                                ", [DB::getDatabaseName(), $table, $table]);
                                
                                if (!empty($selfReferences)) {
                                    if (empty($relationships)) {
                                        $relationships .= "\n\n    // RELATIONSHIPS";
                                    }
                                    
                                    foreach ($selfReferences as $ref) {
                                        $column = $ref->COLUMN_NAME;
                                        $referencedColumn = $ref->REFERENCED_COLUMN_NAME;
                                        
                                        // SKIP SUBCATEGORIES COLUMN FOR CATEGORIES TABLES (ALREADY HANDLED ABOVE)
                                            if ($CATEGORIES && $column === 'subcategories') {
                                                continue;
                                            }
                                        // SKIP SUBCATEGORIES COLUMN FOR CATEGORIES TABLES (ALREADY HANDLED ABOVE)
                                        
                                        // MODEL NAME IS THE SAME AS CURRENT
                                            $modelName = $return['table__'];
                                        // MODEL NAME IS THE SAME AS CURRENT
                                        
                                        // FOR SELF-REFERENCE BELONGSTO, USE COLUMN NAME + "_PARENT"
                                            $belongsToName = $column . '_parent';
                                            if (!in_array($belongsToName, $addedRelationships)) {
                                                $addedRelationships[] = $belongsToName;
                                                
                                                $relationships .= "\n        public function {$belongsToName}()";
                                                $relationships .= "\n        {";
                                                $relationships .= "\n            return \$this->belongsTo('Root\\Models\\{$modelName}', '{$column}', '{$referencedColumn}');";
                                                $relationships .= "\n        }\n";
                                            }
                                        // FOR SELF-REFERENCE BELONGSTO, USE COLUMN NAME + "_PARENT"
                                        
                                        // FOR SELF-REFERENCE HASMANY, USE COLUMN NAME + "_CHILDREN"
                                            $hasManyName = $column . '_children';
                                            if (!in_array($hasManyName, $addedRelationships)) {
                                                $addedRelationships[] = $hasManyName;
                                                
                                                $relationships .= "\n        public function {$hasManyName}()";
                                                $relationships .= "\n        {";
                                                $relationships .= "\n            return \$this->hasMany('Root\\Models\\{$modelName}', '{$column}', '{$referencedColumn}');";
                                                $relationships .= "\n        }\n";
                                            }
                                        // FOR SELF-REFERENCE HASMANY, USE COLUMN NAME + "_CHILDREN"
                                    }
                                }
                            // GET SELF REFERENCES (BOTH BELONGS TO AND HAS MANY)
                            
                            if (!empty($relationships)) {
                                $relationships .= "    // RELATIONSHIPS\n";
                            }
                            
                            // INSERT RELATIONSHIPS BEFORE CLOSING BRACE
                                if (!empty($relationships)) {
                                    $new_content = str_replace("\n}", $relationships . "\n}", $new_content);
                                }
                            // INSERT RELATIONSHIPS BEFORE CLOSING BRACE
                        // ADD RELATIONSHIPS BASED ON FOREIGN KEYS
                        
                        file_put_contents($model_new, $new_content);
                    }
                // ROOT
            // CREATE MODEL


            // CREATE TABLE
                if (compare__ini('api_', $table)) {
                    $table = '_'.$table;
                }

                if (!Schema::hasTable($table)) {
                    if(compare__ini('_categories', $table)){
                        Schema::create($table, function (Blueprint $t) {
                            $t->bigIncrements('id');
                            $t->integer('active')->default(1);
                            $t->text('name');
                            $t->longText('image')->nullable();
                            $t->integer('type')->default(0);
                            $t->unsignedBigInteger('subcategories')->nullable();
                            $t->integer('order')->default(999);
                            $t->timestamps();
                        });

                    } else {
                        Schema::create($table, function (Blueprint $t) {
                            $t->bigIncrements('id');
                            $t->integer('active')->default(1);
                            $t->text('name');
                            $t->longText('image')->nullable();
                            $t->string('type', 45)->nullable();
                            $t->integer('order')->default(999);
                            $t->timestamps();
                        });
                    }
                }
            // CREATE TABLE


            // CREATE COLUMNS
                function columns_add($table, $value, $columns) {
                    if (!Schema::hasColumn($table, $columns) && $table != 'x_settings') {
                        Schema::table($table, function (Blueprint $blueprint) use($table, $value, $columns) {
                            if ( // EXCEPTIONS
                                !(isset($value['name']) && compare__fim('__', $value['name']))
                                && !(isset($value['extra']) && compare__('|->no_save', $value['extra']))
                                && !(isset($value['extra']) && compare__('|->items', $value['extra']))
                            ) {
                                $created = 0;

                                // CREATE_COLUMN
                                    if (isset($value['create_column']) && $value['create_column']) {
                                        if ($value['create_column'] == 'bigint') {
                                            $blueprint->bigInteger($columns)->nullable();
                                            $created = 1;


                                        } else if($value['create_column'] == 'bigint_key') {
                                            $blueprint->unsignedBigInteger($columns)->nullable();
                                            
                                            if (isset($value['options']) && $value['options']) {
                                                $referenced_menu = YMenuAdmin_Admin::find($value['options']);
                                                if ($referenced_menu && $referenced_menu->table) {
                                                    $referenced_table = $referenced_menu->table;
                                                    $blueprint->foreign($columns, $table.'__'.$columns.'__foreign')->references('id')->on($referenced_table)->onDelete('set null')->onUpdate('cascade');
                                                }
                                            }
                                            $created = 1;

                                        } else if($value['create_column'] == 'int') {
                                            $blueprint->integer($columns)->default(0);
                                            $created = 1;

                                        } else if($value['create_column'] == 'date') {
                                            $blueprint->date($columns)->nullable();
                                            $created = 1;

                                        } else if($value['create_column'] == 'datetime') {
                                            $blueprint->dateTime($columns)->nullable();
                                            $created = 1;

                                        } else if($value['create_column'] == 'month') {
                                            $blueprint->string($columns, 7)->nullable();
                                            $created = 1;

                                        } else if($value['create_column'] == 'decimal') {
                                            $blueprint->decimal($columns, 10, 2)->default(0);
                                            $created = 1;

                                        } else if($value['create_column'] == 'varchar_50') {
                                            $blueprint->string($columns, 50)->nullable();
                                            $created = 1;

                                        } else if($value['create_column'] == 'varchar') {
                                            $blueprint->string($columns, 255)->nullable();
                                            $created = 1;

                                        } else if($value['create_column'] == 'text_long') {
                                            $blueprint->longText($columns)->nullable();
                                            $created = 1;

                                        } else if($value['create_column'] == 'json') {
                                            $blueprint->longText($columns)->nullable();
                                            $created = 1;

                                        } else {
                                            $blueprint->string($columns, 255)->nullable();
                                            $created = 1;
                                        }
                                    }
                                // CREATE_COLUMN


                                // TYPE
                                    else if(isset($value['type'])) {
                                        if ($value['type'] == 'categories' || $value['type'] == 'subcategories' || $value['type'] == 'number' || $value['type'] == 'select') {
                                            $blueprint->integer($columns)->nullable();
                                            $created = 1;

                                        } else if($value['type'] == 'price') {
                                            $blueprint->decimal($columns, 10, 2)->default(0);
                                            $created = 1;

                                        } else if($value['type'] == 'date') {
                                            $blueprint->date($columns)->nullable();
                                            $created = 1;

                                        } else if($value['type'] == 'datetime-local') {
                                            $blueprint->dateTime($columns)->nullable();
                                            $created = 1;

                                        } else if($value['type'] == 'color') {
                                            $blueprint->string($columns, 10)->nullable();
                                            $created = 1;

                                        } else if($value['type'] == 'file' || $value['type'] == 'checkbox') {
                                            $blueprint->longText($columns)->nullable();
                                            $created = 1;

                                        } else if($value['type'] == 'textarea' && $value['type'] == 'address') {
                                            $blueprint->text($columns)->nullable();
                                            $created = 1;

                                        } else if($value['name'] == 'cpf') {
                                            $blueprint->string($columns, 14)->nullable();
                                            $created = 1;

                                        } else if($value['name'] == 'cnpj' || $value['name'] == 'cpf_cnpj') {
                                            $blueprint->string($columns, 18)->nullable();
                                            $created = 1;

                                        } else if ($value['type'] == 'phone') {
                                            $blueprint->string($columns, 15)->nullable();
                                            $created = 1;

                                        } else if($value['type'] == 'zipcode') {
                                            $blueprint->string($columns, 10)->nullable();
                                            $created = 1;

                                        } else if($value['type'] == 'uf') {
                                            $blueprint->string($columns, 2)->nullable();
                                            $created = 1;

                                        } else if($value['type'] == 'json_fields') {
                                            $blueprint->longText($columns)->nullable();
                                            $created = 1;

                                        } else {
                                            $blueprint->string($columns, 255)->nullable();
                                            $created = 1;
                                        }
                                    }
                                // TYPE


                                // ELSE
                                    if (!$created) {
                                        $blueprint->string($columns)->nullable();
                                    }
                                // ELSE
                            
                            }
                        });
                    }
                }


                // COLUNS
                    // INPUT
                        foreach ($request['input'] as $key => $value) {
                            if (isset($value['check']) && ($value['check'] === true || $value['check'] === 'true') && isset($value['name'])) {
                                $columns = explode('->', $value['name'])[0];

                                if ($value['type'] != 'info' && $value['type'] != 'editor') {
                                    if ((isset($value['create_column']) && $value['create_column']) || ($value['type'] != 'query' && $value['type'] != 'search')) { // column / query / search so passa se tiver create_column
                                        columns_add($table, $value, $columns);
                                    }
                                }
                            }
                        }
                    // INPUT


                    // INFO
                        foreach (json_decode($request['info']) as $key => $columns) {
                            if (compare__('star_', $columns)) {
                                $value = [
                                    'create_column' => 'int',
                                ];
                                columns_add($table, $value, $columns);
                            }
                        }
                    // INFO
                // COLUNS
            // CREATE COLUMNS
            

            // CREATE_MIGRATION / UPDATE_MIGRATION
                if(LOCALHOST){
                    __MigrateService::generateMigrationForTable($table);
                    __MigrateService::backupDaily();
                    __MigrateService::registerMigrationInDatabase($table);
                }
            // CREATE_MIGRATION / UPDATE_MIGRATION


            if (isset($return['GET'])) unset($return['GET']);
            if (isset($return['sortable'])) unset($return['sortable']);

            return $return;
        }
    // TREATMENT_PRE_SAVE










    // JSON__WRITE
        public static function json__write(Request $request, int $id): void
    {
            $dir__ = DIR_F.'/_root/z_Json/menu_admin';
            $file__ = $dir__.'/'.$id.'.json';

            $request__ = self::treatment_pre_save($request);

            if (!file_exists($dir__)) { mkdir($dir__, 0777, true); }


            // Y_MENU_ADMIN
                $YMenuAdmin_Admin = YMenuAdmin_Admin::find($id)->toArray();
                foreach ($YMenuAdmin_Admin as $key => $value) {
                    $request__[$key] = $value;
                }

                $request__['info'] = json_decode($request__['info'], true);

                if (isset($request__['input'])) {
                    if (ADMIN__JSON__MENU_ADMIN_BASE64) {
                        $request__['input'] = base64_encode(json_encode($request__['input']));
                    }
                }

                // TABLE
                    $table = name__function_to_table($request__['table__']);
                    $request__['table'] = $table;
                // TABLE
            // Y_MENU_ADMIN


            // RESOUCES
                $resources = [];

                foreach($request__ as $key => $value) {
                    if(compare__('resources__', $key)){
                        $resources[replace('resources__', '', $key)] = array_values($value);
                        unset($request__[$key]);
                    }
                }
                $request__['resources'] = $resources;        
            // RESOUCES

            // UNSET
                unset($request__['GET']);
                unset($request__['__GLOBAL__']);
                unset($request__['__THUMBS_FORMATS__']);
                unset($request__['_method']);

                unset($request__['action']);
                unset($request__['columns_categories_n']);
            // UNSET


            $file = fopen($file__, 'w');
            fwrite($file, json_encode($request__));
            fclose($file);
        }
    // JSON__WRITE










    // BD
        // COLUMN_UPDATE
            public function column_update(Request $request): JsonResponse
            {
                $arr = [];
                if ($request->user()->id == 1) {
                    try {
                        // GET REQUEST DATA
                            $table = $request->input('table');
                            $column = $request->input('column');
                            $field = $request->input('field');
                            $value = $request->input('value');
                        // GET REQUEST DATA


                        // VALIDATE TABLE EXISTS
                            if (!Schema::hasTable($table)) {
                                throw new \Exception("Table '$table' does not exist");
                            }
                        // VALIDATE TABLE EXISTS


                        // VALIDATE COLUMN EXISTS
                            if (!Schema::hasColumn($table, $column)) {
                                throw new \Exception("Column '$column' does not exist in table '$table'");
                            }
                        // VALIDATE COLUMN EXISTS


                        // UPDATE COLUMN BASED ON FIELD
                            switch ($field) {
                                case 'Field':
                                    // RENAME COLUMN
                                        if ($column !== $value) {
                                            DB::statement("ALTER TABLE `$table` CHANGE `$column` `$value` " . DB::select("SHOW COLUMNS FROM `$table` WHERE Field = ?", [$column])[0]->Type);
                                        }
                                    // RENAME COLUMN
                                    break;

                                case 'Type':
                                    // CHANGE COLUMN TYPE
                                        // GET CURRENT COLUMN INFO
                                            $columnInfo = DB::select("SHOW COLUMNS FROM `$table` WHERE Field = ?", [$column])[0];
                                            $isNullable = $columnInfo->Null === 'YES' ? 'NULL' : 'NOT NULL';
                                            $defaultValue = $columnInfo->Default;
                                            $extra = $columnInfo->Extra;
                                        // GET CURRENT COLUMN INFO

                                        // BUILD DEFAULT CLAUSE
                                            $defaultClause = '';
                                            if ($defaultValue !== null) {
                                                $defaultClause = " DEFAULT '$defaultValue'";
                                            } else if ($isNullable === 'NULL') {
                                                $defaultClause = " DEFAULT NULL";
                                            }
                                        // BUILD DEFAULT CLAUSE

                                        // BUILD EXTRA CLAUSE
                                            $extraClause = '';
                                            if (strpos($extra, 'auto_increment') !== false) {
                                                $extraClause = ' AUTO_INCREMENT';
                                            }
                                        // BUILD EXTRA CLAUSE

                                        // ALTER TABLE
                                            DB::statement("ALTER TABLE `$table` MODIFY `$column` $value $isNullable$defaultClause$extraClause");
                                        // ALTER TABLE
                                    // CHANGE COLUMN TYPE
                                    break;

                                case 'Null':
                                    // CHANGE NULLABLE
                                        $columnDefinition = DB::select("SHOW COLUMNS FROM `$table` WHERE Field = ?", [$column])[0];
                                        
                                        // GET CURRENT TYPE
                                            $currentType = $columnDefinition->Type;
                                            $defaultValue = $columnDefinition->Default;
                                            $extra = $columnDefinition->Extra;
                                        // GET CURRENT TYPE

                                        // BUILD DEFAULT CLAUSE
                                            $defaultClause = '';
                                            if ($defaultValue !== null) {
                                                $defaultClause = " DEFAULT '$defaultValue'";
                                            }
                                        // BUILD DEFAULT CLAUSE

                                        // BUILD EXTRA CLAUSE
                                            $extraClause = '';
                                            if (strpos($extra, 'auto_increment') !== false) {
                                                $extraClause = ' AUTO_INCREMENT';
                                            }
                                        // BUILD EXTRA CLAUSE

                                        // RECREATE COLUMN WITH NULLABLE CHANGE
                                            if ($value === 'YES') {
                                                DB::statement("ALTER TABLE `$table` MODIFY `$column` $currentType NULL$defaultClause$extraClause");
                                            } else {
                                                DB::statement("ALTER TABLE `$table` MODIFY `$column` $currentType NOT NULL$defaultClause$extraClause");
                                            }
                                        // RECREATE COLUMN WITH NULLABLE CHANGE
                                    // CHANGE NULLABLE
                                    break;

                                case 'Default':
                                    // CHANGE DEFAULT VALUE
                                        $columnDefinition = DB::select("SHOW COLUMNS FROM `$table` WHERE Field = ?", [$column])[0];
                                        $currentType = $columnDefinition->Type;
                                        $isNullable = $columnDefinition->Null === 'YES' ? 'NULL' : 'NOT NULL';
                                        $extra = $columnDefinition->Extra;
                                        
                                        // BUILD EXTRA CLAUSE
                                            $extraClause = '';
                                            if (strpos($extra, 'auto_increment') !== false) {
                                                $extraClause = ' AUTO_INCREMENT';
                                            }
                                        // BUILD EXTRA CLAUSE
                                        
                                        if ($value === '' || $value === null || strtoupper($value) === 'NULL') {
                                            DB::statement("ALTER TABLE `$table` MODIFY `$column` $currentType $isNullable DEFAULT NULL$extraClause");
                                        } else {
                                            DB::statement("ALTER TABLE `$table` MODIFY `$column` $currentType $isNullable DEFAULT '$value'$extraClause");
                                        }
                                    // CHANGE DEFAULT VALUE
                                    break;

                                case 'Key':
                                    // CHANGE KEY/INDEX
                                        if ($value === 'PRI') {
                                            // ADD PRIMARY KEY (CAREFUL - REMOVE EXISTING FIRST)
                                                DB::statement("ALTER TABLE `$table` ADD PRIMARY KEY (`$column`)");
                                            // ADD PRIMARY KEY
                                        } else if ($value === 'UNI') {
                                            // ADD UNIQUE INDEX
                                                DB::statement("ALTER TABLE `$table` ADD UNIQUE INDEX `{$column}_unique` (`$column`)");
                                            // ADD UNIQUE INDEX
                                        } else if ($value === 'MUL') {
                                            // ADD REGULAR INDEX
                                                DB::statement("ALTER TABLE `$table` ADD INDEX `{$column}_index` (`$column`)");
                                            // ADD REGULAR INDEX
                                        } else {
                                            // REMOVE INDEXES
                                                $indexes = DB::select("SHOW INDEX FROM `$table` WHERE Column_name = ?", [$column]);
                                                foreach ($indexes as $index) {
                                                    if ($index->Key_name !== 'PRIMARY') {
                                                        DB::statement("ALTER TABLE `$table` DROP INDEX `{$index->Key_name}`");
                                                    }
                                                }
                                            // REMOVE INDEXES
                                        }
                                    // CHANGE KEY/INDEX
                                    break;

                                case 'Extra':
                                    // CHANGE EXTRA (AUTO_INCREMENT)
                                        if ($value === 'auto_increment') {
                                            DB::statement("ALTER TABLE `$table` MODIFY `$column` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT");
                                        } else {
                                            // REMOVE AUTO_INCREMENT
                                                $columnDefinition = DB::select("SHOW COLUMNS FROM `$table` WHERE Field = ?", [$column])[0];
                                                $currentType = $columnDefinition->Type;
                                                $isNullable = $columnDefinition->Null === 'YES' ? 'NULL' : 'NOT NULL';
                                                DB::statement("ALTER TABLE `$table` MODIFY `$column` $currentType $isNullable");
                                            // REMOVE AUTO_INCREMENT
                                        }
                                    // CHANGE EXTRA
                                    break;
                            }
                        // UPDATE COLUMN BASED ON FIELD

                        $arr['status'] = 200;
                        $arr['alert'] = 1;

                    } catch (\Throwable $th) {
                        $arr = errors__($th, $arr);
                    }
                }

                return json_encode__($arr, $request);
            }
        // COLUMN_UPDATE


        // COLUMN_REORDER
            public function column_reorder(Request $request): JsonResponse
            {
                $arr = [];
                if ($request->user()->id == 1) {
                    try {
                        // GET REQUEST DATA
                            $table = $request->input('table');
                            $orderInput = $request->input('order');
                            
                            // DECODE JSON IF STRING
                                if (is_string($orderInput)) {
                                    $order = json_decode($orderInput, true);
                                } else {
                                    $order = $orderInput;
                                }
                            // DECODE JSON IF STRING
                        // GET REQUEST DATA


                        // VALIDATE TABLE EXISTS
                            if (!Schema::hasTable($table)) {
                                throw new \Exception("Table '$table' does not exist");
                            }
                        // VALIDATE TABLE EXISTS


                        // GET CURRENT COLUMNS INFO
                            $currentColumns = DB::select("SHOW COLUMNS FROM `$table`");
                            $columnsInfo = [];
                            foreach ($currentColumns as $col) {
                                $columnsInfo[$col->Field] = $col;
                            }
                        // GET CURRENT COLUMNS INFO


                        // REORDER COLUMNS IN DATABASE
                            $previousColumn = null;
                            foreach ($order as $columnName) {
                                // VALIDATE COLUMN EXISTS
                                    if (!isset($columnsInfo[$columnName])) {
                                        continue;
                                    }
                                // VALIDATE COLUMN EXISTS

                                // GET COLUMN DETAILS
                                    $columnInfo = $columnsInfo[$columnName];
                                    $type = $columnInfo->Type;
                                    $null = $columnInfo->Null === 'YES' ? 'NULL' : 'NOT NULL';
                                    $default = '';
                                    if ($columnInfo->Default !== null) {
                                        $default = " DEFAULT '{$columnInfo->Default}'";
                                    } else if ($columnInfo->Null === 'YES') {
                                        $default = " DEFAULT NULL";
                                    }
                                    $extra = '';
                                    if (strpos($columnInfo->Extra, 'auto_increment') !== false) {
                                        $extra = ' AUTO_INCREMENT';
                                    }
                                // GET COLUMN DETAILS

                                // ALTER COLUMN POSITION
                                    if ($previousColumn === null) {
                                        // FIRST COLUMN
                                            DB::statement("ALTER TABLE `$table` MODIFY COLUMN `$columnName` $type $null$default$extra FIRST");
                                        // FIRST COLUMN
                                    } else {
                                        // AFTER PREVIOUS COLUMN
                                            DB::statement("ALTER TABLE `$table` MODIFY COLUMN `$columnName` $type $null$default$extra AFTER `$previousColumn`");
                                        // AFTER PREVIOUS COLUMN
                                    }
                                // ALTER COLUMN POSITION

                                $previousColumn = $columnName;
                            }
                        // REORDER COLUMNS IN DATABASE

                        $arr['status'] = 200;
                        $arr['alert'] = 1;
                        $arr['msg'] = 'Ordem das colunas atualizada no banco de dados!';

                    } catch (\Throwable $th) {
                        $arr = errors__($th, $arr);
                    }
                }

                return json_encode__($arr, $request);
            }
        // COLUMN_REORDER


        // INDEXES CREATE
            public function indexes_create(Request $request): JsonResponse
            {
                $arr = [];
                if ($request->user()->id == 1) {
                    try {
                        $id = $request->id;
                        $table = $request->table;
                        $name = $request->name;
                        $type = $request->type;
                        $columns = $request->columns;
                        
                        // CHECK IF COLUMNS IS STRING AND DECODE
                            if (is_string($columns)) {
                                $columns = json_decode($columns, true);
                            }
                        // CHECK IF COLUMNS IS STRING AND DECODE
                        
                        if (!$table || !$name || !$columns || !is_array($columns) || empty($columns)) {
                            throw new \Exception('Dados inválidos - Tabela, nome ou colunas não fornecidos');
                        }
                        
                        // BUILD INDEX QUERY
                            $columnsList = implode('`, `', $columns);
                            
                            if ($type === 'UNIQUE') {
                                DB::statement("ALTER TABLE `$table` ADD UNIQUE INDEX `$name` (`$columnsList`)");
                            } else {
                                DB::statement("ALTER TABLE `$table` ADD INDEX `$name` (`$columnsList`)");
                            }
                        // BUILD INDEX QUERY

                        $arr = $arr = $this->create_edit($request, $id, $arr, true);
                        
                        $arr['status'] = 200;
                        $arr['alert'] = 1;
                        $arr['msg'] = 'Índice criado com sucesso!';
                        
                    } catch (\Throwable $th) {
                        $arr = errors__($th, $arr);
                    }
                }
                
                return json_encode__($arr, $request);
            }
        // INDEXES CREATE


        // INDEXES RENAME
            public function indexes_rename(Request $request): JsonResponse
            {
                $arr = [];
                if ($request->user()->id == 1) {
                    try {
                        $id = $request->id;
                        $table = $request->table;
                        $oldName = $request->oldName;
                        $newName = $request->newName;
                        
                        if (!$table || !$oldName || !$newName) {
                            throw new \Exception('Dados inválidos');
                        }
                        
                        // GET INDEX DETAILS
                            $indexes = DB::select("SHOW INDEX FROM `$table` WHERE Key_name = ?", [$oldName]);
                            
                            if (empty($indexes)) {
                                throw new \Exception('Índice não encontrado');
                            }
                            
                            // CHECK IF UNIQUE
                                $isUnique = !$indexes[0]->Non_unique;
                            // CHECK IF UNIQUE
                            
                            // GET COLUMNS
                                $columns = [];
                                foreach ($indexes as $index) {
                                    $columns[] = $index->Column_name;
                                }
                                $columnsList = implode('`, `', $columns);
                            // GET COLUMNS
                        // GET INDEX DETAILS
                        
                        // DROP OLD INDEX
                            DB::statement("ALTER TABLE `$table` DROP INDEX `$oldName`");
                        // DROP OLD INDEX
                        
                        // CREATE NEW INDEX
                            if ($isUnique) {
                                DB::statement("ALTER TABLE `$table` ADD UNIQUE INDEX `$newName` (`$columnsList`)");
                            } else {
                                DB::statement("ALTER TABLE `$table` ADD INDEX `$newName` (`$columnsList`)");
                            }
                        // CREATE NEW INDEX
                        
                        $arr = $this->create_edit($request, $id, $arr, true);
                        
                        $arr['status'] = 200;
                        $arr['alert'] = 1;
                        $arr['msg'] = 'Índice renomeado com sucesso!';
                        
                    } catch (\Throwable $th) {
                        $arr = errors__($th, $arr);
                    }
                }
                
                return json_encode__($arr, $request);
            }
        // INDEXES RENAME


        // INDEXES DELETE
            public function indexes_delete(Request $request): JsonResponse
            {
                $arr = [];
                if ($request->user()->id == 1) {
                    // try {
                        $id = $request->id;
                        $table = $request->table;
                        $name = $request->name;
                        
                        if (!$table || !$name) {
                            throw new \Exception('Dados inválidos');
                        }
                        
                        // CHECK IF INDEX IS PART OF FOREIGN KEY
                            $foreignKeys = DB::select("
                                SELECT CONSTRAINT_NAME 
                                FROM information_schema.KEY_COLUMN_USAGE 
                                WHERE TABLE_SCHEMA = ? 
                                AND TABLE_NAME = ? 
                                AND CONSTRAINT_NAME = ?
                                AND REFERENCED_TABLE_NAME IS NOT NULL
                            ", [DB::getDatabaseName(), $table, $name]);
                            
                            if (!empty($foreignKeys)) {
                                // DROP FOREIGN KEY FIRST
                                    DB::statement("ALTER TABLE `$table` DROP FOREIGN KEY `$name`");
                                // DROP FOREIGN KEY FIRST
                            }
                        // CHECK IF INDEX IS PART OF FOREIGN KEY
                        
                        // DROP INDEX
                            DB::statement("ALTER TABLE `$table` DROP INDEX `$name`");
                        // DROP INDEX
                        
                        $arr = $this->create_edit($request, $id, $arr, true);
                        
                        $arr['status'] = 200;
                        $arr['alert'] = 1;
                        $arr['msg'] = 'Índice deletado com sucesso!';
                        
                    // } catch (\Throwable $th) {
                    //     $arr = errors__($th, $arr);
                    // }
                }
                
                return json_encode__($arr, $request);
            }
        // INDEXES DELETE


        // FOREIGN KEY RENAME
            public function foreign_key_rename(Request $request): JsonResponse
            {
                $arr = [];
                if ($request->user()->id == 1) {
                    try {
                        $id = $request->id;
                        $table = $request->table;
                        $oldName = $request->oldName;
                        $newName = $request->newName;
                        
                        if (!$table || !$oldName || !$newName) {
                            throw new \Exception('Dados inválidos');
                        }
                        
                        // GET FOREIGN KEY DETAILS
                            $foreignKey = DB::select("
                                SELECT 
                                    COLUMN_NAME,
                                    REFERENCED_TABLE_NAME,
                                    REFERENCED_COLUMN_NAME
                                FROM information_schema.KEY_COLUMN_USAGE
                                WHERE TABLE_SCHEMA = ?
                                AND TABLE_NAME = ?
                                AND CONSTRAINT_NAME = ?
                                AND REFERENCED_TABLE_NAME IS NOT NULL
                            ", [DB::getDatabaseName(), $table, $oldName]);
                            
                            if (empty($foreignKey)) {
                                throw new \Exception('Foreign key não encontrada');
                            }
                            
                            $column = $foreignKey[0]->COLUMN_NAME;
                            $referencedTable = $foreignKey[0]->REFERENCED_TABLE_NAME;
                            $referencedColumn = $foreignKey[0]->REFERENCED_COLUMN_NAME;
                        // GET FOREIGN KEY DETAILS
                        
                        // DROP OLD FOREIGN KEY
                            DB::statement("ALTER TABLE `$table` DROP FOREIGN KEY `$oldName`");
                        // DROP OLD FOREIGN KEY
                        
                        // CREATE NEW FOREIGN KEY
                            DB::statement("ALTER TABLE `$table` ADD CONSTRAINT `$newName` FOREIGN KEY (`$column`) REFERENCES `$referencedTable`(`$referencedColumn`) ON DELETE SET NULL ON UPDATE CASCADE");
                        // CREATE NEW FOREIGN KEY
                        
                        $arr = $this->create_edit($request, $id, $arr, true);
                        
                        $arr['status'] = 200;
                        $arr['alert'] = 1;
                        $arr['msg'] = 'Foreign key renomeada com sucesso!';
                        
                    } catch (\Throwable $th) {
                        $arr = errors__($th, $arr);
                    }
                }
                
                return json_encode__($arr, $request);
            }
        // FOREIGN KEY RENAME


        // FOREIGN KEY UPDATE
            public function foreign_key_update(Request $request): JsonResponse
            {
                $arr = [];
                if ($request->user()->id == 1) {
                    try {
                        $id = $request->id;
                        $table = $request->table;
                        $name = $request->name;
                        
                        if (!$table || !$name) {
                            throw new \Exception('Dados inválidos');
                        }
                        
                        // GET CURRENT FOREIGN KEY DETAILS
                            $foreignKey = DB::select("
                                SELECT 
                                    COLUMN_NAME,
                                    REFERENCED_TABLE_NAME,
                                    REFERENCED_COLUMN_NAME
                                FROM information_schema.KEY_COLUMN_USAGE
                                WHERE TABLE_SCHEMA = ?
                                AND TABLE_NAME = ?
                                AND CONSTRAINT_NAME = ?
                                AND REFERENCED_TABLE_NAME IS NOT NULL
                            ", [DB::getDatabaseName(), $table, $name]);
                            
                            if (empty($foreignKey)) {
                                throw new \Exception('Foreign key não encontrada');
                            }
                            
                            $currentColumn = $foreignKey[0]->COLUMN_NAME;
                            $currentReferencedTable = $foreignKey[0]->REFERENCED_TABLE_NAME;
                            $currentReferencedColumn = $foreignKey[0]->REFERENCED_COLUMN_NAME;
                        // GET CURRENT FOREIGN KEY DETAILS
                        
                        // GET CURRENT ON DELETE AND ON UPDATE RULES
                            $currentRules = DB::select("
                                SELECT 
                                    DELETE_RULE,
                                    UPDATE_RULE
                                FROM information_schema.REFERENTIAL_CONSTRAINTS
                                WHERE CONSTRAINT_SCHEMA = ?
                                AND TABLE_NAME = ?
                                AND CONSTRAINT_NAME = ?
                            ", [DB::getDatabaseName(), $table, $name]);
                            
                            $currentOnDelete = $currentRules[0]->DELETE_RULE ?? 'RESTRICT';
                            $currentOnUpdate = $currentRules[0]->UPDATE_RULE ?? 'RESTRICT';
                        // GET CURRENT ON DELETE AND ON UPDATE RULES
                        
                        // SET NEW VALUES OR KEEP CURRENT
                            $column = $request->column ?? $currentColumn;
                            $referencedTable = $request->referenced_table ?? $currentReferencedTable;
                            $referencedColumn = $request->referenced_column ?? $currentReferencedColumn;
                            $onDelete = $request->on_delete ?? $currentOnDelete;
                            $onUpdate = $request->on_update ?? $currentOnUpdate;
                        // SET NEW VALUES OR KEEP CURRENT
                        
                        // DROP OLD FOREIGN KEY
                            DB::statement("ALTER TABLE `$table` DROP FOREIGN KEY `$name`");
                        // DROP OLD FOREIGN KEY
                        
                        // CREATE NEW FOREIGN KEY WITH UPDATED VALUES
                            DB::statement("ALTER TABLE `$table` ADD CONSTRAINT `$name` FOREIGN KEY (`$column`) REFERENCES `$referencedTable`(`$referencedColumn`) ON DELETE $onDelete ON UPDATE $onUpdate");
                        // CREATE NEW FOREIGN KEY WITH UPDATED VALUES
                        
                        $arr = $this->create_edit($request, $id, $arr, true);
                        
                        $arr['status'] = 200;
                        $arr['alert'] = 1;
                        $arr['msg'] = 'Foreign key atualizada com sucesso!';
                        
                    } catch (\Throwable $th) {
                        $arr = errors__($th, $arr);
                    }
                }
                
                return json_encode__($arr, $request);
            }
        // FOREIGN KEY UPDATE


        // FOREIGN KEY CREATE
            public function foreign_key_create(Request $request): JsonResponse
            {
                $arr = [];
                if ($request->user()->id == 1) {
                    try {
                        $id = $request->id;
                        $table = $request->table;
                        $name = $request->name;
                        $column = $request->column;
                        $referencedTable = $request->referenced_table;
                        $referencedColumn = $request->referenced_column;
                        $onDelete = $request->on_delete ?? 'SET NULL';
                        $onUpdate = $request->on_update ?? 'CASCADE';
                        
                        if (!$table || !$name || !$column || !$referencedTable || !$referencedColumn) {
                            throw new \Exception('Dados inválidos');
                        }
                        
                        // CREATE FOREIGN KEY
                            DB::statement("ALTER TABLE `$table` ADD CONSTRAINT `$name` FOREIGN KEY (`$column`) REFERENCES `$referencedTable`(`$referencedColumn`) ON DELETE $onDelete ON UPDATE $onUpdate");
                        // CREATE FOREIGN KEY
                        
                        $arr = $this->create_edit($request, $id, $arr, true);
                        
                        $arr['status'] = 200;
                        $arr['alert'] = 1;
                        $arr['msg'] = 'Foreign key criada com sucesso!';
                        
                    } catch (\Throwable $th) {
                        $arr = errors__($th, $arr);
                    }
                }
                
                return json_encode__($arr, $request);
            }
        // FOREIGN KEY CREATE


        // FOREIGN KEY DELETE
            public function foreign_key_delete(Request $request): JsonResponse
            {
                $arr = [];
                if ($request->user()->id == 1) {
                    try {
                        $id = $request->id;
                        $table = $request->table;
                        $name = $request->name;
                        
                        if (!$table || !$name) {
                            throw new \Exception('Dados inválidos');
                        }
                        
                        // DROP FOREIGN KEY
                            DB::statement("ALTER TABLE `$table` DROP FOREIGN KEY `$name`");
                        // DROP FOREIGN KEY
                        
                        // TRY TO DROP THE ASSOCIATED INDEX
                            try {
                                // Check if index exists with same name
                                $indexExists = DB::select("
                                    SELECT COUNT(*) as count
                                    FROM information_schema.STATISTICS
                                    WHERE TABLE_SCHEMA = ?
                                    AND TABLE_NAME = ?
                                    AND INDEX_NAME = ?
                                ", [DB::getDatabaseName(), $table, $name]);
                                
                                if ($indexExists[0]->count > 0) {
                                    DB::statement("ALTER TABLE `$table` DROP INDEX `$name`");
                                }
                            } catch (\Exception $e) {
                                // Index might not exist or might be used by another constraint
                                // Continue without error
                            }
                        // TRY TO DROP THE ASSOCIATED INDEX
                        
                        $arr = $this->create_edit($request, $id, $arr, true);
                        
                        $arr['status'] = 200;
                        $arr['alert'] = 1;
                        $arr['msg'] = 'Foreign key deletada com sucesso!';
                        
                    } catch (\Throwable $th) {
                        $arr = errors__($th, $arr);
                    }
                }
                
                return json_encode__($arr, $request);
            }
        // FOREIGN KEY DELETE
    // BD

}