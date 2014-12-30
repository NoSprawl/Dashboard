<?php

class IntegrationsTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{

		$user_ids = DB::table('users')->lists('id');

		if(!$user_ids)
		{
			$this->command->info('You must have users. At least one.');	
		}

		DB::table('integrations')->delete();

		foreach($user_ids as $id)
		{
			$integration = ['name' => 'Integ' . $id, 'service_provider_id' => 'SeededIntegration', 'user_id' => $id, 'authorization_field_1' => 'something', 'authorization_field_2' => 'something'];
			Integration::firstOrCreate($integration);
		}
		


	}

}