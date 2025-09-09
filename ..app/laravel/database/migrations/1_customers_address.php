<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersAddressTable extends Migration
{
    public function up()
    {
        Schema::create('customers_address', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('customers');
            $table->integer('main')->default(0);
            $table->text('name')->nullable();
            $table->string('cpf_cnpj', 18)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('phone', 15)->nullable();
            $table->text('address');
            $table->string('number', 100);
            $table->string('complement', 255)->nullable();
            $table->string('neighborhood', 255);
            $table->string('city', 255);
            $table->string('uf', 2);
            $table->string('zipcode', 10);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('state', 255)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers_address');
    }
}
