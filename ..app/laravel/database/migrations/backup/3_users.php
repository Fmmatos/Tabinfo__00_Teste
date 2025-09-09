<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class InsertUsersData extends Migration
{
    public function up()
    {
        DB::table('users')->insert([
            [
                'id' => 1,
                'active' => 1,
                'name' => 'Default',
                'email' => 'p@1',
                'phone' => null,
                'users' => null,
                'permissions' => '',
                'permissions_all' => 1,
                'password' => '$2y$10$gB9skygnSrvo9ZYehKJwCuAIdJTIMJ3AEKINgISns8OaL7hbD2h5a',
                'remember_token' => null,
                'verified_at' => null,
                'created_at' => null,
                'updated_at' => '2025-09-08 23:39:52',
            ],
            [
                'id' => 2,
                'active' => 1,
                'name' => 'Administrador',
                'email' => 'admin@admin',
                'phone' => '',
                'users' => null,
                'permissions' => '',
                'permissions_all' => 1,
                'password' => '$2y$10$WsBLHhbq3AMrnOpDMlVgTOUnr9gbZY4IEwbN9zHAUY5yXh10n3iBW',
                'remember_token' => null,
                'verified_at' => null,
                'created_at' => null,
                'updated_at' => '2025-08-08 15:25:12',
            ]
        ]);
    }

    public function down()
    {
        DB::table('users')->truncate();
    }
}
