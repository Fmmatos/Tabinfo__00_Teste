<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('active')->default(1);
            $table->text('name');
            $table->text('image')->nullable();
            $table->string('type', 50);
            $table->unsignedBigInteger('categories')->nullable();
            $table->integer('order')->default(999);
            $table->integer('place')->default(0);
            $table->decimal('price', 10, 2)->default(0.00);
            $table->date('date')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('link', 255)->nullable();
            $table->integer('icon')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('items');
    }
}
