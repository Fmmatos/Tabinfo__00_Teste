<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('x_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fields', 50);
            $table->text('value');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('x_settings');
    }
}
