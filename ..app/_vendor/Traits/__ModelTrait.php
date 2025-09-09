<?php

namespace Vendor\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Vendor\Models\ZText;
use Vendor\Resources\__Resource;

trait __ModelTrait
{

    // ADDITIONAL
        // // WITHOUTGLOBALSCOPE
            public function newQuery(): Builder
            {
                $builder = parent::newQuery();
                if (isset($_GET['__NO_QUERY__DASHBOARD__']) || isset($_GET['__NO_QUERY__'])) {
                    $builder->withoutGlobalScope('customizations');
                }

                return $builder;
            }
        // WITHOUTGLOBALSCOPE





        // FILLABLE__
            public static function fillable__($return_all = false): array
            {
                $instance = new static;
                if (empty($_GET['fillable__'][$instance->getTable()])) {
                    $_GET['fillable__'][$instance->getTable()] = Schema::getColumnListing($instance->getTable());

                    $table = $instance->getTable();
                    $columns = DB::select("SHOW COLUMNS FROM {$table}");
                    $result = [];
                    foreach ($columns as $column) {
                        $result[] = [
                            'name' => $column->Field,
                            'type' => $column->Type,
                            'null' => $column->Null,
                            'key' => $column->Key,
                            'default' => $column->Default,
                            'extra' => $column->Extra
                        ];
                    }
                    $_GET['fillable__all'][$instance->getTable()] = $result;
                }

                return $return_all ? $_GET['fillable__all'][$instance->getTable()] : $_GET['fillable__'][$instance->getTable()];
            }
        // FILLABLE__





        // FILLABLE__ALL
            // public static function fillable__all(): array
            // {
            //     $instance = new static();
            //     if (empty($_GET['fillable__all'][$instance->getTable()])) {
            //         $table = $instance->getTable();
            //         $columns = DB::select("SHOW COLUMNS FROM {$table}");
            //         $result = [];
            //         foreach ($columns as $column) {
            //             $result[] = [
            //                 'field' => $column->Field,
            //                 'type' => $column->Type,
            //                 'null' => $column->Null,
            //                 'key' => $column->Key,
            //                 'default' => $column->Default,
            //                 'extra' => $column->Extra
            //             ];
            //         }
            //         $_GET['fillable__all'][$instance->getTable()] = $result;
            //     }
            //     return $_GET['fillable__all'][$instance->getTable()];
            // }
        // FILLABLE__





        // NO -> DO NOT USE MODEL CUSTOMIZATIONS
            public static function scopeNo(Builder $query): Builder
            {
                $_GET['__NO_QUERY__ACTIVE__'] = 1;
                return $query;
            }
        // NO





        // FIND_ID
            public static function scopeFind_id(Builder $query, int $id): mixed
            {
                $_GET['__FIND_ID__'] = 1;
                $return = $query->select(['id'])->orderBy('id', 'ASC')->find($id);
                unset($_GET['__FIND_ID__']);

                return $return;
            }
        // FIND_ID





        // SELECT__
            public static function scopeSelect__(Builder $query, array $columns_all = []): Builder
            {
                return __Resource::select__($query, self::model(), get_class($query->getModel()), $columns_all);
            }
        // SELECT__




        // FILTER_JSON
            public static function scopefilter_json(Builder $query, string $column, string $value, string $val = ''): Builder
            {
                return $query->where(function($query)  use($column, $value, $val) {
                    $query->where($column, 'LIKE', '%"'.$value.'":"'.($val ? $val : 'true').'"%')
                    ->orWhere($column, 'LIKE', '%"'.$value.'":'.($val ? $val : 'true').'%');
                });
            }
            public static function scopefilter_sql(Builder $query, string $column, string $value, string $val = ''): Builder
            {
                return self::filter_json($query, $column, $value, $val);
            }
        // FILTER_JSON






        // SEARCH
            public static function scopeSearch(Builder $query, Request $request, object $menu_admin, array $columns_all = []): Builder
            {
                return __Resource::search($query, self::model(), $request, $menu_admin, $columns_all);
            }
        // SEARCH






