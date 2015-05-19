<?php

class NodesTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('nodes')->delete();

		$integrations = Integration::all();
		
		if(!$integrations)
		{
			 $this->command->info('You must have users and integrations. At least one of each');
		}

		foreach($integrations as $integration)
		{	
			$node = ['name' => 'Node' . $integration->id, 'description' => 'Seeded Node', 'owner_id' => $integration->user_id, 'integration_id' => $integration->id];
			Node::firstOrCreate($node);
		}

	}

}
