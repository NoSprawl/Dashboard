<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBaseImagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('base_images', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('rollback_index');
			$table->string('service_provider_id');
			$table->string('service_provider_label');
			$table->string('label')->default('');
			$table->string('integration_id');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('base_images');
	}

}
