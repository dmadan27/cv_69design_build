<?php
	date_default_timezone_set('Asia/Jakarta');
	session_start();

	define("BASE_PATH", true);
	define('ROOT', dirname(__FILE__)); // root file web
	define('DS', DIRECTORY_SEPARATOR); // pemisah direktori '\'

	// load config
	require_once "app/config/config.php";
	require_once "app/config/route.php";
	require_once "app/library/Database.class.php"; 
	require_once "app/library/Controller.class.php";
	require_once "app/library/Page.class.php";
	require_once "app/library/Auth.class.php";
	require_once "app/library/Datatable.class.php";

	// load interface
	// require_once "app/interface/CrudInterface.php";
	// require_once "app/interface/ModelInterface.php";

	$request = isset($_SERVER['PATH_INFO']) ? preg_replace("|/*(.+?)/*$|", "\\1", $_SERVER['PATH_INFO']) : DEFAULT_CONTROLLER;

	// $_SESSION['sess_id'] = '';
	// $_SESSION['sess_nama'] = '';
	// $_SESSION['sess_alamat'] = '';
	// $_SESSION['sess_email'] = '';
	// $_SESSION['sess_foto'] = '';
	// $_SESSION['sess_status'] = '';

	// $_SESSION['sess_level'] = 'KAS BESAR';
	// $_SESSION['sess_level'] = 'KAS KECIL';
	// $_SESSION['sess_level'] = 'OWNER';
	// $_SESSION['sess_level'] = '';

	$route = new Route();
	$route->setUri($request)->getController();

