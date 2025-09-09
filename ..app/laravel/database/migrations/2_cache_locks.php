<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCacheLocksIndexesAndForeignKeys extends Migration
{
    public function up()
    {
        Schema::table('cache_locks', function (Blueprint $table) {
        });
    }

    public function down()
    {
        Schema::table('cache_locks', function (Blueprint $table) {
        });
    }
}
