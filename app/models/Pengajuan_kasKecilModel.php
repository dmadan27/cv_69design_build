<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	 * Class BankModel, implementasi ke ModelInterface
	 */
	class Pengajuan_kasKecilModel extends Database implements ModelInterface
	{

		protected $koneksi;

		/**
		 * 
		 */
		public function __construct(){
			$this->koneksi = $this->openConnection();
		}

		/**
		 * 
		 */
		public function getAll(){
			
		}

		/**
		 * 
		 */
		public function getById($id) {
			$query = "	SELECT pengajuan_kas_kecil.id, pengajuan_kas_kecil.id_kas_kecil, 
							 kas_kecil.nama AS 'kas_kecil', pengajuan_kas_kecil.tgl, pengajuan_kas_kecil.nama,
							 pengajuan_kas_kecil.total, pengajuan_kas_kecil.total_disetujui, pengajuan_kas_kecil.status 
					FROM pengajuan_kas_kecil 
					JOIN kas_kecil ON kas_kecil.id = pengajuan_kas_kecil.id_kas_kecil
					WHERE pengajuan_kas_kecil.id = :id;";

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
			$status = "0";
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
		public function getTotal_pending() {
			$status = "0";
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
		public function getTotal_setujui() {
			$status = "2";
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
		public function getSaldoKK($id) {
			$query = "SELECT saldo FROM kas_kecil WHERE id = :id";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$statement->execute();
			$result = $statement->fetchColumn();

			return $result;	
		}

		public function export_by_id_bulan_tahun($id, $tgl) {
			$query = "SELECT `ID PENGAJUAN`, `KAS KECIL`, PENGAJUAN, TANGGAL, `TOTAL PENGAJUAN`, `TOTAL DISETUJUI`, `STATUS` FROM v_pengajuan_kas_kecil_export ";
			$query .= "WHERE TANGGAL LIKE :tgl ";
			$query .= "AND id=:id ORDER BY `ID PENGAJUAN` DESC;";

			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id' => $id,
					':tgl' => $tgl,
				)
			);
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}

		/**
		 * 
		 */
		public function getExport($tgl_awal, $tgl_akhir) {

			$level = $_SESSION['sess_level'];
			$id = $_SESSION['sess_id'];
			
			if($level == "KAS BESAR"){
				
				if($tgl_awal == '' && $tgl_akhir == ''){
					$query = "SELECT * FROM v_pengajuan_kas_kecil_export";
					$statement = $this->koneksi->prepare($query);
					$statement->execute();
				} else {
					$query = "SELECT * FROM v_pengajuan_kas_kecil_export WHERE TANGGAL BETWEEN :tgl_awal AND :tgl_akhir;";
					$statement = $this->koneksi->prepare($query);
					$statement->execute(
						array(
							':tgl_awal' => $tgl_awal,
							':tgl_akhir' => $tgl_akhir
						)
					);
				}

			} else if($level == "KAS KECIL") {

				if($tgl_awal == '' && $tgl_akhir == ''){
					$query = "SELECT * FROM v_pengajuan_kas_kecil_export WHERE id_kas_kecil = :id;";
					$statement = $this->koneksi->prepare($query);
					$statement->execute(
						array(
							':id' => $id,
						)
					);
				} else {
					$query = "SELECT * FROM v_pengajuan_kas_kecil_export WHERE TANGGAL BETWEEN :tgl_awal AND :tgl_akhir AND id = :id;";
					$statement = $this->koneksi->prepare($query);
					$statement->execute(
						array(
							':id' => $id,
							':tgl_awal' => $tgl_awal,
							':tgl_akhir' => $tgl_akhir
						)
					);
				}
			}

			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	
			$row = array();

			foreach ($result as $data){
				if($data['STATUS'] == '0'){
					$data['STATUS'] = "PENDING";
				} else if($data['STATUS'] == '1'){
					$data['STATUS'] = "DIPERBAIKI";
				} else if($data['STATUS'] == '2'){
					$data['STATUS'] = "DISETUJUI";
				} else if($data['STATUS'] == '3'){
					$data['STATUS'] = "DITOLAK";
				}	
				$row[] = $data;
			}

			return $row;
		}

		/**
		 * 
		 */
		public function insert($data) {

			$response = true;

			try {
				$this->koneksi->beginTransaction();
				$saldoKK = $this->getSaldoKK($_SESSION['sess_id']);
				//Cek Apakah Saldo KK masih mencukupi?
				if($data['total'] <= $saldoKK){
					$response = false;
				} else {
					//Edit Pengajuan
					$this->tambahPengajuan($data);
				}

				if($response) {
					$output = array(
						'success' => true,
						'tolakdana' => false,
						'error' => NULL
					);
				} else if(!$response) {
					$output = array(
						'success' => false,
						'tolakdana' => true,
						'error' => NULL
					);
				}

				$this->koneksi->commit();
				return $output;
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
		private function tambahPengajuan($data) {
			$query = "CALL p_tambah_pengajuan_kas_kecil(
				:id, :id_kas_kecil,
				:tgl,
				:nama,
				:total,
				:status,
				:created_by
			)";
			$statement = $this->koneksi->prepare($query);
			$result = $statement->execute(
				array(
					':id' => $data['id'],
					':id_kas_kecil' => $data['id_kas_kecil'],
					':tgl' => $data['tgl'],
					':nama' => $data['nama'],
					':total' => $data['total'],
					':status' => $data['status'],
					':created_by' => $_SESSION['sess_email']
				)
			);
			$statement->closeCursor();
		}

		/**
		 * 
		 */
		public function update($data) {
			
			$response = true;

			try {
				$this->koneksi->beginTransaction();

				//Define User Level
				$level = $_SESSION['sess_level'];

				//Jika User Kas Besar, masuk ke kondisi acc pengajuan
				//Jika User Kas Kecil, masuk ke kondisi edit pengajuan
				if($level == "KAS BESAR"){
					if($data['status'] == "2"){
						//Acc Pengajuan
						$this->accPengajuan($data);
					} else {
						//Review Pengajuan
						$this->revPengajuan($data);
					}
				} else if($level == "KAS KECIL") {
					$saldoKK = $this->getSaldoKK($_SESSION['sess_id']);
					//Cek Apakah Saldo KK masih mencukupi?
					if($data['total'] <= $saldoKK){
						$response = false;
					} else {
						//Edit Pengajuan
						$this->editPengajuan($data);
					}
				}

				if($response){
					$output = array(
						'success' => true,
						'tolakdana' => false,
						'error' => NULL
					);
				} else if(!$response){
					$output = array(
						'success' => false,
						'tolakdana' => true,
						'error' => NULL
					);
				}
				
				$this->koneksi->commit();
				return $output;

			} catch(PDOException $e){
				$this->koneksi->rollback();
				die($e->getMessage());
				// return false;
			}

		}

		/**
		* 	Acc Pengajuan Kas Kecil. Dilakukkan Oleh Kas Besar
		*/
		private function accPengajuan($data) {
			
			$uang = number_format($data['total_disetujui'],2,",",".");

			$ket_mutasi = "UANG KELUAR SEBESAR Rp. ".$uang." DARI TRANSAKSI DI PENGAJUAN KAS KECIL DENGAN ID ".$data['id'];
			$ket_mutasi_kk = "UANG MASUK SEBESAR Rp. ".$uang." DARI TRANSAKSI DI PENGAJUAN KAS KECIL DENGAN ID ".$data['id'];

			$query = "CALL p_acc_pengajuan_kas_kecil (
				:id, 
				:id_kas_kecil,
				:tgl_param,
				:id_bank,
				:total_disetujui,
				:ket_kas_kecil,
				:ket,
				:status,
				:modified_by
			);";
			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id'				=> $data['id'], 
					':id_kas_kecil'		=> $data['id_kas_kecil'],
					':tgl_param'		=> $data['tgl'],
					':id_bank'			=> $data['id_bank'],
					':total_disetujui'	=> $data['total_disetujui'],
					':ket_kas_kecil'	=> $ket_mutasi_kk,
					':ket'				=> $ket_mutasi,
					':status'			=> $data['status'],
					':modified_by' => $_SESSION['sess_email']
				)
			);
			$statement->closeCursor();
		}

		/**
		* 	Review Pengajuan Kas Kecil. Dilakukkan Oleh Kas Besar
		*/
		private function revPengajuan($data) {
			$query = "UPDATE pengajuan_kas_kecil SET status = :status, modified_by = :modified_by WHERE id = :id";
			
			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $data['id']);
			$statement->bindParam(':status', $data['status']);
			$statement->bindParam(':modified_by', $_SESSION['sess_email']);
			$result = $statement->execute();

			return $result;
		}

		/**
		* 	Edit Pengajuan Kas Kecil. Dilakukkan Oleh Kas Kecil selama pengajuan belum di-review oleh Kas Besar  
		*/
		private function editPengajuan($data) {
			$query = "UPDATE pengajuan_kas_kecil SET nama = :nama, tgl = :tgl, total = :total, modified_by = :modified_by WHERE id = :id";
			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $data['id']);
			$statement->bindParam(':nama', $data['nama']);
			$statement->bindParam(':tgl', $data['tgl']);
			$statement->bindParam(':total', $data['total']);
			$statement->bindParam(':modified_by', $_SESSION['sess_email']);

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