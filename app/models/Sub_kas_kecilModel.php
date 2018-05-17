<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	* 
	*/
	class Sub_kas_kecilModel extends Database implements ModelInterface{

		protected $koneksi;
		protected $dataTable;

		/**
		* 
		*/
		public function __construct(){
			$this->koneksi = $this->openConnection();
			$this->dataTable = new Datatable();
		}

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

		/**
		* 
		*/
		public function getAll(){
			
		}

		/**
		* 
		*/
		public function getById($id){
			
		}

		/**
		* 
		*/
		public function insert($data){
			$query = "INSERT INTO sub_kas_kecil (id, nama, alamat, no_telp, email, password, foto, saldo, status) ";
			$query .= "VALUES (:id, :nama, :alamat, :no_telp, :email, :password, :foto, :saldo, :status);";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $data['id']);
			$statement->bindParam(':nama', $data['nama']);
			$statement->bindParam(':alamat', $data['alamat']);
			$statement->bindParam(':no_telp', $data['no_telp']);
			$statement->bindParam(':email', $data['email']);
			$statement->bindParam(':password', $data['password']);
			$statement->bindParam(':foto', $data['foto']);
			$statement->bindParam(':saldo', $data['saldo']);
			$statement->bindParam(':status', $data['status']);
			$result = $statement->execute();

			return $result;
		}

		/**
		* 
		*/
		public function update($data){

		}

		/**
		* 
		*/
		public function delete($id){

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