        // EDITOR
            public static function scopeEditor(Builder $query, string $type = '0'): Builder
            {
                return __Resource::editor($query, self::model(), get_class($query->getModel()), $type);
            }
            public function editor_all()
            {
                if (isset($this->id) && isset($this->table__)) {
                    $table__ = MODEL__ROOT__OR__ALL($this->table__);
        
                    $ZText = ZText::where(function($query) use($table__) {
                        $query->where('table__', $table__)->orWhere('table__', replace('//', '/', $table__));
                    })->where('id__', $this->id)->get();
        
                    foreach ($ZText as $key => $value) {
                        $this->{$value->fields} = editor_base64($value->value);
                    }
                }
        
                return $this;
            }
        // EDITOR





        // ORDER
            public static function scopeOrder(Builder $query, Request $request, object $menu_admin): Builder
            {
                return __Resource::order($query, self::model(), $request, $menu_admin);
            }
        // ORDER
    // ADDITIONAL










    // METHODS
        // FIRST / FIND / GET / PAGINATE
            public static function get(mixed $item): void
            {
                if (!isset($_GET['no_fields'])) {
                    __Resource::fields($item, (new static));
                }
            }
        // FIRST / FIND / GET / PAGINATE





        // CREATE
            public static function create(array|Request $request = [], ?object $menu_admin = null): Model
            {
                $query = new static;

                $request__ = __Resource::save_validate($request, $query, $menu_admin);

                // TYPE
                    if (isset($menu_admin->type_items) && $menu_admin->type_items) {
                        $request__['type'] = $menu_admin->type_items;
                    }
                // TYPE

                // PASSWORD
                    if (!empty($request__['password']) && Hash::needsRehash($request__['password'])) {
                        $request__['password'] = Hash::make($request__['password']);
                    }
                // PASSWORD

                if (!isset($_GET['errors'])) {
                    $columns = Schema::getColumnListing($query->getTable());
                    $attributes_table = array_intersect_key($request__, array_flip($columns));

                    // SAVE
                        $query->fill($attributes_table)->save();
                    // SAVE

                    // REL / EDITOR
                        __Resource::save_rel_editor($query, $request__, $columns);
                    // REL / EDITOR

                    // IMAGES / FILES
                        __Resource::save_file($request, self::model(), $query, '', $menu_admin);
                    // IMAGES / FILES
                }

                return $query;
            }
        // CREATE





        // UPDATE
            public function update(array|Request $request = [], array $options = [], ?object $menu_admin = null): Model | null
            {
                $query = $this;

                // UPDATE
                    // FIND
                        if (count($query->toArray()) > 1) {
                            $query_id = $query->find_id($query->id);
                        } else {
                            $query_id = $query;
                        }
                        $query_return = $query;
                    // FIND

                    $request__ = __Resource::save_validate($request, $query_id, $menu_admin);

                    // PASSWORD
                        if (!empty($request__['password']) && Hash::needsRehash($request__['password'])) {
                            $request__['password'] = Hash::make($request__['password']);
                        }
                    // PASSWORD

                    if (!isset($_GET['errors'])) {
                        $columns = Schema::getColumnListing($query_id->getTable());
                        $attributes_table = array_intersect_key($request__, array_flip($columns));

                        // SAVE
                            $query_id->fill($attributes_table)->save($options);
                        // SAVE

                        // REL / EDITOR
                            __Resource::save_rel_editor($query_id, $request__, $columns);
                        // REL / EDITOR

                        // IMAGES / FILES
                            if(!is_string(self::model())){
                                __Resource::save_file($request, self::model(), $query_id, '', $menu_admin);
                            }
                        // IMAGES / FILES
                    }
                // UPDATE

                // READ
                    // $return = $query_return->find($query_id->id);
                    $return = $query_return;
                // READ

                return $return;
            }
        // UPDATE
    // METHODS

}
