<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
	
	// KEY FIREBASE NOTIFICATION
	define(
		'KEY_FIREBASE_NOTIFICATION',
		'key=AAAAlOkhnSY:APA91bEqHj4gSO-lEqIeQz4g0ABtrhnSa6w8zDWMlXno50jkyJt5VwrfiC91uXv55yM560VyV4QaL9JG7XBktaX1IeMPJeNB7wDaFtI_Z3d2Qk6tnUeV0lYVxtvbF94fw_7rQqk8foLO'
	);

	if(STATUS_DEV == 'DEVELOPMENT'){
		// config base url
		define('BASE_URL', 'http://localhost/cv_69design_build/'); // isi path dari web
		define('SITE_URL', BASE_URL.'index.php/'); // hilangkan index.php atau komentari SITE_URL jika sudah memakai .htaccess
		define('DEFAULT_CONTROLLER', 'home'); // default controller yg diakses pertama kali
		define('VERSION', 'Beta v1.0');

		// config database
		define('DB_HOST', 'localhost'); // host db
		define('DB_USERNAME', 'root'); // username db
		define('DB_PASSWORD', ''); // password db
		define('DB_NAME', '69design-build'); // db yang digunakan
	}
	else if(STATUS_DEV == 'LIVE'){
		define('BASE_URL', 'https://dev.69designbuild.com/v1/web/'); 
		define('SITE_URL', BASE_URL.'index.php/'); 
		define('DEFAULT_CONTROLLER', 'home');
		define('VERSION', 'Beta v1.0');

		define('DB_HOST', 'localhost');
		define('DB_USERNAME', 'designbu_full');
		define('DB_PASSWORD', '69db69db69db');
		define('DB_NAME', 'designbu_69design_build.sql');
	}
	else if(STATUS_DEV == 'PRODUCTION'){
		define('BASE_URL', 'https://system.69design-build.com/');
		define('SITE_URL', BASE_URL.'index.php/');
		define('DEFAULT_CONTROLLER', 'home');
		define('VERSION', 'v1.0');

		define('DB_HOST', 'localhost');
		define('DB_USERNAME', 'designbu_full');
		define('DB_PASSWORD', '69db69db69db');
		define('DB_NAME', 'designbu_69design_build');
	}
	else die(json_encode(
		array(
			'success' => false,
			'message' => 'Access Denied'
		)
	));

		
