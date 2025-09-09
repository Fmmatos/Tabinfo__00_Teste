<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddZRelIndexesAndForeignKeys extends Migration
{
    public function up()
    {
        Schema::table('z_rel', function (Blueprint $table) {
        });
    }

    public function down()
    {
        Schema::table('z_rel', function (Blueprint $table) {
        });
    }
}
