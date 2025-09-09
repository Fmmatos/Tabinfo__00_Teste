<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class InsertZRelData extends Migration
{
    public function up()
    {
        DB::table('z_rel')->insert([
            [
                'id' => 1,
                'table__' => 'Vendor\\Models\\Items',
                'id__' => 1,
                'fields' => 'link',
                'value' => '',
                'created_at' => '2025-05-08 01:34:02',
                'updated_at' => '2025-07-14 01:59:55',
            ],
            [
                'id' => 2,
                'table__' => 'Vendor\\Models\\Items',
                'id__' => 2,
                'fields' => 'link',
                'value' => '',
                'created_at' => '2025-05-16 11:45:33',
                'updated_at' => '2025-05-16 11:53:02',
            ]
        ]);
    }

    public function down()
    {
        DB::table('z_rel')->truncate();
    }
}
