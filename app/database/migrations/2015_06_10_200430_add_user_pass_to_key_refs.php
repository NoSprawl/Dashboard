<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserPassToKeyRefs extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('key_references', function(Blueprint $table)
		{
			$table->string('username')->nullable();
			$table->string('password')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('key_references', function(Blueprint $table)
		{
		});
	}

}
