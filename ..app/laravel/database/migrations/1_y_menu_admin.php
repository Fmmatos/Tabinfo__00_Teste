<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYMenuAdminTable extends Migration
{
    public function up()
    {
        Schema::create('y_menu_admin', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('active')->default(1);
            $table->string('table__', 100);
            $table->unsignedBigInteger('categories');
            $table->string('name', 255);
            $table->string('icon', 255)->nullable();
            $table->integer('type')->default(0);
            $table->string('type_items', 50)->nullable();
            $table->text('filter')->nullable();
            $table->text('orderby')->nullable();
            $table->integer('order')->default(999);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('y_menu_admin');
    }
}
