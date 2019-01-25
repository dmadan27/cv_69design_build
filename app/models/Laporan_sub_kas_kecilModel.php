<?php
	Defined("BASE_PATH") or die(ACCESS_DENIED);

	/**
	 * 
	 */
	class Laporan_sub_kas_kecilModel extends Database{

		private $koneksi;
		private $dataTable;

		/**
		 * 
		 */
		public function __construct(){
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
		public function __destruct(){
			$this->closeConnection($this->koneksi);
		}
	}
