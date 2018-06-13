<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
	
	/**
	* 
	*/
	class Kas_besarModel extends Database implements ModelInterface{

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

		}

				/**
		* 
		*/
		public function getById($id){
			$query = "SELECT * FROM kas_besar WHERE id = :id;";

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
			// try{
			// 	$this->koneksi->beginTransaction();

			// 	$this->insertBank($data);

			// 	$this->koneksi->commit();

			// 	return true;
			// }
			// catch(PDOException $e){
			// 	$this->koneksi->rollback();
			// 	die($e->getMessage());
			// 	// return false;
			// }
		}

		/**
		* 
		*/
		public function update($data){
			// try{
			// 	$this->koneksi->beginTransaction();

			// 	$this->updateBank($data);

			// 	$this->koneksi->commit();

			// 	return true;
			// }
			// catch(PDOException $e){
			// 	$this->koneksi->rollback();
			// 	die($e->getMessage());
			// 	// return false;
			// }
		}

		/**
		* 
		*/
		public function delete($id){
			// try{
			// 	$this->koneksi->beginTransaction();

			// 	$this->deleteBank($id);

			// 	$this->koneksi->commit();

			// 	return true;
			// }
			// catch(PDOException $e){
			// 	$this->koneksi->rollback();
			// 	die($e->getMessage());
			// 	// return false;
			// }
		}



		/**
		* 
		*/
		public function __destruct(){
			$this->closeConnection($this->koneksi);
		}
	}