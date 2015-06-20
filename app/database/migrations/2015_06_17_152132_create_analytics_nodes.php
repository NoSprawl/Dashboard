<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnalyticsNodes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::connection('analytics')->create('nodes', function($table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('application_user_id');
			$table->integer('application_node_id');
			$table->string('service_provider_type');
			$table->integer('risk');
			$table->integer('vulnerability_count_critical');
			$table->integer('vulnerability_count_high');
			$table->integer('vulnerability_count_low');
			$table->boolean('application_is_managed');
			$table->integer('application_classification_id');
		});
		
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::connection('analytics')->drop('nodes');
	}

}
