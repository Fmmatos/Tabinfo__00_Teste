<?php

namespace Vendor\Models;

class ProductsCategories extends __Model
{
    public $table = 'products_categories';
    public $fillable__ = [];
    public $active__ = 1;

    public $order_all__ = [
    ];


    // RELATIONSHIPS
        // SHOW ARRAY OF PARENT CATEGORY
            public function categories_array()
            {
                return $this->belongsTo('Root\Models\ProductsCategories', 'subcategories', 'id');
            }
        // SHOW ARRAY OF PARENT CATEGORY


        // SHOW ARRAY OF SUBCATEGORIES
            public function subcategories_array()
            {
                return $this->hasMany('Root\Models\ProductsCategories', 'subcategories', 'id');
            }
        // SHOW ARRAY OF SUBCATEGORIES
    // RELATIONSHIPS


    // RELATIONSHIPS

        // SHOW ARRAY OF PRODUCTS
            public function products()
            {
                return $this->hasMany('Root\Models\Products', 'categories', 'id');
            }
        // SHOW ARRAY OF PRODUCTS
    // RELATIONSHIPS

}