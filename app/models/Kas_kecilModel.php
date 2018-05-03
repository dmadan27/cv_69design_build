<?php
class Kas_kecilModel extends Database{


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
					'id' =>  'Kas-Kecil001',
					'nama' => 'John',
					'alamat' => 'Banjar',
					'no_telp' => '081353012823',
					'email' => 'John@gmail.com',
					'saldo' => '20000000',					
				),
				array(
					'id' =>  'Kas-Kecil002',
					'nama' => 'Micheal',
					'alamat' => 'Banjar',
					'no_telp' => '081353012823',
					'email' => 'Micheal@gmail.com',
					'saldo' => '20000000',					
				),
				array(
					'id' =>  'Kas-Kecil003',
					'nama' => 'Frank',
					'alamat' => 'Banjar',
					'no_telp' => '081353012823',
					'email' => 'Frank@gmail.com',
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