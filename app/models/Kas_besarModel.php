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
			try{
				$this->koneksi->beginTransaction();

				$this->insertKasBesar($data);

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
		public function insertKasBesar($data){
			$level = "KAS BESAR";
			$query = "CALL tambah_kas_besar (:id, :nama, :alamat, :no_telp, :email, :foto, :saldo, :status, :password,:level);";

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
		public function getLastID(){
			$query = "SELECT MAX(id) id FROM kas_besar;";

			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

		/**
		*
		*/
		public function checkExistEmail($email){
			$query = "SELECT COUNT(*) total FROM kas_besar WHERE email =:email";

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
		public function __destruct(){
			$this->closeConnection($this->koneksi);
		}
	}