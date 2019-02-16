<?php
	Defined("BASE_PATH") or die(ACCESS_DENIED);

	/**
	 * 
	 */
	class Laporan_sub_kas_kecilModel extends Database
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

		}

		/**
		 * 
		 */
		public function getById($id) {
			$query = "SELECT * FROM v_laporan_pengajuan_sub_kas_kecil WHERE id = :id;";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

		/**
		 * 
		 */
		public function getDetailById($id) {
			$query = "SELECT * FROM detail_pengajuan_sub_kas_kecil WHERE id_pengajuan = :id;";
			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);

			return $result;
		}

		/**
		 * 
		 */
		public function getBuktiLaporanById($id) {
			$query = "SELECT * FROM upload_laporan_pengajuan_sub_kas_kecil WHERE id_pengajuan = :id;";
			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);

			return $result;
		}

		/**
		 * 
		 */
		public function update_laporan($data) {
			try {
				$this->koneksi->beginTransaction();

				$query	= "UPDATE pengajuan_sub_kas_kecil SET status_laporan = :status_laporan, modified_by = :modified_by WHERE id = :id";

				$statment = $this->koneksi->prepare($query);
				$statment->execute(
					array(
						':id' => $data['id'],
						':status_laporan' => $data['status_laporan'],
						':modified_by' => $data['modified_by']
					)
				);
				$statment->closeCursor();

				$this->koneksi->commit();

				return array(
					'success' => true,
					'error' => NULL
				);
			} catch (PDOException $e) {
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
		public function perbaiki_laporan($data) {
			try {
				$this->koneksi->beginTransaction();
				
				$query	= "CALL p_ganti_status_perbaiki_laporan_sub_kas_kecil (:id, :id_sub_kas_kecil, :tgl, :modified_by);";

				$statment = $this->koneksi->prepare($query);
				$statment->execute(
					array(
						':id' => $data['id'],
						':id_sub_kas_kecil' => $data['id_sub_kas_kecil'],
						':tgl' => date('Y-m-d'),
						':modified_by' => $data['modified_by']
					)
				);
				$statment->closeCursor();

				$this->koneksi->commit();

				return array(
					'success' => true,
					'error' => NULL
				);
			} catch (PDOException $e) {
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
		public function __destruct() {
			$this->closeConnection($this->koneksi);
		}
	}
