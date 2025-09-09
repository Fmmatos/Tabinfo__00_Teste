<?php

namespace Vendor\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Vendor\Traits\__ModelTrait;

class Users_Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, __ModelTrait;

    public $table = 'users';

    protected $guarded = [];
    public $fillable = [];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public static function table(): string
    {
        $instance = new static();
        return $instance->table ?? (new static())->getTable();
    }

    public static function model(): string
    {
        return (new static())->getModel();
    }

}
