<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlertedRecordsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sent_alerts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->integer('recipient_user_id');
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
		Schema::drop('sent_alerts');
	}

}
