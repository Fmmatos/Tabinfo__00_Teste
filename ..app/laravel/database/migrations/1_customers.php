<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('active')->default(0);
            $table->integer('approved')->default(0);
            $table->text('name');
            $table->text('image')->nullable();
            $table->string('type', 45);
            $table->unsignedBigInteger('customers')->nullable();
            $table->string('email', 255);
            $table->string('cpf', 14)->nullable();
            $table->string('cnpj', 18)->nullable();
            $table->string('ie', 255)->nullable();
            $table->string('phone', 15)->nullable();
            $table->date('birth')->nullable();
            $table->integer('sexo')->nullable();
            $table->decimal('price', 10, 2)->default(0.00);
            $table->string('url', 100)->nullable();
            $table->string('code', 10)->nullable();
            $table->dateTime('last_acess')->nullable();
            $table->integer('order')->default(999);
            $table->string('password', 255)->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
