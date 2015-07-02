<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTotalNodecountForAnalytics extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::connection('analytics')->table('nodes', function(Blueprint $table){
			$table->string('account_nodes')->nullable();
		});
		
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::connection('analytics')->table('nodes', function(Blueprint $table){
			$table->dropColumn('account_nodes');
		});
		
	}
	
}
