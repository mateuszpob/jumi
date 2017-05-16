<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('schema_id')->nullable();
            $table->integer('producer_id')->nullable();
            $table->string('name', 100);
            $table->text('description');
            $table->text('image_path')->nullable();
            $table->float('price');
            $table->float('price_producer');
            $table->float('price_discounted')->nullable();
            $table->float('weight')->nullable();
            $table->string('code', 100)->nullable();
            $table->string('ean', 100)->nullable();
            $table->string('category_name', 100)->nullable(); // nazwa kategorii na stronie producenta
            $table->integer('count')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();

        });
        
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('items');
    }
}
