<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	* 
	*/
	class Saldo_kas_kecilModel extends Database{

		protected $koneksi;
		protected $dataTable;

		/**
		* 
		*/
		public function __construct(){
			$this->koneksi = $this->openConnection();
		}

		/**
		* 
		*/
		public function getAll(){
			$data = array(
				array(
					'ID Kas Kecil' => 'KasKecil-001',
					'Tanggal' => '1-5-2018',
					'Uang Masuk' => '320000',
					'Uang Keluar' => '0',
					'Saldo' => '320000',				
				),
				array(
					'ID Kas Kecil' => 'KasKecil-001',
					'Tanggal' => '2-5-2018',
					'Uang Masuk' => '320000',
					'Uang Keluar' => '0',
					'Saldo' => '640000',				
				),
				
				
			);

			return $data;
		}

		/**
		* 
		*/
		// public function getUser($username){
		// 	$query = "SELECT * FROM sub_kas_kecil WHERE BINARY email = :username";

		// 	$statement = $this->koneksi->prepare($query);
		// 	$statement->bindParam(':username', $username);
		// 	$statement->execute();
		// 	$result = $statement->fetch(PDO::FETCH_ASSOC);

		// 	return $result;
		// }

		/**
		* 
		*/
		public function __destruct(){
			$this->closeConnection($this->koneksi);
		}
		
	}