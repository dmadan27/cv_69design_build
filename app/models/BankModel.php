<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	* Class BankModel, implementasi ke ModelInterface
	*/
	class BankModel extends Database implements ModelInterface{

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
			$status = "AKTIF";
			$query = "INSERT INTO bank (nama, saldo, status) VALUES (:nama, :saldo, :status);";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':nama', $data['nama']);
			$statement->bindParam(':saldo', $data['saldo']);
			$statement->bindParam(':status', $status);
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
		public function __destruct(){
			$this->closeConnection($this->koneksi);
		}

	}