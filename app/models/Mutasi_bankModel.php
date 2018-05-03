<?php
class Mutasi_BankModel extends Database{


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
					'ID Bank' =>  'BRI-001',
					'Tanggal' => '1-5-2018',
					'Uang Masuk' => '10000000',
					'Uang Keluar' => '0',
					'saldo' => '10000000',					
				),

				array(
					'ID Bank' =>  'BRI-002',
					'Tanggal' => '3-5-2018',
					'Uang Masuk' => '10000000',
					'Uang Keluar' => '0',
					'saldo' => '20000000',					
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
