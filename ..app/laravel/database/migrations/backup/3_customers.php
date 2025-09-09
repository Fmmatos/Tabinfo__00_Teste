<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class InsertCustomersData extends Migration
{
    public function up()
    {
        DB::table('customers')->insert([
            [
                'id' => 1,
                'active' => 1,
                'approved' => 1,
                'name' => 'Teste AAA1',
                'image' => '[{"file":"customer-1_1111988146.jpg","size":15007,"type":"jpg","name":"01.jpg"}]',
                'type' => 'customers',
                'customers' => null,
                'email' => 'p@1',
                'cpf' => '996.833.853-20',
                'cnpj' => null,
                'ie' => null,
                'phone' => '(99) 99999-9999',
                'birth' => '1990-01-01',
                'sexo' => 1,
                'price' => 0.00,
                'url' => null,
                'code' => null,
                'last_acess' => '2025-09-09 00:16:12',
                'order' => 999,
                'password' => '$2y$10$gB9skygnSrvo9ZYehKJwCuAIdJTIMJ3AEKINgISns8OaL7hbD2h5a',
                'remember_token' => null,
                'verified_at' => '2025-09-09 00:17:00',
                'created_at' => '2025-02-18 21:45:14',
                'updated_at' => '2025-09-09 00:16:12',
            ],
            [
                'id' => 2,
                'active' => 1,
                'approved' => 1,
                'name' => 'Teste 002',
                'image' => '[{"file":"customer-2_teste-002_955443661.jpg","size":15006,"type":"jpg","name":"02.jpg"}]',
                'type' => 'customers',
                'customers' => null,
                'email' => 't@2',
                'cpf' => '772.244.886-07',
                'cnpj' => null,
                'ie' => null,
                'phone' => '(11) 11111-1111',
                'birth' => '1990-01-01',
                'sexo' => 1,
                'price' => 0.00,
                'url' => null,
                'code' => null,
                'last_acess' => '2025-04-04 16:48:48',
                'order' => 999,
                'password' => '$2y$10$xAxz15OV1pt92t9DYLqnJ.jAM53hYdxEkhLq37eNZqA0TsGEhSDxi',
                'remember_token' => null,
                'verified_at' => null,
                'created_at' => '2025-02-18 20:36:52',
                'updated_at' => '2025-09-09 00:14:14',
            ]
        ]);
    }

    public function down()
    {
        DB::table('customers')->truncate();
    }
}
