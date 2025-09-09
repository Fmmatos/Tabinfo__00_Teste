<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYMenuAdminCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('y_menu_admin_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('active')->default(1);
            $table->text('name');
            $table->string('title', 255);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('y_menu_admin_categories');
    }
}
