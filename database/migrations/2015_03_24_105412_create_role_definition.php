<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleDefinition extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('role_definitions', function(Blueprint $table)
                {
                    $table->increments('id');
                    $table->string('action', 255);
                    $table->integer('role_id')->unsigned();
                    $table->foreign('role_id')->references('id')->on('roles');
                    $table->unique(array('action', 'role_id'));
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
	}

}
