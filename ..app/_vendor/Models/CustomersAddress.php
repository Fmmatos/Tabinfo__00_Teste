<?php

namespace Vendor\Models;

class CustomersAddress extends __Model
{
    public $table = 'customers_address';
    public $fillable__ = [];

    // RELATIONSHIPS
        public function customers()
        {
            return $this->belongsTo('Root\Models\Customers', 'customers', 'id');
        }
    // RELATIONSHIPS

}