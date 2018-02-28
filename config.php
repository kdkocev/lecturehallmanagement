<?php
	return array(
		'base_path' => '/fmi',
		'database' => array(
			'username' => 'root',
			'password' => '123123',
			'db_name' => 'fmi',
			'servername' => 'localhost'
		),
		'templates' => array(
			'static_urls' => '/fmi/static',
			'base_path' => '/fmi',
			'start_hour' => 9,
			'end_hour' => 12,
			'calendar_hours_height' => 200,
			'calendar_delimeters_offset' => '30' // Minutes
		),
		'date_default_timezone_set' => 'America/New_York',
	);
