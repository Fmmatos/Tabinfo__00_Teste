<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddYMenuAdminColumnsIndexesAndForeignKeys extends Migration
{
    public function up()
    {
        Schema::table('y_menu_admin_columns', function (Blueprint $table) {
            $table->index('y_menu_admin', 'y_menu_admin_columns__y_menu_admin__foreign');
            $table->index('users', 'y_menu_admin__columns_users__foreign');
        });
    }

    public function down()
    {
        Schema::table('y_menu_admin_columns', function (Blueprint $table) {
            $table->dropIndex('y_menu_admin_columns__y_menu_admin__foreign');
            $table->dropIndex('y_menu_admin__columns_users__foreign');
        });
    }
}
