<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class InsertTextsData extends Migration
{
    public function up()
    {
        DB::table('texts')->insert([
            [
                'id' => 1,
                'active' => 1,
                'name_main' => 'Termos de Uso',
                'type' => 'text',
                'name' => 'Termos de Uso',
                'image' => null,
                'place' => 2,
                'order' => 999,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 2,
                'active' => 1,
                'name_main' => 'Politica de Privacidade',
                'type' => 'text',
                'name' => 'Politica de Privacidade',
                'image' => null,
                'place' => 2,
                'order' => 999,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 3,
                'active' => 1,
                'name_main' => 'Politica de Cookies',
                'type' => 'text',
                'name' => 'Politica de Cookies',
                'image' => null,
                'place' => 0,
                'order' => 999,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 4,
                'active' => 0,
                'name_main' => 'Quem Somos',
                'type' => 'text',
                'name' => 'Quem Somos',
                'image' => null,
                'place' => 1,
                'order' => 999,
                'created_at' => null,
                'updated_at' => null,
            ]
        ]);
    }

    public function down()
    {
        DB::table('texts')->truncate();
    }
}
