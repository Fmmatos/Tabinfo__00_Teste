<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWebhooksIndexesAndForeignKeys extends Migration
{
    public function up()
    {
        Schema::table('webhooks', function (Blueprint $table) {
        });
    }

    public function down()
    {
        Schema::table('webhooks', function (Blueprint $table) {
        });
    }
}
