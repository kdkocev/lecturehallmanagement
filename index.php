<?php
	session_start();
	error_reporting(E_ALL);

	ini_set('display_errors', 1);

	// Used by all request handlers
	include 'system/controller.php';
	
	// Handles and routes requests
	include 'system/urls.php';
?>
