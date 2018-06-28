<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
	
	if(STATUS_DEV == 'DEVELOPMENT'){
		// config base url
		define('BASE_URL', 'http://localhost/cv_69design_build/'); // isi path dari web
		define('SITE_URL', BASE_URL.'index.php/'); // hilangkan index.php atau komentari SITE_URL jika sudah memakai .htaccess
		define('DEFAULT_CONTROLLER', 'home'); // default controller yg diakses pertama kali
		define('VERSION', 'Alpha v0.1');

		// config database
		define('DB_HOST', 'localhost'); // host db
		define('DB_USERNAME', 'root'); // username db
		define('DB_PASSWORD', ''); // password db
		define('DB_NAME', '69design-build'); // db yang digunakan
	}
	else if(STATUS_DEV == 'LIVE'){
		define('BASE_URL', 'https://69design-build.lordraze.com/'); // isi path dari web
		define('SITE_URL', BASE_URL.'index.php/'); // hilangkan index.php atau komentari SITE_URL jika sudah memakai .htaccess
		define('DEFAULT_CONTROLLER', 'home'); // default controller yg diakses pertama kali
		define('VERSION', 'Alpha v0.1');

		// config database
		define('DB_HOST', 'localhost'); // host db
		define('DB_USERNAME', 'lordraze_full'); // username db
		define('DB_PASSWORD', 'VixyBlack27'); // password db
		define('DB_NAME', 'lordraze_69_design_build'); // db yang digunakan
	}
	else if(STATUS_DEV == 'PRODUCTION'){
		define('BASE_URL', 'https://admin.69design-build.com/'); // isi path dari web
		define('SITE_URL', BASE_URL.'index.php/'); // hilangkan index.php atau komentari SITE_URL jika sudah memakai .htaccess
		define('DEFAULT_CONTROLLER', 'home'); // default controller yg diakses pertama kali
		define('VERSION', '');

		// config database
		define('DB_HOST', 'localhost'); // host db
		define('DB_USERNAME', 'admin'); // username db
		define('DB_PASSWORD', ''); // password db
		define('DB_NAME', '69design-build'); // db yang digunakan
	}
	else die();

		
