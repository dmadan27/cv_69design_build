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
	require_once "app/library/Helper.class.php";

	// load abstract

	// load interface

	$request = isset($_SERVER['PATH_INFO']) ? preg_replace("|/*(.+?)/*$|", "\\1", $_SERVER['PATH_INFO']) : DEFAULT_CONTROLLER;

	$route = new Route();
	$route->setUri($request)->getController();

