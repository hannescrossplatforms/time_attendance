<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Database Connections
	|--------------------------------------------------------------------------
	|
	| Here are each of the database connections setup for your application.
	| Of course, examples of configuring each database platform that is
	| supported by Laravel is shown below to make development simple.
	|
	|
	| All database work in Laravel is done through the PHP PDO facilities
	| so make sure you have the driver for your particular database of
	| choice installed on your machine before you begin development.
	|
	*/

	'connections' => array(

		'hiprm' => array(
			'driver'    => 'mysql',
			'host'      => 'rmdbmaster.hipzone.co.za',
			'database'  => 'hiprm',
			'username'  => 'hipzone',
			'password'  => 'Np0at4dbs',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		),

		'radius_wifistag' => array(
			'driver'    => 'mysql',
			'host'      => 'hipspot.hipzone.co.za',
			'database'  => 'radius',
			'username'  => 'hipzone',
			'password'  => 'Np0at4dbs',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		),

		// 'hipengage' => array(
		// 	'driver'    => 'mysql',
		// 	'host'      => 'localhost',
		// 	'database'  => 'hipengage',
		// 	'username'  => 'hipzone',
		// 	'password'  => 'Np0at4dbs',
		// 	'charset'   => 'utf8',
		// 	'collation' => 'utf8_unicode_ci',
		// 	'prefix'    => '',
		// ),

		// 'hiprm' => array(
		// 	'driver'    => 'mysql',
		// 	'host'      => 'localhost',
		// 	'database'  => 'hiprm',
		// 	'username'  => 'hipzone',
		// 	'password'  => 'Np0at4dbs',
		// 	'charset'   => 'utf8',
		// 	'collation' => 'utf8_unicode_ci',
		// 	'prefix'    => '',
		// ),
		
		// 'pgsql' => array(
		// 	'driver'   => 'pgsql',
		// 	'host'     => 'localhost',
		// 	'database' => 'homestead',
		// 	'username' => 'homestead',
		// 	'password' => 'secret',
		// 	'charset'  => 'utf8',
		// 	'prefix'   => '',
		// 	'schema'   => 'public',
		// ),

	),

);
