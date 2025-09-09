<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddItemsCategoriesIndexesAndForeignKeys extends Migration
{
    public function up()
    {
        Schema::table('items_categories', function (Blueprint $table) {
            $table->foreign('subcategories', 'items_categories__subcategories__foreign')
                  ->references('id')
                  ->on('items_categories')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('items_categories', function (Blueprint $table) {
            $table->dropForeign('items_categories__subcategories__foreign');
        });
    }
}
