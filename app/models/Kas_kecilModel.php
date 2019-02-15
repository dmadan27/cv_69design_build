<?php
	Defined("BASE_PATH") or die(ACCESS_DENIED);

	/**
	 * 
	 */
	class Kas_kecilModel extends Database implements ModelInterface
	{

		private $koneksi;

		/**
		 * 
		 */
		public function __construct() {
			$this->koneksi = $this->openConnection();
		}

		/**
		 * 
		 */
		public function getAll() {
			$query = "SELECT * FROM kas_kecil";
			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll();

			return $result;	
		}

		/**
		 * 
		 */
		public function getById($id) {
			$query = "SELECT * FROM kas_kecil WHERE id = :id;";
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
			try {
				$this->koneksi->beginTransaction();

				$this->insertKasKecil($data);

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
		private function insertKasKecil($data) {
			$level = "KAS KECIL";
			$query = "CALL p_tambah_kas_kecil (:id, :nama, :alamat, :no_telp, :email, :foto, :saldo, :tgl, :status, :password, :level, :created_by);";

			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id' => $data['id'],
					':nama' => $data['nama'],
					':alamat' => $data['alamat'],
					':no_telp' => $data['no_telp'],
					':email' => $data['email'],
					':foto' => $data['foto'],
					':saldo' => $data['saldo'],
					':tgl' => date('Y-m-d'),
					':status' => $data['status'],
					':password' => $data['password'],
					':level' => $level,
					':created_by' => $data['created_by']
				)
			);
			$statement->closeCursor();
		}

		/**
		 * 
		 */
		public function update($data) {
			// $query = "UPDATE kas_kecil SET nama = :nama, alamat = :alamat, no_telp = :no_telp, email = :email, status = :status WHERE id = :id;";
			$query = "CALL p_edit_kas_kecil (:id, :nama, :alamat, :no_telp, :status, :modified_by);";

			try {
				$this->koneksi->beginTransaction();
				$statement = $this->koneksi->prepare($query);
				$statement->execute(
					array(
						':nama' => $data['nama'],
						':alamat' => $data['alamat'],
						':no_telp' => $data['no_telp'],
						':status' => $data['status'],
						':id' => $data['id'],
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
		public function updateProfil($data) {
			$query = "UPDATE kas_kecil SET nama = :nama, alamat = :alamat, no_telp = :no_telp, modified_by = :modified_by WHERE id = :id;";

			try {
				$this->koneksi->beginTransaction();

				$statement = $this->koneksi->prepare($query);
				$statement->execute(
					array(
						':id' => $data['id'],
						':nama' => $data['nama'],
						':alamat' => $data['alamat'],
						':no_telp' => $data['no_telp'],
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
		public function updateFoto($data) {
			$query = "UPDATE kas_kecil SET foto = :foto, modified_by = :modified_by WHERE id = :id";

			try {
				$this->koneksi->beginTransaction();

				$statement = $this->koneksi->prepare($query);
				$statement->execute(
					array(
						':foto' => $data['foto'],
						':id' => $data['id'],
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
			catch(PDOException $e){
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
			try {
				$query = "CALL p_hapus_kas_kecil (:id)";

				$this->koneksi->beginTransaction();

				$statement = $this->koneksi->prepare($query);
				$statement->execute(
					array(':id' => $id)
				);
				$statement->closeCursor();

				$this->koneksi->commit();

				return array(
					'success' => true,
					'error' => NULL
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
		public function getLastID() {
			$query = "SELECT MAX(id) id FROM kas_kecil;";

			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

		
		/**
		 * 
		 */
		public function checkExistEmail($email) {
			$query = "SELECT COUNT(*) total FROM kas_kecil WHERE email =:email";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':email', $email);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			if($result['total'] > 0) return false;
			else return true;
		}

		/**
		 * 
		 */
		public function export() {
			$query = "SELECT id ID, nama NAMA, alamat ALAMAT, no_telp NO_TELP, email EMAIL, saldo SALDO, status STATUS FROM kas_kecil ";
			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}

		/**
		 * 
		 */
		public function __destruct() {
			$this->closeConnection($this->koneksi);
		}
	}