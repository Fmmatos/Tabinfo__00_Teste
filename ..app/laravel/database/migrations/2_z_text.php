<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddZTextIndexesAndForeignKeys extends Migration
{
    public function up()
    {
        Schema::table('z_text', function (Blueprint $table) {
        });
    }

    public function down()
    {
        Schema::table('z_text', function (Blueprint $table) {
        });
    }
}
