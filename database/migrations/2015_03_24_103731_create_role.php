<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRole extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('roles', function($table)
                {
                    $table->increments('id');
                    $table->string('name', 100);
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
            Schema::dropIfExists('role_definitions');
            Schema::dropIfExists('role_user');
            //DB::unprepared('TRUNCATE roles CASCADE');
            Schema::dropIfExists('roles');
	}

}
