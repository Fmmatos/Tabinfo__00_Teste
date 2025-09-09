<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomersAddressIndexesAndForeignKeys extends Migration
{
    public function up()
    {
        Schema::table('customers_address', function (Blueprint $table) {
            $table->foreign('customers', 'customers_address__customers__foreign')
                  ->references('id')
                  ->on('customers')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('customers_address', function (Blueprint $table) {
            $table->dropForeign('customers_address__customers__foreign');
        });
    }
}
