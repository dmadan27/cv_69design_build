<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	* 
	*/
	class Mutasi_saldo_kas_kecilModel extends Database implements ModelInterface{

		protected $koneksi;
		protected $dataTable;

		/**
		* 
		*/
		public function __construct(){
			$this->koneksi = $this->openConnection();
			$this->dataTable = new Datatable();
		}

		// ======================= dataTable ======================= //

			/**
			* 
			*/
			public function getAllDataTable($config){
				$this->dataTable->set_config($config);
				$statement = $this->koneksi->prepare($this->dataTable->getDataTable());
				$statement->execute();
				$result = $statement->fetchAll();

				return $result;
			}

			/**
			* 
			*/
			public function recordFilter(){
				return $this->dataTable->recordFilter();

			}

			/**
			* 
			*/
			public function recordTotal(){
				return $this->dataTable->recordTotal();
			}

		// ========================================================= //

		/**
		* 
		*/
		public function getAll(){
			$query = "SELECT * FROM mutasi_saldo_kas_kecil";

			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);

			return $result;
		}

		public function getById($id){

		}

		public function insert($data){

		}

		public function update($data){

		}

		public function delete($id){

		}

		public function export(){
			
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