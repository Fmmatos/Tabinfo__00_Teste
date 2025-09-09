<?php

namespace Vendor\Models;

use Laravel\Sanctum\HasApiTokens;

class Customers extends __Model
{
    use HasApiTokens;

    public $table = 'customers';
    public $fillable__ = [];
    public $active__ = 1;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public $order_all__ = [
        // TYPE
            'customers' => [
                'id' => 'ASC',
            ],
        // TYPE
    ];


    // RELATIONSHIPS
        public function categories()
        {
            return $this->belongsTo('Root\Models\CustomersCategories', 'categories', 'id');
        }

        public function customers()
        {
            return $this->belongsTo('Root\Models\Customers', 'customers', 'id');
        }

        public function customers_parent()
        {
            return $this->belongsTo('Root\Models\Customers', 'customers', 'id');
        }

        public function customers_children()
        {
            return $this->hasMany('Root\Models\Customers', 'customers', 'id');
        }

        public function customers_address()
        {
            return $this->hasMany('Vendor\Models\CustomersAddress', 'customers', 'id');
        }
    // RELATIONSHIPS

}
