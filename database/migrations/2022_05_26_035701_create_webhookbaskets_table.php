<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebhookbasketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webhookbaskets', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('webhook_id')->unsigned();
            $table->string('basket_name');
            $table->double('sq_target')->default('0');
            $table->double('sq_loss')->default('0');
            $table->dateTime('scheduled_exec',0);
            $table->dateTime('scheduled_sqoff',0);
            $table->string('recorring')->nullable();
            $table->string('weekDays')->nullable();
            $table->string('strategy')->nullable();
            $table->integer('qty')->default('0');
            $table->double('Pnl')->default('0');
            $table->double('Pnl_perc')->default('0');
            $table->double('init_target')->default('0');
            $table->double('current_target')->default('0');
            $table->double('prev_current_target')->default('0');
            $table->double('target_strike')->default('0');
            $table->double('max_target_achived')->default('0');
            $table->double('stop_loss')->default('0');
            $table->string('status')->nullable();
            $table->text('fb_token')->nullable();
            $table->integer('is_delete')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('webhook_id')->references('id')->on('webhooks')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('webhookbaskets');
    }
}
