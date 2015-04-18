<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodePackageRecord extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		
		Schema::create('node_package_records', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('package');
			$table->string('version');
			$table->integer('node_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('node_package_records');
	}
	

}
