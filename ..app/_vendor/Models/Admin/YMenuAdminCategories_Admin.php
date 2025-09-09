<?php

namespace Vendor\Models\Admin;

use Vendor\Models\__Model;

class YMenuAdminCategories_Admin extends __Model
{
    public $table = 'y_menu_admin_categories';
    public $fillable__ = [];

    public function categories()
    {
        return $this->hasOne(YMenuAdmin_Admin::class, 'categories');
    }

    public static function categories_rel()
    {
        $YMenuAdmin_Admin = YMenuAdmin_Admin::select(['categories'])->where('active', 1)->where('id', '!=', 1);

        return self::whereIn('id', $YMenuAdmin_Admin);
    }


}
