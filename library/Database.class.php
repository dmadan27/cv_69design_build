<?php

Defined("BASE_PATH") or die(ACCESS_DENIED);

/**
 * Class Database
 * Library untuk akses koneksi ke database, DBMS MySQL dan MariaDB
 * Koneksi menggunakan PDO
 */
class Database
{

	/**
	 * Method openConnection
	 * Proses membuka koneksi ke database
	 * @return connection {object}
	 */
	public function openConnection() {
		$dbHost = DB_HOST;
		$dbName = DB_NAME;
		try {
			$koneksi = new PDO("mysql:host=$dbHost;dbname=$dbName", DB_USERNAME, DB_PASSWORD);
			$koneksi->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			return $koneksi;
		}
		catch(PDOException $e) {
			http_response_code(500);
			die(json_encode(array(
				'success' => false,
				'message' => 'Fail Connection to Database',
				'error' => $e->getMessage()
			)));
		}
	}

	/**
	 * Method closeConnection
	 * Proses tutup koneksi
	 * @param koneksi {object}
	 */
	public function closeConnection($koneksi) {
		$koneksi = null;
	}
}