<?php

namespace Vendor\Models;

class Orders extends __Model
{
    public $table = 'orders';
    public $fillable__ = [];

    public $order_all__ = [
    ];


    // RELATIONSHIPS
        public function customers()
        {
            return $this->belongsTo('Root\Models\Customers', 'customers', 'id');
        }

        public function orders_events()
        {
            return $this->belongsTo('Root\Models\OrdersEvents', 'orders_events', 'id');
        }

        public function status()
        {
            return $this->belongsTo('Root\Models\OrdersStatus', 'status', 'id');
        }

        public function status_users()
        {
            return $this->belongsTo('Root\Models\Users', 'status_users', 'id');
        }
    // RELATIONSHIPS

}