<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Default Queue Driver
	|--------------------------------------------------------------------------
	|
	| The Laravel queue API supports a variety of back-ends via an unified
	| API, giving you convenient access to each back-end using the same
	| syntax for each one. Here you may set the default queue driver.
	|
	| Supported: "sync", "beanstalkd", "sqs", "iron", "redis"
	|
	*/

	'default' => 'sqs',

	/*
	|--------------------------------------------------------------------------
	| Queue Connections
	|--------------------------------------------------------------------------
	|
	| Here you may configure the connection information for each server that
	| is used by your application. A default configuration has been added
	| for each back-end shipped with Laravel. You are free to add more.
	|
	*/

	'connections' => array(
		'sqs' => array(
			'driver' => 'sqs',
			'key'    => 'AKIAJS5YYIOIIT3XI2EA',
			'secret' => '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',
			'queue'  => 'https://sqs.us-east-1.amazonaws.com/373233922238/nosprawl-sqs-va',
			'region' => 'us-east-1',
		)

	),

	/*
	|--------------------------------------------------------------------------
	| Failed Queue Jobs
	|--------------------------------------------------------------------------
	|
	| These options configure the behavior of failed queue job logging so you
	| can control which database and table are used to store the jobs that
	| have failed. You may change them to any database / table you wish.
	|
	*/

	'failed' => array(
		'database' => 'pgsql', 'table' => 'failed_jobs',
	),

);
