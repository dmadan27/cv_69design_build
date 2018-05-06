<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	* 
	*/
	class Sub_kas_kecilModel extends Database{

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
					'umur' => 20,
				),
				array(
					'nama' => 'Ujang Jeprut',
					'umur' => 20,
				),
				array(
					'nama' => 'Ujang Jeprut',
					'umur' => 20,
				),
				array(
					'nama' => 'Ujang Jeprut',
					'umur' => 20,
				),
				array(
					'nama' => 'Ujang Jeprut',
					'umur' => 20,
				),
			);

			return $data;
		}

		/**
		* 
		*/
		public function getUser($username){
			$query = "SELECT * FROM sub_kas_kecil WHERE BINARY email = :username";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':username', $username);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

		/**
		* 
		*/
		public function __destruct(){
			$this->closeConnection($this->koneksi);
		}
	}