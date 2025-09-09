<?php

namespace Vendor\Models;

class XSettings extends __Model
{
    public $table = 'x_settings';
    public $fillable__ = [];

    public static function get__($fields = null)
    {
        $query = self::select('fields', 'value');

        if ($fields) {
            $query = $query->whereIn('fields', $fields);
        }

        $query = $query->get();

        $return = (object)[];
        foreach ($query as $key => $value) {
            $k = $value->fields;

            if ($k == 'key_google') {
                $return->key_google = $value->value;
                //$return->kg = crip($value->value);

            } else {
                $return->$k = $value->value;
            }
        }

        return $return;
    }

}
