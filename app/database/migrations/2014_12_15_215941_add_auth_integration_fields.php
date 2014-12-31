<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAuthIntegrationFields extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('integrations', function($table)
		{
			$table->string('authorization_field_1')->nullable();
			$table->string('authorization_field_2')->nullable();
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
			$table->dropColumn('authorization_field_1');
			$table->dropColumn('authorization_field_2');
		});
	}

}
