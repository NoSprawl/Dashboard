<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIntegrationFields extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('integrations', function($table)
		{
			$table->string('service_provider_id')->nullable();
			$table->integer('user_id')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('integrations', function($table)
		{
			$table->dropColumn('service_provider_id');
			$table->dropColumn('user_id');
		});
	}

}
