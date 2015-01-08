<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMacAddressesTableAndAssoc extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mac_addresses', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('address')->unique()->default('00:00:00:00:00:00');
			$table->integer('node_id');
			$table->foreign('node_id')->references('id')->on('nodes');
		});
		
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('mac_addresses');
	}

}
