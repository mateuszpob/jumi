<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExtendedDesc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function ($table) {
            $table->text('ext_desc')->nullable();
        });
        Schema::table('cart_items', function ($table) {
            $table->dropForeign('cart_items_variant_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @n void
     */
    public function down()
    {
        //
    }
}
