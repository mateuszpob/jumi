<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderDiscountIVariant extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_items', function ($table) {
            $table->integer('variant_id')->nullable();
            $table->string('ean', 32)->nullable();
            $table->float('price_discounted')->nullable();
        });
        Schema::table('product_variants', function ($table) {
            $table->float('price_discounted')->nullable();
        });
        Schema::table('cart_items', function ($table) {
            $table->float('price_discounted')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_items', function ($table) {
            $table->dropColumn('variant_id');
            $table->dropColumn('ean');
            $table->dropColumn('price_discounted');
        });
    }
}
