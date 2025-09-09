<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomersIndexesAndForeignKeys extends Migration
{
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->index('type', 'idx_type');
            $table->index('created_at', 'idx_created_at');
            $table->foreign('customers', 'customers__customers__foreign')
                  ->references('id')
                  ->on('customers')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign('customers__customers__foreign');
            $table->dropIndex('idx_type');
            $table->dropIndex('idx_created_at');
        });
    }
}
