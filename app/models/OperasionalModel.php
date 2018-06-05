<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	* Class BankModel, implementasi ke ModelInterface
	*/
	class OperasionalModel extends Database implements ModelInterface{

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
			$query = "SELECT * FROM operasional WHERE id = :id;";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

		/**
		* 
		*/
		public function insert($data){
			$query = "INSERT INTO operasional (id, id_bank, tgl,  nama,  nominal, ket) VALUES (:id, :id_bank, :tgl, :nama, :nominal, :ket);";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $data['id']);
			$statement->bindParam(':id_bank', $data['id_bank']);
			$statement->bindParam(':tgl', $data['tgl']);
			$statement->bindParam(':nama', $data['nama']);
			$statement->bindParam(':nominal', $data['nominal']);
			$statement->bindParam(':ket', $data['ket']);
			$result = $statement->execute();

			return $result;
		}

		/**
		* 
		*/
		public function update($data){
			$query = "UPDATE operasional SET nama = :nama, nominal = :nominal, ket =:ket WHERE id = :id;";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':nama', $data['nama']);
			$statement->bindParam(':nominal', $data['nominal']);
			$statement->bindParam(':ket', $data['ket']);
			$statement->bindParam(':id', $data['id']);
			$result = $statement->execute();

			return $result;
		}

		/**
		* 
		*/
		public function delete($id){
			$query = "DELETE FROM operasional WHERE id = :id";
			
			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$result = $statement->execute();

			return $result;
		}

		/**
		* 
		*/
		public function __destruct(){
			$this->closeConnection($this->koneksi);
		}

	}