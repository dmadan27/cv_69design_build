<?php
	Defined("BASE_PATH") or die(ACCESS_DENIED);
	
	/**
	 * Class DistributorModel, implementasi ModelInterface
	 */
	class DistributorModel extends Database implements ModelInterface
	{

		protected $koneksi;

		/**
		 * fungsi yang dijalankan saat memanggil kelas model
		 */
		public function __construct() {
			$this->koneksi = $this->openConnection();
		}

		/**
		 * 
		 */
		public function getAll() {
			$query = "SELECT * FROM distributor";

			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll();

			return $result;
		}

		/**
		 * 
		 */
		public function getById($id) {
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
		public function insert($data) {
			// $query = "INSERT INTO distributor (id, nama, alamat, no_telp, pemilik, status) VALUES (:id, :nama, :alamat,  :no_telp, :pemilik, :status);";
			$query = "CALL p_tambah_distributor (:id, :nama, :alamat, :no_telp, :pemilik, :status, :created_by);";
			try {
				$this->koneksi->beginTransaction();

				$statement = $this->koneksi->prepare($query);
				$result = $statement->execute(
					array(
						':id' => $data['id'],
						':nama' => $data['nama'],
						':alamat' => $data['alamat'],
						':no_telp' => $data['no_telp'],
						':pemilik' => $data['pemilik'],
						':status' => $data['status'],
						':created_by' => $data['created_by']
					)
				);
				$statement->closeCursor();

				$this->koneksi->commit();

				return array(
					'success' => true,
					'error' => null
				);
			}
			catch(PDOException $e) {
				$this->koneksi->rollback();
				return array(
					'success' => false,
					'error' => $e->getMessage()
				);
			}
			
		}

		/**
		 * 
		 */
		public function update($data) {
			// $query = "UPDATE distributor SET
			//  nama = :nama, alamat = :alamat,  no_telp = :no_telp, pemilik = :pemilik, status = :status WHERE id = :id;";
			$query = "CALL p_edit_distributor (:id, :nama, :alamat, :no_telp, :pemilik, :status, :modified_by);";
			try {
				$this->koneksi->beginTransaction();

				$statement = $this->koneksi->prepare($query);
				$statement->execute(
					array(
						':id' => $data['id'],
						':nama' => $data['nama'],
						':alamat' => $data['alamat'],
						':no_telp' => $data['no_telp'],
						':pemilik' => $data['pemilik'],
						':status' => $data['status'],
						':modified_by' => $data['modified_by']
					)
				);
				$statement->closeCursor();

				$this->koneksi->commit();

				return array(
					'success' => true,
					'error' => null
				);
			}
			catch(PDOException $e) {
				$this->koneksi->rollback();
				return array(
					'success' => false,
					'error' => $e->getMessage()
				);
			}
		}


		/**
		 * 
		 */
		public function delete($id) {
			// $query = "DELETE FROM distributor WHERE id = :id";
			$query = "CALL p_hapus_distributor (:id);";
			
			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$result = $statement->execute();

			return $result;
			
		}
		
		/**
		 * 
		 */
		public function export() {
			$query = "SELECT id ID, nama NAMA, alamat ALAMAT,  no_telp NO_TELP, pemilik PEMILIK, status STATUS FROM distributor ";
			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}

		/**
		 * 
		 */
		public function getLastID() {
			$query = "SELECT MAX(id) id FROM distributor";

			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

		public function countDistributor() {
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
