<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYMenuAdminColumnsTable extends Migration
{
    public function up()
    {
        Schema::create('y_menu_admin_columns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('y_menu_admin');
            $table->unsignedBigInteger('users');
            $table->text('value');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('y_menu_admin_columns');
    }
}
