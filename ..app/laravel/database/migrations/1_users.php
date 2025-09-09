<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('active')->default(1);
            $table->text('name');
            $table->string('email', 255);
            $table->string('phone', 255)->nullable();
            $table->bigInteger('users')->nullable();
            $table->text('permissions')->nullable();
            $table->integer('permissions_all')->default(0);
            $table->string('password', 255);
            $table->string('remember_token', 100)->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
