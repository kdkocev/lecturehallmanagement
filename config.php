<?php
	$local_config = include './local_config.php';
	return array(
		'base_path' => $local_config['base_path'],
		'database' => $local_config['database'],
		'templates' => array(
			'static_urls' => $local_config['base_path'] . '/static',
			'base_path' => $local_config['base_path'],
			'start_hour' => 9,
			'end_hour' => 13,
			'calendar_hours_height' => 200,
			'calendar_delimeters_offset' => '15' // Minutes
		),
		'date_default_timezone_set' => 'America/New_York',
	);
