<?php

Defined("BASE_PATH") or die(ACCESS_DENIED);

// KEY FIREBASE NOTIFICATION
define(
	'KEY_FIREBASE_NOTIFICATION',
	'key=AAAAVowCLac:APA91bGdY5WrVjAbVfKpqoRmehjfV2wu8Zkd_rQI3kSbTtVELkWDX8kEsGbKnOpRMrF26kD8kHpwdJ781JdkWKL2_KqGt5hFdHYmfS_TJB0fc2YqW0bQy8CVPCTdPS8DqpJEpLlwUifv'
);
define('SEND_EMAIL', array('email' => 'simakpro@69designbuild.com', 'password' => '69db69db69db_email_admin_simakpro'));

if(TYPE == 'DEV'){ // local
	// config base url
	define('BASE_URL', 'http://localhost/cv_69design_build/'); // isi path dari web
	define('BASE_API_MOBILE', 'http://localhost/api_69/');
	define('SITE_URL', BASE_URL.'index.php/'); // hilangkan index.php atau komentari SITE_URL jika sudah memakai .htaccess
	define('DEFAULT_CONTROLLER', 'home'); // default controller yg diakses pertama kali
	define('VERSION', 'v1.1');

	// config database
	define('DB_HOST', 'localhost'); // host db
	define('DB_USERNAME', 'root'); // username db
	define('DB_PASSWORD', ''); // password db
	define('DB_NAME', '69design-build_dev'); // db yang digunakan
}
else if(TYPE == 'DEV_LIVE') {
	// config base url
	define('BASE_URL', 'https://dev.69designbuild.com/'); // isi path dari web
	define('BASE_API_MOBILE', 'https://api-dev.69designbuild.com/');
	define('SITE_URL', BASE_URL.'index.php/'); // hilangkan index.php atau komentari SITE_URL jika sudah memakai .htaccess
	define('DEFAULT_CONTROLLER', 'home'); // default controller yg diakses pertama kali
	define('VERSION', 'v1.1');

	// config database
	define('DB_HOST', 'localhost'); // host db
	define('DB_USERNAME', 'designbu_full');
	define('DB_PASSWORD', '69db69db69db');
	define('DB_NAME', 'designbu_69design_build_dev'); // db yang digunakan
}
else if(TYPE == 'TEST'){ // testing before production
	define('BASE_URL', 'https://test.69designbuild.com/');
	define('BASE_API_MOBILE', 'https://api-test.69designbuild.com/'); 
	define('SITE_URL', BASE_URL.'index.php/'); 
	define('DEFAULT_CONTROLLER', 'home');
	define('VERSION', 'v1.1');

	define('DB_HOST', 'localhost');
	define('DB_USERNAME', 'designbu_full');
	define('DB_PASSWORD', '69db69db69db');
	define('DB_NAME', 'designbu_69design_build_testing');
}
else if(TYPE == 'PROD'){ // production
	define('BASE_URL', 'https://simakpro.69design-build.com/');
	define('BASE_API_MOBILE', 'https://api.69designbuild.com/');
	define('SITE_URL', BASE_URL.'index.php/');
	define('DEFAULT_CONTROLLER', 'home');
	define('VERSION', 'v1.1');

	define('DB_HOST', 'localhost');
	define('DB_USERNAME', 'designbu_full');
	define('DB_PASSWORD', '69db69db69db');
	define('DB_NAME', 'designbu_69design_build');
}
else {
	header('Content-Type: application/json');
	http_response_code(403);
	die(ACCESS_DENIED);
}

	
