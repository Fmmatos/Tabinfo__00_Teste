<?php

namespace Vendor\Models\Admin;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Vendor\Models\__Model;

class YMenuAdmin_Admin extends __Model
{
    public $table = 'y_menu_admin';
    public $fillable__ = [];


    // GET
        public function __get($key)
        {
            $value = parent::__get($key);

            if ($key == 'table__') {
                if (!compare__('\\Models\\', $value)) {
                    return MODEL__ROOT__OR__ALL($value);
                }
            }

            return $value;
        }
    // GET





    // RELATIONSHIPS
        public function categories(): BelongsTo
        {
            return $this->belongsTo(YMenuAdminCategories_Admin::class, 'categories', 'id');
        }

        public function menu_admin_columns(): HasOne
        {
            return $this->hasOne(YMenuAdminColumns_Admin::class, 'y_menu_admin');
        }
    // RELATIONSHIPS





    // FUNCTIONS
        public static function json(int $module, bool $class = true): mixed
        {
            $item = self::where('id', $module)->first();
            if ($item) {
                $item_db = $item->toArray();

                if (file_exists(DIR_F.'/_root/z_Json/menu_admin/'.$item->id.'.json')) {
                    $file = file(DIR_F.'/_root/z_Json/menu_admin/'.$item->id.'.json');
                    if (isset($file[0])) {
                        $item = json_decode($file[0]);
                        if (isset($item->input)) {
                            if (is_base64($item->input)) {
                                $item->input = json_decode(base64_decode($item->input));
                            } else {
                                $item->input = $item->input;
                            }
                        }
                    }
                }


                // COLUMNS BD OVERWRITE JSON
                    foreach ($item_db as $key => $value) {
                        if ($key == 'id') {
                            $item->$key = $value;
                        }
                    }
                // COLUMNS BD OVERWRITE JSON


                // TABLE__1
                    $item->table__1 = name__function_to_table($item->table__);
                // TABLE__1


                // SEARCH_DATE_FIELD
                    if(isset($item->info) && in_array('search_top_date', $item->info)){
                        $item->search_columns_date = $item->search_columns_date ?? 'created_at'; 
                        
                        // ARRAY
                            $search_date_array = [];
                            $search_columns_date = explode(',', $item->search_columns_date);
                            foreach($search_columns_date as $key => $value) {
                                $search_date_array[] = trim($value);
                            }
                        // ARRAY


                        // FIELDS: NAME
                            $search_date_field = [];
                            if(in_array('created_at', $search_date_array)){
                                $search_date_field['created_at'] = 'Data de Criação';
                            }
                            if(in_array('updated_at', $search_date_array)){
                                $search_date_field['updated_at'] = 'Data de Atualização';
                            }
                            foreach($item->input as $key => $value) {
                                if(isset($value->name) && compare__('date', $value->name)){
                                    if(in_array($value->name, $search_date_array)){
                                        $search_date_field[$value->name] = $value->label;
                                    }
                                }
                            }
                            $item->search_date_field = $search_date_field;
                        // FIELDS: NAME


                        // SEARCH_COLUMNS_DATE CURRENT
                            $search_columns_date_current = '';
                            foreach($search_date_field as $key => $value) {
                                if($item->search_columns_date == $key){
                                    $search_columns_date_current = $key;
                                }
                            }
                            if(!$search_columns_date_current){
                                $search_columns_date_current = key__($search_date_field);
                            }
                            $item->search_columns_date_current = $search_columns_date_current ?? 'created_at';
                        // SEARCH_COLUMNS_DATE CURRENT
                    }
                // SEARCH_DATE_FIELD

                if ($class === true) {
                    $item->table__ = MODEL__ROOT__OR__ALL($item->table__);
                }
            }

            return $item;
        }
    // FUNCTIONS

}
