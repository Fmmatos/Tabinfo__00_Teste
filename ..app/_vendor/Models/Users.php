<?php

namespace Vendor\Models;

class Users extends __Model
{
    public $table = 'users';
    public $fillable__ = [];
    public $active__ = 1;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public $order_all__ = [
    ];

}