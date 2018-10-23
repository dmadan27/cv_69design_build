<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	 * Class BankModel
	 * Implements ModelInterface
	 */
	class BankModel extends Database implements ModelInterface{

		protected $koneksi;
		protected $dataTable;

		/**
		 * Method __construct
		 * Open connection to DB
		 * Access library dataTable
		 */
		public function __construct(){
			$this->koneksi = $this->openConnection();
			$this->dataTable = new Datatable();
		}

		// ======================= dataTable ======================= //
		
			/**
			 * Method getAllDataTable
			 * @param config {array}
			 * @return result {array}
			 */
			public function getAllDataTable($config){
				$this->dataTable->set_config($config);
				$statement = $this->koneksi->prepare($this->dataTable->getDataTable());
				$statement->execute();
				$result = $statement->fetchAll();

				return $result;
			}

			/**
			 * Method recordFilter
			 * @return result {int}
			 */
			public function recordFilter(){
				return $this->dataTable->recordFilter();

			}

			/**
			 * Method recordTotal
			 * @return result {int}
			 */
			public function recordTotal(){
				return $this->dataTable->recordTotal();
			}

		// ========================================================= //

		/**
		 * Method getAll
		 * Proses get semua data bank
		 * @return result {array}
		 */
		public function getAll(){
			$query = "SELECT * FROM bank";

			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);

			return $result;
		}

		/**
		 * Method getById
		 * Proses get data bank berdasarkan id
		 * @param id {string}
		 * @return result {array}
		 */
		public function getById($id){
			$query = "SELECT * FROM bank WHERE id = :id;";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

		/**
		 * Method insert
		 * Proses insert data bank
		 * @param data {array}
		 * @return result {array}
		 */
		public function insert($data){
			$query = "INSERT INTO bank (nama, saldo, status) VALUES (:nama, :saldo, :status);";

			try{
				$this->koneksi->beginTransaction();

				$statement = $this->koneksi->prepare($query);
				$result = $statement->execute(
					array(
						':nama' => $data['nama'],
						':saldo' => $data['saldo'],
						':status' => $data['status']
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
		 * Method update
		 * Proses update data bank
		 * @param data {array}
		 * @return result {array}
		 */
		public function update($data){
			$query = "UPDATE bank SET nama = :nama, status = :status WHERE id = :id;";

			try{
				$this->koneksi->beginTransaction();

				$statement = $this->koneksi->prepare($query);
				$statement->execute(
					array(
						':nama' => $data['nama'],
						':status' => $data['status'],
						':id' => $data['id'],
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
		 * Method delete
		 * Proses penghapusan data bank beserta data yang berelasi denganya
		 * @param id {string}
		 * @return result {array}
		 */
		public function delete($id){
			$query = "CALL hapus_bank (:id);";
			
			try{
				$this->koneksi->beginTransaction();

				$statement = $this->koneksi->prepare($query);
				$statement->execute(
					array(
						':id' => $id
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
		 * Method export
		 * Proses get data bank khusus untuk export
		 * @return result {array}
		 */
		public function export(){
			$query = "SELECT id ID, nama NAMA, saldo SALDO, status STATUS FROM bank ";
			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $result;

		}

		public function countBank(){
			$query = "SELECT count(id) FROM bank";
			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $result;			 
		}

		/**
		 * Method __destruct
		 * Close connection to DB
		 */
		public function __destruct(){
			$this->closeConnection($this->koneksi);
		}

	}