<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('promotion_percentage')->nullable();
            $table->timestamp('promotion_start_date')->nullable();
            $table->timestamp('promotion_end_date')->nullable();
            $table->string('name', '64');
            $table->string('image_path', '128')->nullable();
            $table->boolean('promotion_once')->default(true); // czy promocja moze byc jesli kupuje tylko jeden item w grupie
            $table->boolean('active')->default(true);
            $table->text('description')->nullable();
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
        Schema::drop('product_groups');
    }
}
