<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLimboUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	Schema::create('limbo_users', function(Blueprint $table)
	{
		$table->increments('id');
		$table->timestamps();
		$table->string('name');
		$table->string('email')->unique();
		$table->integer('parent_user_id');
		$table->string('user_confirmation_token');
	});
	
}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('limbo_users');
	}

}
