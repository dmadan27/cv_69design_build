<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
	
	/**
	* 
	*/
	class ExampleModel extends Database implements ModelInterface{
		
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
		public function getAllDataTable($config){
			$dataTable = new Datatable($config);

			$statement = $this->koneksi->prepare($dataTable->getDataTable());
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