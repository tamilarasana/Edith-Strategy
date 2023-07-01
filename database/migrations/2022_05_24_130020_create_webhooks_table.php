<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebhooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webhooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('webhookbasket_id')->nullable();
            $table->string('token_id')->nullable();
            $table->string('hook_name')->nullable();
            $table->json('order_details')->nullable();
            $table->integer('qty')->nullable();
            $table->string('status')->nullable();
            $table->string('order_type')->nullable();
            $table->dateTime('order_date_time')->nullable();
            $table->string('api_source')->nullable();
            $table->string('recurring')->nullable();
            $table->text('post_api')->nullable();

            
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('webhookbasket_id')->references('id')->on('webhookbaskets')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('webhooks');
    }
}
