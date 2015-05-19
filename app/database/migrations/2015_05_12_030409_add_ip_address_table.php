<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIpAddressTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ip_addresses', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->string('address');
			$table->integer('node_id');
			$table->boolean('private');
		});
		
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ip_addresses');
	}

}
