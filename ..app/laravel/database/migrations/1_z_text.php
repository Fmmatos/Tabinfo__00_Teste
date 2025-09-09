<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZTextTable extends Migration
{
    public function up()
    {
        Schema::create('z_text', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('table__', 100);
            $table->unsignedBigInteger('id__');
            $table->string('fields', 50);
            $table->text('value');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('z_text');
    }
}
