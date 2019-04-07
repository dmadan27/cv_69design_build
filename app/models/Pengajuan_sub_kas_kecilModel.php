<?php
	Defined("BASE_PATH") or die(ACCESS_DENIED);

	/**
	 * 
	 */
	class Pengajuan_sub_kas_kecilModel extends Database {

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
			$query = "SELECT * FROM pengajuan_sub_kas_kecil";

			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll();

			return $result;
		}

		/**
		 * 
		 */
		public function getById($id) {
			$query = "SELECT * FROM v_pengajuan_sub_kas_kecil_v2 WHERE id = :id;";

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
		public function getEstimasiPengajuan_byId($id) {
			$query = "SELECT * FROM vp_estimasi_pengeluaran_skk WHERE id_sub_kas_kecil = :id;";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

		/**
		 * 
		 */
		public function getAll_pending() {
			$status = "1";
			$query = "SELECT pskc.id, skc.id id_skc, skc.nama nama_skc, pskc.total FROM pengajuan_sub_kas_kecil pskc ";
			$query .= "JOIN sub_kas_kecil skc ON skc.id = pskc.id_sub_kas_kecil WHERE pskc.status = :status ORDER BY id DESC LIMIT 5";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':status', $status);
			$statement->execute();
			$result = $statement->fetchAll();

			return $result;
		}

		/**
		 * 
		 */
		public function getTotal_pending() {
			$status = "1";
			$query = "SELECT COUNT(*) FROM pengajuan_sub_kas_kecil WHERE status = :status";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':status', $status);
			$statement->execute();
			$result = $statement->fetchColumn();

			return $result;
		}

		/**
		 * 
		 */
		public function getLastID($id_pengajuan) {
			// $query = "SELECT MAX(id) as id from pengajuan_sub_kas_kecil WHERE id LIKE :id_pengajuan"."%";
			$id_pengajuan .= "%";
			$query = "SELECT MAX(id) as id from pengajuan_sub_kas_kecil WHERE id LIKE :id_pengajuan";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id_pengajuan', $id_pengajuan);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

		/**
		 * Method acc_pengajuan
		 * @param data {array}
		 */
		public function acc_pengajuan($data) {
			try {
				$this->koneksi->beginTransaction();
				
				$query	= "CALL p_acc_pengajuan_sub_kas_kecil (:id, :id_kas_kecil, ";
				$query .= ":tgl, :dana_disetujui, :status, :modified_by)";

				$statment = $this->koneksi->prepare($query);
				$statment->execute(
					array(
						':id' => $data['id'],
						':id_kas_kecil' => $data['id_kas_kecil'],
						':tgl' => $data['tgl'],
						':dana_disetujui' => $data['dana_disetujui'],
						':status' => $data['status'],
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
		public function update_status($data) {

			try {
				$this->koneksi->beginTransaction();

				$query	= "UPDATE pengajuan_sub_kas_kecil SET status = :status, modified_by = :modified_by WHERE id = :id";

				$statment = $this->koneksi->prepare($query);
				$statment->execute(
					array(
						':id' => $data['id'],
						':status' => $data['status'],
						':modified_by' => $data['modified_by']
					)
				);
				$statment->closeCursor();

				$this->koneksi->commit();

				return array(
					'success' => true,
					'error' => NULL
				);
			} 
			catch (PDOException $e) {
				$this->koneksi->rollback();
				return array(
					'success' => false,
					'error' => $e->getMessage()
				);
			}
		}

		// ======================== MODEL EXPORT ======================================

		/**
		 * 
		 */
		public function getByTglExport($tgl) {
			$tgl = "%".$tgl;

			$query = "SELECT * FROM v_pengajuan_sub_kas_kecil_export WHERE `TANGGAL PENGAJUAN` LIKE :tgl";
			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':tgl', $tgl);
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}

		/**
		 * 
		 */
		public function getByIdSKKTglExport($id_skk, $tgl) {
			$id_skk = "%".$id_skk."%";
			$tgl = "%".$tgl;

			$query = "SELECT * FROM v_pengajuan_sub_kas_kecil_export WHERE `ID PENGAJUAN` LIKE :id_skk AND `TANGGAL PENGAJUAN` LIKE :tgl";
			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id_skk', $id_skk);
			$statement->bindParam(':tgl', $tgl);
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}

		/**
		 * 
		 */
		public function export($tgl_awal = false, $tgl_akhir = false, $id_proyek = false, $id_pengajuan = false) {
			if($id_pengajuan) {
				$query = "SELECT * FROM v_pengajuan_sub_kas_kecil_export_v2 WHERE `ID PENGAJUAN` = :id_pengajuan;";
				$bindParam = array(
					':id_pengajuan' => $id_pengajuan
				);
			}
			else {
				$query = "SELECT * FROM v_pengajuan_sub_kas_kecil_export_v2 WHERE `ID PROYEK` = :id_proyek ";
				$query .= "AND (`TANGGAL PENGAJUAN` BETWEEN :tgl_awal AND :tgl_akhir));";
				$bindParam = array(
					':id_proyek' => $id_proyek,
					':tgl_awal' => $tgl_awal,
					':tgl_akhir' => $tgl_akhir,
				);
			}

			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				$bindParam
			);
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}

		/**
		 * 
		 */
		public function export_detail($tgl_awal = false, $tgl_akhir = false, $id_proyek = false, $id_pengajuan = false) {
			if($id_pengajuan) {
				$query = "SELECT * FROM v_export_detail_pengajuan_skk WHERE `ID PENGAJUAN` = :id_pengajuan;";
				$bindParam = array(
					':id_pengajuan' => $id_pengajuan
				);
			}
			else {
				$query = "SELECT * FROM v_export_detail_pengajuan_skk WHERE `ID PENGAJUAN` IN ";
				$query .= "(SELECT id FROM pengajuan_sub_kas_kecil WHERE id_proyek = :id_proyek ";
				$query .= "AND (tgl BETWEEN :tgl_awal AND :tgl_akhir));";
				$bindParam = array(
					':id_proyek' => $id_proyek,
					':tgl_awal' => $tgl_awal,
					':tgl_akhir' => $tgl_akhir,
				);
			}

			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				$bindParam
			);
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}

		// ======================= END MODEL EXPORT ===================================

		/**
		 * 
		 */
		public function __destruct(){
			$this->closeConnection($this->koneksi);
		}
	}
