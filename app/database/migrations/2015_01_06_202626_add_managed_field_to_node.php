<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddManagedFieldToNode extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('nodes', function(Blueprint $table)
		{
			$table->boolean('managed')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('nodes', function(Blueprint $table)
		{
			$table->dropColumn('managed');
		});
	}

}
