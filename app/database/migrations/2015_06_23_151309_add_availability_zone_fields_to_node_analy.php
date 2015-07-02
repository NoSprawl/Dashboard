<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAvailabilityZoneFieldsToNodeAnaly extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::connection('analytics')->table('nodes', function(Blueprint $table){
			$table->string('service_provider_availability_zone')->nullable();
		});
		
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::connection('analytics')->table('nodes', function(Blueprint $table){
			$table->dropColumn('service_provider_availability_zone');
		});
		
	}

}
