<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPackagesAnalytics extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::connection('analytics')->create('packages', function($table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('application_package_id');
			$table->string('application_package_name');
			$table->string('application_package_version');
			$table->string('application_package_vulnerability_severity');
		});
		
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::connection('analytics')->drop('packages');
	}

}
