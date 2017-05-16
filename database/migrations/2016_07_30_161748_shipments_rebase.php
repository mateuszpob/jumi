<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ShipmentsRebase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function ($table) {
            $table->dropForeign('orders_shipment_id_foreign');
            $table->dropColumn('shipment_id');
        });
        Schema::table('shipments', function ($table) {
            $table->boolean('quantity_multiplication')->default(false); //czy cene wysylki mnożyć razy ilość zamawianych przedmiotów
            $table->integer('price_percentage')->nullable();
            $table->float('price')->nullable()->change();
            $table->integer('max_quantity')->nullable();
        });
        Schema::table('items', function ($table) {
            $table->integer('shipment_id')->nullable();
            $table->foreign('shipment_id')->references('id')->on('shipments');
        });
        Schema::table('carts', function ($table) {
            $table->float('total_amount')->change();
            $table->float('shipment_amount');
            $table->float('items_amount');
            $table->dropColumn('shipment_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
