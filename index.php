<?php
	date_default_timezone_set('Asia/Jakarta');
	session_start();

	define("BASE_PATH", true);
	define('ROOT', dirname(__FILE__)); // root file web
	define('DS', DIRECTORY_SEPARATOR); // pemisah direktori '\'
	define('STATUS_DEV', 'DEVELOPMENT'); // DEVELOPMENT, LIVE, PRODUCTION 

	/** Load config */
	require_once "app/config/config.php";
	require_once "app/config/route.php";
	require_once "app/library/Database.class.php"; 
	require_once "app/library/Controller.class.php";
	require_once "app/library/Page.class.php";
	require_once "app/library/Auth.class.php";
	require_once "app/library/Datatable.class.php";
	require_once "app/library/Helper.class.php";
	require_once "app/library/Validation.class.php";
	require_once "app/library/Excel.class.php";

	// load abstract
	require_once "app/abstracts/CrudAbstract.php";
	require_once "app/abstracts/Crud_modalsAbstract.php";

	// load interface
	require_once "app/interfaces/ModelInterface.php";

	// get request
	$request = isset($_SERVER['PATH_INFO']) ? preg_replace("|/*(.+?)/*$|", "\\1", $_SERVER['PATH_INFO']) : DEFAULT_CONTROLLER;

	$route = new Route();
	$route->setUri($request)->getController();

