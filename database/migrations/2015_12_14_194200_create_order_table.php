<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cart_id');
            $table->integer('user_id')->nullable();
            $table->integer('shipment_id');
            
            $table->text('first_name');
            $table->text('last_name');
            $table->text('address');
            $table->text('city');
            $table->text('postcode');
            $table->text('email');
            $table->text('telephone');
            $table->text('comment')->nullable();
            
            
            $table->float('cart_amount');
            $table->float('shipment_amount');
            $table->float('weight_total');
            
            $table->boolean('active')->default(true);
            $table->boolean('payd')->default(false); // czy oplacone
            $table->boolean('pay_delivery')->default(true); // platnosc za pobraniem, jesli true, payd nie musi byc true zeby mozna bylo wyslac
            $table->timestamp('confirm_date')->nullable();
            $table->timestamp('sent_date')->nullable();
            $table->string('confirm_hash', 32);
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('cart_id')->references('id')->on('carts');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('shipment_id')->references('id')->on('shipments');
            
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
