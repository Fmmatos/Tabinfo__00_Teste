<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddYMenuAdminIndexesAndForeignKeys extends Migration
{
    public function up()
    {
        Schema::table('y_menu_admin', function (Blueprint $table) {
            $table->index('categories', 'y_menu_admin__y_menu_admin_categories__foreign');
        });
    }

    public function down()
    {
        Schema::table('y_menu_admin', function (Blueprint $table) {
            $table->dropIndex('y_menu_admin__y_menu_admin_categories__foreign');
        });
    }
}
