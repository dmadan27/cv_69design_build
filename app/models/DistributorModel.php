<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
	/**
	*	Class DistributorModel, implementasi ModelInterface
	*/

	class DistributorModel extends Database implements ModelInterface{

		protected $koneksi;
		protected $dataTable;

		/**
		* fungsi yang dijalankan saat memanggil kelas model
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
			$query = "SELECT * FROM distributor";

			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll();

			return $result;
		}

		/**
		* 
		*/
		public function getById($id){
			$query = "SELECT * FROM distributor WHERE id = :id;";

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
			$query = "INSERT INTO distributor (id, nama, alamat, no_telp, pemilik, status) VALUES (:id, :nama, :alamat,  :no_telp, :pemilik, :status);";

			try{
				$this->koneksi->beginTransaction();

				$statement = $this->koneksi->prepare($query);
				$result = $statement->execute(
					array(
						':id' => $data['id'],
						':nama' => $data['nama'],
						':alamat' => $data['alamat'],
						':no_telp' => $data['no_telp'],
						':pemilik' => $data['pemilik'],
						':status' => $data['status']
					)
				);
				$statement->closeCursor();

				$this->koneksi->commit();

				return true;
			}
			catch(PDOException $e){
				$this->koneksi->rollback();
				die($e->getMessage());
				// return false;
			}
			
		}

		/**
		* 
		*/
		public function update($data){
			$query = "UPDATE distributor SET
			 nama = :nama, alamat = :alamat,  no_telp = :no_telp, pemilik = :pemilik, status = :status WHERE id = :id;";

			try{
				$this->koneksi->beginTransaction();

				$statement = $this->koneksi->prepare($query);
				$statement->execute(
					array(

						':nama' => $data['nama'],
						':alamat' => $data['alamat'],
						':no_telp' => $data['no_telp'],
						':pemilik' => $data['pemilik'],
						':status' => $data['status'],
						':id' => $data['id'],
					)
				);
				$statement->closeCursor();

				$this->koneksi->commit();

				return true;
			}
			catch(PDOException $e){
				$this->koneksi->rollback();
				die($e->getMessage());
				// return false;
			}

			
		}


		/**
		* 
		*/
		public function delete($id){
			$query = "DELETE FROM distributor WHERE id = :id";
			
			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$result = $statement->execute();

			return $result;
			
		}
		

		public function export(){
			$query = "SELECT id ID, nama NAMA, alamat ALAMAT,  no_telp NO_TELP, pemilik PEMILIK, status STATUS FROM distributor ";
			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		

		}

		public function getLastID(){
			$query = "SELECT MAX(id) id FROM distributor";

			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

		public function countDistributor(){
			$query = "SELECT count(id) FROM distributor";
			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $result;			 
		}

		/**
		* 
		*/
		public function __destruct(){
			$this->closeConnection($this->koneksi);
		}
	}
