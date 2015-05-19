<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameServiceProviderColumnInIntegrations extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('integrations', function($table)
		{
		    $table->renameColumn('service_provider_id', 'service_provider');
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
		    $table->renameColumn('service_provider', 'service_provider_id');
		});
	}

}
