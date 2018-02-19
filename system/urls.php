<?php
	include_once('utils.php');
	include_once('router.php');
	include_once('request.php');

	$router = new Router(array(
		'/api/slot/create' => url('controllers/api/slot.php', 'SlotController.create'),
		'/api/slot/delete' => url('controllers/api/slot.php', 'SlotController.remove'),
		'/api/slot/update' => url('controllers/api/slot.php', 'SlotController.update'),
		'/api/slot/list' => url('controllers/api/slot.php', 'SlotController.fetch_all'),
		'/api/slot/lock' => url('controllers/api/slot.php', 'SlotController.toggle_lock_slot'),
		'/api/slot/renderNotes' => url('controllers/api/slot.php', 'SlotController.renderNotes'),
		'/' => url('controllers/home.php', 'HomeController'),
		'/slot/reserve' => url('controllers/home.php', 'HomeController.reserve'),
		'/api/slot/setfree' => url('controllers/api/slot.php', 'SlotController.setfree'),
		'/slot/addreview' => url('controllers/home.php', 'HomeController.addReview'),
		'/login' => url('controllers/logincontroller.php', 'LoginController'),
		'/logout' => url('controllers/logincontroller.php', 'LoginController.logout'),
		'/admin' => url('', ''),

		'/api/slot/render' => url('controllers/api/slot.php', 'SlotController.renderSlot'),
	));
	
	$page_content = $router->route(new Request($_SERVER));
	print $page_content;
