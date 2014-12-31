<?php

class UsersTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		
		$users = [
					['name' => 'Fake1', 'email' => 'fake1@nosprawl.com', 'username' => 'fake1', 'password' =>  Hash::make('password123'), 'full_name' => 'Fake1 McFakester'],
					['name' => 'Fake2', 'email' => 'fake2@nosprawl.com', 'username' => 'fake2', 'password' =>  Hash::make('password123'), 'full_name' => 'Fake2 Fakestein'],
					['name' => 'Fake3', 'email' => 'fake3@nosprawl.com', 'username' => 'fake3', 'password' =>  Hash::make('password123'), 'full_name' => 'Fake3 Fakeconi'],
					['name' => 'Fake4', 'email' => 'fake4@nosprawl.com', 'username' => 'fake4', 'password' =>  Hash::make('password123'), 'full_name' => 'Fake4 Fakes'],
					['name' => 'Fake5', 'email' => 'fake5@nosprawl.com', 'username' => 'fake5', 'password' =>  Hash::make('password123'), 'full_name' => 'Fake5 Fakester'],
					['name' => 'Fake6', 'email' => 'fake6@nosprawl.com', 'username' => 'fake6', 'password' =>  Hash::make('password123'), 'full_name' => 'Fake6 Fakess'],
					['name' => 'Fake7', 'email' => 'fake7@nosprawl.com', 'username' => 'fake7', 'password' =>  Hash::make('password123'), 'full_name' => 'Fake7 Fakesterich'],
					['name' => 'Fake8', 'email' => 'fake8@nosprawl.com', 'username' => 'fake8', 'password' =>  Hash::make('password123'), 'full_name' => 'Fake8 Fakesterykos'],
					['name' => 'Fake9', 'email' => 'fake9@nosprawl.com', 'username' => 'fake9', 'password' =>  Hash::make('password123'), 'full_name' => 'Fake9 Fakesterian'],
					['name' => 'Fake0', 'email' => 'fake0@nosprawl.com', 'username' => 'fake0', 'password' =>  Hash::make('password123'), 'full_name' => 'Fake0 Fakessex'],
				];
		
		DB::table('users')->delete();

		foreach($users as $user)
		{
			User::firstOrCreate($user);
		}

	}

}
