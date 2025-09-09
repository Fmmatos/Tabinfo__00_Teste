<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddItemsIndexesAndForeignKeys extends Migration
{
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->index('type', 'idx_type');
            $table->foreign('categories', 'items__items_categories__foreign')
                  ->references('id')
                  ->on('items_categories')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign('items__items_categories__foreign');
            $table->dropIndex('idx_type');
        });
    }
}
