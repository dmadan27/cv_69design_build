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
			$query = "SELECT skk.saldo, (skk.saldo - SUM(pskk.total)) sisa_saldo FROM sub_kas_kecil skk ";
			$query .= "JOIN pengajuan_sub_kas_kecil pskk ON skk.id=pskk.id_sub_kas_kecil ";
			$query .= "WHERE skk.id = :id AND pskk.status = 'DISETUJUI' AND pskk.status_laporan IS NULL GROUP BY skk.id";

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
		public function updateFoto($id){

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

		/**
		* 
		*/
		public function __destruct(){
			$this->closeConnection($this->koneksi);
		}
	}