<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
	
	/**
	* Class database untuk open dan close koneksi database
	*/
	class Database{

		/**
		* Fungsi membuka koneksi memakai PDO
		*/
		public function openConnection(){
			$dbHost = DB_HOST;
			$dbName = DB_NAME;
			try{
				$koneksi = new PDO("mysql:host=$dbHost;dbname=$dbName", DB_USERNAME, DB_PASSWORD);
				$koneksi->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				return $koneksi;
			}
			catch(PDOException $e){
				die("Koneksi Database Error: " . $e->getMessage()); // jika ada error
			}
		}

		/**
		* Fungsi tutup koneksi
		*/
		public function closeConnection($koneksi){
			$koneksi = null;
		}
	}