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
			$query = "SELECT * FROM sub_kas_kecil";

			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll();

			return $result;
		}

		/**
		*
		*/
		public function getById($id){
			$query = "SELECT * FROM sub_kas_kecil WHERE id = :id;";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

		/**
		*
		*/
		public function getSaldoById($id){
			$query = "SELECT saldo FROM sub_kas_kecil WHERE id = :id";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

		/**
		*
		*/
		public function getSisaSaldoById($id){
			$query = "SELECT skk.saldo, (skk.saldo - SUM(dpskk.subtotal)) sisa_saldo FROM sub_kas_kecil skk  ";
			$query .= "JOIN pengajuan_sub_kas_kecil pskk ON skk.id=pskk.id_sub_kas_kecil ";
			$query .= "JOIN detail_pengajuan_sub_kas_kecil dpskk ON pskk.id=dpskk.id_pengajuan ";
			$query .= "WHERE skk.id = :id AND (pskk.status ='DISETUJUI' OR pskk.status='LANGSUNG') AND pskk.status_laporan IS NULL GROUP BY skk.id";

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
			try{
				$this->koneksi->beginTransaction();

				$this->insertSubKasKecil($data);

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
		private function insertSubKasKecil($data){
			$level = "SUB KAS KECIL";
			$query = "CALL tambah_sub_kas_kecil (:id, :nama, :alamat, :no_telp, :email, :foto, :saldo, :status, :password, :level);";

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
					':status' => $data['status'],
					':password' => $data['password'],
					':level' => $level,
				)
			);
			$statement->closeCursor();
		}

		/**
		*
		*/
		public function update($data){
			$query = "UPDATE sub_kas_kecil SET nama = :nama, alamat = :alamat, no_telp = :no_telp, email = :email, status = :status WHERE id = :id;";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':nama', $data['nama']);
			$statement->bindParam(':alamat', $data['alamat']);
			$statement->bindParam(':no_telp', $data['no_telp']);
			$statement->bindParam(':status', $data['status']);
			$statement->bindParam(':id', $data['id']);
			$result = $statement->execute();

			return $result;
		}

		/**
		*
		*/
		public function updateFoto($data){
			$query = "UPDATE sub_kas_kecil SET foto = :foto WHERE id = :id";

			try{
				$this->koneksi->beginTransaction();

				$statement = $this->koneksi->prepare($query);
				$statement->execute(
					array(
						':foto' => $data['foto'],
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
		public function updatePassword($id, $password){
			try {
				$this->koneksi->beginTransaction();

				$query	= "UPDATE sub_kas_kecil SET password = :password WHERE id = :id";

				$statment = $this->koneksi->prepare($query);
				$statment->execute(
					array(
						':id' => $id,
						':password' => $password,
					)
				);
				$statment->closeCursor();

				$this->koneksi->commit();

				return true;
			} catch (PDOException $e) {
				$this->koneksi->rollback();
				return $e->getMessage();
			}
		}

		/**
		*
		*/
		public function delete($id){
			$query = "DELETE FROM sub_kas_kecil WHERE id = :id";
			
			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$result = $statement->execute();

			return $result;

		}

		/**
		*
		*/
		public function getLastID(){
			$query = "SELECT MAX(id) id FROM sub_kas_kecil;";

			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
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


		/* ============================== mobile ===================================== */

		/**
		*
		*/
		public function getByIdFromV($id_skk) {
			$query = "SELECT * FROM v_sub_kas_kecil WHERE id=:id_skk;";

			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id_skk' => $id_skk,
				)
			);

			return $statement->fetch(PDO::FETCH_ASSOC);
		}

		public function updateProfil_mobile($id, $telepon, $alamat) {
			$query = "UPDATE sub_kas_kecil SET alamat = :alamat, no_telp = :no_telp WHERE id = :id;";

			try {

				$this->koneksi->beginTransaction();
				$statement = $this->koneksi->prepare($query);
				$statement->execute(
					array(
						':alamat' => $alamat,
						':no_telp' => $telepon,
						':id' => $id,
					)
				);
				$statement->closeCursor();
				$this->koneksi->commit();

				return true;
			} catch (PDOException $e) {
				$this->koneksi->rollback();
				return $e->getMessage();
			}
		}
		/**
		*
		*/
		public function export(){
			$query = "SELECT id ID, nama NAMA, alamat ALAMAT, no_telp NO_TELP, email EMAIL, saldo SALDO, status STATUS FROM sub_kas_kecil ";
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
