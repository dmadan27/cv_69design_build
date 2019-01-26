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

		/**
		* 
		*/
		public function getAll(){
			$query = "SELECT * FROM kas_besar";

			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll();

			return $result;

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
			$query = "CALL tambah_kas_besar (:id, :nama, :alamat, :no_telp, :email, :foto,  :status, :password, :level);";

			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id' => $data['id'],
					':nama' => $data['nama'],
					':alamat' => $data['alamat'],
					':no_telp' => $data['no_telp'],
					':email' => $data['email'],
					':foto' => $data['foto'],
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
			$query = "UPDATE kas_besar SET nama = :nama, alamat = :alamat, no_telp = :no_telp, email = :email, status = :status WHERE id = :id;";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':nama', $data['nama']);
			$statement->bindParam(':alamat', $data['alamat']);
			$statement->bindParam(':no_telp', $data['no_telp']);
			$statement->bindParam(':email', $data['email']);
			
			$statement->bindParam(':status', $data['status']);
			$statement->bindParam(':id', $data['id']);
			$result = $statement->execute();

			return $result;
		}

		/**
		*
		*/
		public function updateProfil($data){
			$query = "UPDATE kas_kecil SET nama = :nama, alamat = :alamat, no_telp = :no_telp WHERE id = :id;";

			try{
				$this->koneksi->beginTransaction();

				$statement = $this->koneksi->prepare($query);
				$statement->execute(
				array(
					':id' => $data['id'],
					':nama' => $data['nama'],
					':alamat' => $data['alamat'],
					':no_telp' => $data['no_telp'],
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
		public function updateFoto($data){
			$query = "UPDATE kas_besar SET foto = :foto WHERE id = :id";

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
		public function delete($id){
			try {
				$query = "CALL hapus_kas_besar (:id)";

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
		public function export(){
			$query = "SELECT id ID, nama NAMA, alamat ALAMAT, no_telp NO_TELP, email EMAIL, status STATUS FROM kas_besar ";
			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $result;

		}

		/**
		*
		*/
		public function countKasBesar(){
			$query = "SELECT count(id) FROM kas_besar";
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