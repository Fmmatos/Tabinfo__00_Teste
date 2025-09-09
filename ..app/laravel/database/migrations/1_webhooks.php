<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebhooksTable extends Migration
{
    public function up()
    {
        Schema::create('webhooks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type', 50);
            $table->integer('status');
            $table->integer('orders')->nullable();
            $table->text('gateway_id');
            $table->text('gateway_webhooks_id');
            $table->text('return__');
            $table->text('reponse');
            $table->text('request');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('webhooks');
    }
}
