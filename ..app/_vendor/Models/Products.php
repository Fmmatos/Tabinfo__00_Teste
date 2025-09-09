<?php

namespace Vendor\Models;

class Products extends __Model
{
    public $table = 'products';
    public $fillable__ = [];
    public $active__ = 1;

    public $order_all__ = [
    ];


    // RELATIONSHIPS
        public function brands()
        {
            return $this->belongsTo('Root\Models\ProductsBrands', 'brands', 'id');
        }

        public function categories()
        {
            return $this->belongsTo('Root\Models\ProductsCategories', 'categories', 'id');
        }
    // RELATIONSHIPS

}