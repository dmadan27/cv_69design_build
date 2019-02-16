<?php
	Defined("BASE_PATH") or die(ACCESS_DENIED);
	
	// KEY FIREBASE NOTIFICATION
	define(
		'KEY_FIREBASE_NOTIFICATION',
		'key=AAAAVowCLac:APA91bGdY5WrVjAbVfKpqoRmehjfV2wu8Zkd_rQI3kSbTtVELkWDX8kEsGbKnOpRMrF26kD8kHpwdJ781JdkWKL2_KqGt5hFdHYmfS_TJB0fc2YqW0bQy8CVPCTdPS8DqpJEpLlwUifv'
	);
	define('SEND_EMAIL', array('email' => '', 'password' => ''));

	if(STATUS_DEV == 'DEVELOPMENT'){ // local
		// config base url
		define('BASE_URL', 'http://localhost/cv_69design_build/'); // isi path dari web
		define('BASE_API_MOBLIE', 'http://localhost/api_69/');
		define('SITE_URL', BASE_URL.'index.php/'); // hilangkan index.php atau komentari SITE_URL jika sudah memakai .htaccess
		define('DEFAULT_CONTROLLER', 'home'); // default controller yg diakses pertama kali
		define('VERSION', 'Beta v1.0');

		// config database
		define('DB_HOST', 'localhost'); // host db
		define('DB_USERNAME', 'root'); // username db
		define('DB_PASSWORD', ''); // password db
		define('DB_NAME', '69design-build_dev'); // db yang digunakan
	}
	else if(STATUS_DEV == 'LIVE'){ // dev live
		define('BASE_URL', 'https://dev.69designbuild.com/v1/web/'); 
		define('BASE_API_MOBLIE', 'https:///dev.69designbuild.com/v1/api/');
		define('SITE_URL', BASE_URL.'index.php/'); 
		define('DEFAULT_CONTROLLER', 'home');
		define('VERSION', 'Beta v1.0');

		define('DB_HOST', 'localhost');
		define('DB_USERNAME', 'designbu_full');
		define('DB_PASSWORD', '69db69db69db');
		define('DB_NAME', 'designbu_69design_build_live');
	}
	else if(STATUS_DEV == 'TESTING'){ // testing before production
		define('BASE_URL', 'https://test.69designbuild.com/');
		define('BASE_API_MOBLIE', 'https:///api-test.69designbuild.com/'); 
		define('SITE_URL', BASE_URL.'index.php/'); 
		define('DEFAULT_CONTROLLER', 'home');
		define('VERSION', 'v1.0');

		define('DB_HOST', 'localhost');
		define('DB_USERNAME', 'designbu_full');
		define('DB_PASSWORD', '69db69db69db');
		define('DB_NAME', 'designbu_69design_build_testing');
	}
	else if(STATUS_DEV == 'PRODUCTION'){ // production
		define('BASE_URL', 'https://system.69design-build.com/');
		define('BASE_API_MOBLIE', 'https:///api.69designbuild.com/');
		define('SITE_URL', BASE_URL.'index.php/');
		define('DEFAULT_CONTROLLER', 'home');
		define('VERSION', 'v1.0');

		define('DB_HOST', 'localhost');
		define('DB_USERNAME', 'designbu_full');
		define('DB_PASSWORD', '69db69db69db');
		define('DB_NAME', 'designbu_69design_build');
	}
	else die(ACCESS_DENIED);

		
