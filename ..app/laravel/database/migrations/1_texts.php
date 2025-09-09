<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTextsTable extends Migration
{
    public function up()
    {
        Schema::create('texts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('active')->default(1);
            $table->string('name_main', 255);
            $table->string('type', 45);
            $table->text('name');
            $table->text('image')->nullable();
            $table->integer('place')->default(0);
            $table->integer('order')->default(999);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('texts');
    }
}
