<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_upper')->nullable();
            $table->integer('schema_id')->nullable();
            $table->integer('margin_percentage')->nullable(); // wszystkie itemy w tej kategorii beda mialy taka marze, cyba ze maja swoja wlasna cene w sklepie (u nas)
            $table->string('name');
            $table->string('url_name', 128)->nullable();
            $table->text('description')->nullable();
            $table->text('image_path')->nullable();
            $table->boolean('on_main_page')->default(true);
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
        if (Schema::hasTable('category_item'))
            Schema::drop('category_item');
        if (Schema::hasTable('categories'))
            Schema::drop('categories');
    }
}
