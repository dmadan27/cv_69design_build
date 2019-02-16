<?php
	Defined("BASE_PATH") or die(ACCESS_DENIED);

	/**
	 * Class BankModel
	 * Implements ModelInterface
	 */
	class BankModel extends Database implements ModelInterface
	{

		private $koneksi;

		/**
		 * Method __construct
		 * Open connection to DB
		 */
		public function __construct() {
			$this->koneksi = $this->openConnection();
		}

		/**
		 * Method getAll
		 * Proses get semua data bank
		 * @return result {array}
		 */
		public function getAll() {
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
		public function getById($id) {
			$query = "SELECT * FROM bank WHERE id = :id;";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

		/**
		 * Method export
		 * Proses get data bank khusus untuk export
		 * @return result {array}
		 */
		public function export_mutasi($id, $tgl_awal, $tgl_akhir) {
			$query = "SELECT * FROM v_mutasi_bank_export WHERE TANGGAL BETWEEN :tgl_awal AND :tgl_akhir AND id_bank = :id;";

			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id' => $id,
					':tgl_awal' => $tgl_awal,
					':tgl_akhir' => $tgl_akhir
				)
			);
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}

		/**
		 * Method insert
		 * Proses insert data bank
		 * @param data {array}
		 * @return result {array}
		 */
		public function insert($data) {
			// $query = "INSERT INTO bank (nama, saldo, status) VALUES (:nama, :saldo, :status);";
			$query = "CALL p_tambah_bank (:nama, :saldo, :status, :created_by);";

			try {
				$this->koneksi->beginTransaction();

				$statement = $this->koneksi->prepare($query);
				$statement->execute(
					array(
						':nama' => $data['nama'],
						':saldo' => $data['saldo'],
						':status' => $data['status'],
						'created_by' => $data['created_by']
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
		 * Method update
		 * Proses update data bank
		 * @param data {array}
		 * @return result {array}
		 */
		public function update($data) {
			// $query = "UPDATE bank SET nama = :nama, status = :status WHERE id = :id;";
			$query = "CALL p_edit_bank (:id, :nama, :status, :modified_by)";
			try {
				$this->koneksi->beginTransaction();

				$statement = $this->koneksi->prepare($query);
				$statement->execute(
					array(
						':id' => $data['id'],
						':nama' => $data['nama'],
						':status' => $data['status'],
						':modified_by' => $data['modified_by'],
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
		 * Method delete
		 * Proses penghapusan data bank beserta data yang berelasi denganya
		 * @param id {string}
		 * @return result {array}
		 */
		public function delete($id) {
			$query = "CALL p_hapus_bank (:id);";
			
			try {
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
			catch(PDOException $e) {
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
		public function export() {
			$query = "SELECT id ID, nama NAMA, saldo SALDO, status STATUS FROM bank ";
			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}

		/**
		 * Method __destruct
		 * Close connection to DB
		 */
		public function __destruct() {
			$this->closeConnection($this->koneksi);
		}

	}