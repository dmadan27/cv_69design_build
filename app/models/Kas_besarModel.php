<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
	
	/**
	* 
	*/
	class Kas_besarModel extends Database{

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
					'nama' => 'Ujang Jeprut',
					'alamat' => 'Sukabumi',
					'no_telp' => '081353012823',
					'email' => 'Ujang1@gmail.com',
						
				),
				array(
					'nama' => 'Ujang Kanem',
					'alamat' => 'Sukabumi',
					'no_telp' => '081353012823',
					'email' => 'Ujang2@gmail.com',
						
				),
				array(
					'nama' => 'Ujang Hiid',
					'alamat' => 'Sukabumi',
					'no_telp' => '081353012823',
					'email' => 'Ujang3@gmail.com',
						
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