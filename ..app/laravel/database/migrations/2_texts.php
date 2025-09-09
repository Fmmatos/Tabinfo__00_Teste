<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTextsIndexesAndForeignKeys extends Migration
{
    public function up()
    {
        Schema::table('texts', function (Blueprint $table) {
        });
    }

    public function down()
    {
        Schema::table('texts', function (Blueprint $table) {
        });
    }
}
