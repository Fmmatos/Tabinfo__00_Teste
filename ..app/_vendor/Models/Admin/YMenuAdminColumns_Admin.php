<?php

namespace Vendor\Models\Admin;

use Vendor\Models\__Model;

class YMenuAdminColumns_Admin extends __Model
{
    public $table = 'y_menu_admin_columns';
    public $fillable__ = [];

    public function menu_admin()
    {
        return $this->belongsTo(YMenuAdmin_Admin::class, 'id', 'y_menu_admin');
    }

}
