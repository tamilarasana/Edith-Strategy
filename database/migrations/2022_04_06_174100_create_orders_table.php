<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('basket_id');
            $table->string('token_name')->nullable();
            $table->bigInteger('token_id')->nullable();
            $table->string('leg_type')->nullable();
            $table->integer('qty')->nullable();
            $table->string('status')->nullable();
            $table->double('delta')->nullable();
            $table->double('theta')->nullable();
            $table->double('vega')->nullable();
            $table->double('gamma')->nullable();
            $table->string('order_type')->nullable();
            $table->bigInteger('order_id')->nullable();
            $table->dateTime('order_date_time',0)->nullable();
            $table->string('order_avg_price',0)->nullable();
            $table->double('ltp')->default('0');
            $table->double('pnl')->default('0');
            $table->double('exit_price')->nullable();
            $table->double('total_inv')->default('0');
            $table->double('pnl_perc')->default('0');
            $table->integer('is_delete')->nullable();
            $table->timestamps();
            
            $table->foreign('basket_id')->references('id')->on('baskets')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });

        
    }
  
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
