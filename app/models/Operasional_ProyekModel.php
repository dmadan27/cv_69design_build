<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
class Operasional_ProyekModel extends Database implements ModelInterface{
	
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
			// $query = "SELECT * FROM bank WHERE id = :id;";

			// $statement = $this->koneksi->prepare($query);
			// $statement->bindParam(':id', $id);
			// $statement->execute();
			// $result = $statement->fetch(PDO::FETCH_ASSOC);

			// return $result;
		}

		/**
		* 
		*/
		public function insert($data){
			// $dataOperasionalProyek = $data['dataOperasionalProyek'];
			// $dataDetail = $data['dataDetail'];
			$query = "INSERT INTO operasional_proyek (id, id_proyek,id_bank, tgl, nama, total) VALUES (:id, :id_proyek, :id_bank, :tgl, :nama, :total);";

			$statment = $this->koneksi->prepare($query);
			$statment->bindParam(':id', $data['id']);
			$statment->bindParam(':id_proyek', $data['id_proyek']);
			$statment->bindParam(':id_bank', $data['id_bank']);
			$statment->bindParam(':tgl', $data['tgl']);
			$statment->bindParam(':nama', $data['nama']);
			$statment->bindParam(':total', $data['total']);
			$result = $statment->execute();

			return $result;
		}

		/**
		* 
		*/
		public function update($data){
			// $query = "UPDATE bank SET nama = :nama, status = :status WHERE id = :id;";

			// $statement = $this->koneksi->prepare($query);
			// $statement->bindParam(':nama', $data['nama']);
			// $statement->bindParam(':status', $data['status']);
			// $statement->bindParam(':id', $data['id']);
			// $result = $statement->execute();

			// return $result;
		}

		/**
		* 
		*/
		public function delete($id){
			// $query = "DELETE FROM bank WHERE id = :id";
			
			// $statement = $this->koneksi->prepare($query);
			// $statement->bindParam(':id', $id);
			// $result = $statement->execute();

			// return $result;
		}

		public function getLastID(){
			$query = "SELECT MAX(id) id FROM operasional_proyek;";

			$statement = $this->koneksi->prepare($query);
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