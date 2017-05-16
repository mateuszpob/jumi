<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         
        Schema::create('schema_categories', function ($table) {
            $table->increments('id');
            $table->integer('owner_id')->nullable();
            $table->text('name', 32);
            $table->text('url', 32);
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            
        });
        
//        Schema::table('categories', function (Blueprint $table) {
//            $table->foreign('schema_id')->references('id')->on('schema_categories');
//        });
//        
//        Schema::table('items', function (Blueprint $table) {
//            $table->foreign('schema_id')->references('id')->on('schema_categories');
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('schema_categories');
    }
}
