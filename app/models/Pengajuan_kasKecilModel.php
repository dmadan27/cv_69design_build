<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	* Class BankModel, implementasi ke ModelInterface
	*/
	class Pengajuan_kasKecilModel extends Database implements ModelInterface{

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
			$query = "SELECT * FROM pengajuan_kas_kecil WHERE id = :id;";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

		/**
		*
		*/
		public function getAll_pending(){
			$status = "PENDING";
			$query = "SELECT * FROM v_pengajuan_kas_kecil WHERE status = :status ORDER BY id DESC LIMIT 5";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':status', $status);
			$statement->execute();
			$result = $statement->fetchAll();

			return $result;
		}

		/**
		*
		*/
		public function getTotal_pending(){
			$status = "PENDING";
			$query = "SELECT COUNT(*) FROM pengajuan_kas_kecil WHERE status = :status";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':status', $status);
			$statement->execute();
			$result = $statement->fetchColumn();

			return $result;	
		}

		/**
		*
		*/
		public function getTotal_setujui(){
			$status = "DISETUJUI";
			$query = "SELECT COUNT(*) FROM pengajuan_kas_kecil WHERE status = :status";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':status', $status);
			$statement->execute();
			$result = $statement->fetchColumn();

			return $result;	
		}

		/**
		* 
		*/
		public function insert($data){
			$query = "INSERT INTO pengajuan_kas_kecil (id, id_kas_kecil, id_bank, tgl, nama, total, status) VALUES (:id, :id_kas_kecil, :id_bank,  :tgl, :nama, :total,  :status);";

			try{
				$this->koneksi->beginTransaction();

				$statement = $this->koneksi->prepare($query);
				$result = $statement->execute(
					array(
						':id' => $data['id'],
						':id_kas_kecil' => $data['id_kas_kecil'],
						':id_bank' => $data['id_bank'],
						':tgl' => $data['tgl'],
						':nama' => $data['nama'],
						':total' => $data['total'],
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
			$query = "UPDATE pengajuan_kas_kecil SET status = :status WHERE id = :id;";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':status', $data['status']);
			$statement->bindParam(':id', $data['id']);
			$result = $statement->execute();
			
			return $result;
		}

		/**
		* 
		*/
		public function delete($id){
			$query = "DELETE FROM pengajuan_kas_kecil WHERE id = :id";
			
			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$result = $statement->execute();

			return $result;
		}

		/**
		*
		*/
		public function getLastID(){
			$query = "SELECT MAX(id) id FROM pengajuan_kas_kecil ";

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