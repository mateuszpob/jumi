<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePages extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('pages', function(Blueprint $table){
            $table->increments('id');
            $table->string('name', 255);
            $table->string('page_title', 255)->nullable();
            $table->string('url');
            $table->string('template', 64);
            $table->integer('type_id')->default(1);
            $table->text('html');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('pages');
    }

}
