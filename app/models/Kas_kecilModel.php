<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	* 
	*/
	class Kas_kecilModel extends Database implements ModelInterface{

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
		public function getAll(){
			
		}
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
		public function insert($data){
			try{
				$this->koneksi->beginTransaction();

				$this->insertKasKecil($data);

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
		public function insertKasKecil($data){
			$level = "KAS KECIL";
			$query = "CALL tambah_kas_kecil (:id, :nama, :alamat, :no_telp, :email, :foto, :saldo, :status, :password,:level);";

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
		public function getById($id){
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
		public function update($data){
			// $query = "UPDATE kas_kecil SET nama = :nama, status = :status WHERE id = :id;";

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
			
		}

		/**
		*
		*/
		public function getLastID(){
			$query = "SELECT MAX(id) id FROM kas_kecil;";

			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

		
		/**
		*
		*/
		public function checkExistEmail($email){
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
		public function __destruct(){
			$this->closeConnection($this->koneksi);
		}
	}