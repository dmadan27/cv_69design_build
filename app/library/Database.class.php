<?php
	/**
	* 
	*/
	class Database{
		
		public function __construct(){
			
		}

		public function openConnection(){
			try{
				$koneksi = new PDO("mysql:host=DB_HOST;dbname=DB_NAME", DB_USERNAME, DB_PASSWORD);
				$koneksi->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				return $koneksi;
			}
			catch(PDOException $e){
				die("Koneksi Database Error: " . $e->getMessage()); // jika ada error
			}
		}

		public function closeConnection(){
			$koneksi = null;
		}
	}