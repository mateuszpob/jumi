<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('transaction_id', 128)->nullable();
            $table->integer('order_id'); // order w tym sklepie
            $table->string('customer_ip', 32)->nullable();
            $table->string('merchant_id', 32)->nullable();
            $table->string('currency_code', 6);
            $table->float('total_amount');
            $table->string('buyer_email', 32)->nullable();
            $table->string('buyer_phone', 32)->nullable();
            $table->string('buyer_first_name', 32)->nullable();
            $table->string('buyer_last_name', 32)->nullable();
            $table->string('status', 32);
            $table->string('payment_id', 32);
            $table->text('data')->nullable();
            
            $table->foreign('order_id')->references('id')->on('orders');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('payment_logs');
    }
}
