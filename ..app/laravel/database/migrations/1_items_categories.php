<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('items_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('active')->default(1);
            $table->text('name');
            $table->text('image')->nullable();
            $table->string('type', 50);
            $table->unsignedBigInteger('subcategories')->nullable();
            $table->integer('order')->default(999);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('items_categories');
    }
}
