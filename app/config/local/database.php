<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| PDO Fetch Style
	|--------------------------------------------------------------------------
	|
	| By default, database results will be returned as instances of the PHP
	| stdClass object; however, you may desire to retrieve records in an
	| array format for simplicity. Here you can tweak the fetch style.
	|
	*/

	'fetch' => PDO::FETCH_CLASS,

	'default' => 'application',

	'connections' => array(
		'application' => array(
			'driver'   => 'pgsql',
			'host'     => 'localhost',
			'port'		 => 5432,
			'database' => 'nosprawl',
			'username' => 'mkeen',
			'password' => '',
			'charset'  => 'utf8',
			'prefix'   => '',
			'schema'   => 'public',
		),
		
		'analytics' => array(
			'driver'   => 'pgsql',
			'host'     => 'localhost',
			'port'		 => 5432,
			'database' => 'nosprawl_analytics',
			'username' => 'mkeen',
			'password' => '',
			'charset'  => 'utf8',
			'prefix'   => '',
			'schema'   => 'public',
		)

	),

	'migrations' => 'migrations'
);
