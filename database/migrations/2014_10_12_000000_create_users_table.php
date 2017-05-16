<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('nick', 31)->nullable();
			$table->string('email', 31)->unique();
			$table->string('password', 60);
			$table->rememberToken();
                        
                        // dane adresowe
                        $table->string('first_name', 31);
                        $table->string('last_name', 31);
                        $table->string('address', 63);
                        $table->string('postcode', 10);
                        $table->string('city', 31);
                        $table->string('telephone', 31);
                        $table->string('company_name', 31)->nullable();
                        
                        
                        
                        
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
		Schema::drop('users');
	}

}
