<?php

date_default_timezone_set('Asia/Jakarta');
session_start();

define("BASE_PATH", true);
define("ACCESS_DENIED", json_encode(array('success' => false, 'message' => 'Access Denied'), JSON_PRETTY_PRINT));
define('ROOT', dirname(__FILE__)); // root file web
define('DS', DIRECTORY_SEPARATOR); // pemisah direktori '\'
/**
 * TYPE ENVIROMENT DEVELOPMENT
 * DEV --> for Local development
 * DEV_LIVE --> for Dev live
 * TEST --> for Testing (Pre Production)
 * PROD --> for Production
 */
define('TYPE', 'DEV');

/** Load config */
require_once "config/config.php";
require_once "config/route.php";
require_once "library/Database.class.php"; 
require_once "library/Controller.class.php";
require_once "library/Page.class.php";
require_once "library/Auth.class.php";
require_once "library/Datatable.class.php";
require_once "library/Helper.class.php";
require_once "library/Validation.class.php";
require_once "library/Excel.class.php";
require_once "library/Excel_v2.class.php";

// load abstract
require_once "app/abstracts/CrudAbstract.php";
require_once "app/abstracts/Crud_modalsAbstract.php";

// load interface
require_once "app/interfaces/ModelInterface.php";

// get request
$request = isset($_SERVER['PATH_INFO']) ? preg_replace("|/*(.+?)/*$|", "\\1", $_SERVER['PATH_INFO']) : DEFAULT_CONTROLLER;

$route = new Route();
$route->setUri($request)->getController();

