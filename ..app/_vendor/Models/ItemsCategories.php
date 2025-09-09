<?php

namespace Vendor\Models;

class ItemsCategories extends __Model
{
    public $table = 'items_categories';
    public $fillable__ = [];
    public $active__ = 1;

    public $order_all__ = [
    ];


    // RELATIONSHIPS
        // SHOW ARRAY OF PARENT CATEGORY
            public function categories_array()
            {
                return $this->belongsTo('Root\Models\ItemsCategories', 'subcategories', 'id');
            }
        // SHOW ARRAY OF PARENT CATEGORY


        // SHOW ARRAY OF SUBCATEGORIES
            public function subcategories_array()
            {
                return $this->hasMany('Root\Models\ItemsCategories', 'subcategories', 'id');
            }
        // SHOW ARRAY OF SUBCATEGORIES
    // RELATIONSHIPS

}