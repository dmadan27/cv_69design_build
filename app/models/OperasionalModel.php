<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	 * Class BankModel, implementasi ke ModelInterface
	 */
	class OperasionalModel extends Database implements ModelInterface
	{

		protected $koneksi;

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
			$query = "SELECT * FROM operasional WHERE id = :id;";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

		/**
		 * 
		 */
		public function getByid_fromView($id) {
			$query = "SELECT * FROM v_operasional WHERE id = :id;";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

		/**
		 * 
		 */
		public function getKasBesar($id) {
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
		public function getExport($tgl_awal, $tgl_akhir) {
			if($tgl_awal == '' || $tgl_akhir == ''){
				$query = "SELECT * FROM v_operasional_export;";
			} else {
				$query = "SELECT * FROM v_operasional_export WHERE TANGGAL BETWEEN :tgl_awal AND :tgl_akhir;";
			}
			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':tgl_awal' => $tgl_awal,
					':tgl_akhir' => $tgl_akhir
				)
			);
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}

		/**
		 * 
		 */
		public function insert($data) {
			
			$res = $this->getKasBesar($data['id_kas_besar']);

			$uang = number_format($data['nominal'],2,",",".");

			if($data['jenis'] == 'UANG MASUK'){
				$ket_mutasi = "UANG MASUK SEBESAR Rp.".$uang." DARI TRANSAKSI OPERASIONAL ".$data['nama']." OLEH ".$res['nama'];
			} else if($data['jenis'] == 'UANG KELUAR') {
				$ket_mutasi = "UANG KELUAR SEBESAR Rp.".$uang." DARI TRANSAKSI OPERASIONAL ".$data['nama']." OLEH ".$res['nama'];
			}

			try {
				$this->koneksi->beginTransaction();

				$query = "CALL p_tambah_operasional (
					:id_bank,
					:id_kas_besar,
					:tgl,
					:nama,
					:nominal,
					:jenis,
					:ket,
					:ket_mutasi,
					:created_by
				)";

				$statement = $this->koneksi->prepare($query);
				$statement->execute(
					array(
						':id_bank' => $data['id_bank'],
						':id_kas_besar' => $data['id_kas_besar'],
						':tgl' => $data['tgl'],
						':nama' => $data['nama'],
						':nominal' => $data['nominal'],
						':jenis' => $data['jenis'],
						':ket' => $data['ket'],
						':ket_mutasi' => $ket_mutasi,
						':created_by' => $_SESSION['sess_email']
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
		public function update($data) {
			try {
				$this->koneksi->beginTransaction();

				if($data['jenis'] == "UANG MASUK"){
					$this->editMasuk($data);
				} else if($data['jenis'] == "UANG KELUAR") {
					$this->editKeluar($data);
				}

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
		private function editMasuk($data) {

			$res = $this->getById($data['id']);
			
			$ket_mutasi = '';

			if($res['jenis'] == 'UANG KELUAR'){
				$ket_mutasi = "UANG MASUK SEBESAR Rp.".number_format($res['nominal'],2,",",".")." DIKARENAKAN ADANYA PERUBAHAN DATA DI OPERASIONAL DENGAN ID ".$data['id'];	
			}

			$uang = number_format($data['nominal'],2,",",".");

			//Keterangan Jika Ada Perubahan Bank
			$ket_bank_masuk = "UANG MASUK SEBESAR Rp.".$uang." DIKARENAKAN ADANYA PERUBAHAN DATA DI OPERASIONAL DENGAN ID ".$data['id'];
			$ket_bank_keluar = "UANG KELUAR SEBESAR Rp.".number_format($res['nominal'],2,",",".")." DIKARENAKAN ADANYA PERUBAHAN DATA DI OPERASIONAL DENGAN ID ".$data['id'];

			$ket_saldoChange = '';
			$temp = 0;
			$saldo = 0;

			//Keterangan Jika Ada Perubahan Saldo
			if($data['nominal'] > $res['nominal']){
				
				$temp = $data['nominal'] - $res['nominal']; 
				$saldo = number_format($temp,2,",",".");

				$ket_saldoChange = "UANG MASUK SEBESAR Rp.".$saldo." DIKARENAKAN ADANYA PERUBAHAN DATA DI OPERASIONAL DENGAN ID ".$data['id'];
			
			} else if($data['nominal'] < $res['nominal']){
			
				$temp = $res['nominal'] - $data['nominal'];
				$saldo = number_format($temp,2,",",".");
				
				$ket_saldoChange = "UANG KELUAR SEBESAR Rp.".$saldo." DIKARENAKAN ADANYA PERUBAHAN DATA DI OPERASIONAL DENGAN ID ".$data['id'];
			
			}
			
			$query = "CALL p_edit_operasional_masuk (
				:id,
				:id_bank,
				:tgl,
				:nama,
				:nominal,
				:jenis,
				:ket,
				:ket_mutasi,
				:ket_bank_masuk,
				:ket_bank_keluar,
				:ket_saldo_change,
				:modified_by
			)";

			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id' => $data['id'],
					':id_bank' => $data['id_bank'],
					':tgl' => $data['tgl'],
					':nama' => $data['nama'],
					':nominal' => $data['nominal'],
					':jenis' => $data['jenis'],
					':ket' => $data['ket'],
					':ket_mutasi' => $ket_mutasi,
					':ket_bank_masuk' => $ket_bank_masuk,
					':ket_bank_keluar' => $ket_bank_keluar,
					':ket_saldo_change' => $ket_saldoChange,
					':modified_by' => $_SESSION['sess_email']
				)
			);
			$statement->closeCursor();
		}

		/**
		 * 
		 */
		private function editKeluar($data) {

			$res = $this->getById($data['id']);

			$ket_mutasi = '';
			
			if($res['jenis'] == 'UANG MASUK'){
				$ket_mutasi = "UANG KELUAR SEBESAR Rp.".number_format($res['nominal'],2,",",".")." DIKARENAKAN ADANYA PERUBAHAN DATA DI OPERASIONAL DENGAN ID ".$data['id'];	
			}

			$uang = number_format($data['nominal'],2,",",".");

			//Keterangan Jika Ada Perubahan Bank
			$ket_bank_masuk = "UANG MASUK SEBESAR Rp.".number_format($res['nominal'],2,",",".")." DIKARENAKAN ADANYA PERUBAHAN DATA DI OPERASIONAL DENGAN ID ".$data['id'];
			$ket_bank_keluar = "UANG KELUAR SEBESAR Rp.".$uang." DIKARENAKAN ADANYA PERUBAHAN DATA DI OPERASIONAL DENGAN ID ".$data['id'];

			$ket_saldoChange = '';
			$temp = 0;
			$saldo = 0;

			//Keterangan Jika Ada Perubahan Saldo
			if($data['nominal'] > $res['nominal']){
				
				$temp = $data['nominal'] - $res['nominal']; 
				$saldo = number_format($temp,2,",",".");

				$ket_saldoChange = "UANG KELUAR SEBESAR Rp.".$saldo." DIKARENAKAN ADANYA PERUBAHAN DATA DI OPERASIONAL DENGAN ID ".$data['id'];
			
			} else if($data['nominal'] < $res['nominal']){
			
				$temp = $res['nominal'] - $data['nominal'];
				$saldo = number_format($temp,2,",",".");
				
				$ket_saldoChange = "UANG MASUK SEBESAR Rp.".$saldo." DIKARENAKAN ADANYA PERUBAHAN DATA DI OPERASIONAL DENGAN ID ".$data['id'];
			
			}

			$query = "CALL p_edit_operasional_keluar (
				:id,
				:id_bank,
				:tgl,
				:nama,
				:nominal,
				:jenis,
				:ket,
				:ket_mutasi,
				:ket_bank_masuk,
				:ket_bank_keluar,
				:ket_saldo_change,
				:modified_by
			)";

			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id' => $data['id'],
					':id_bank' => $data['id_bank'],
					':tgl' => $data['tgl'],
					':nama' => $data['nama'],
					':nominal' => $data['nominal'],
					':jenis' => $data['jenis'],
					':ket' => $data['ket'],
					':ket_mutasi' => $ket_mutasi,
					':ket_bank_masuk' => $ket_bank_masuk,
					':ket_bank_keluar' => $ket_bank_keluar,
					':ket_saldo_change' => $ket_saldoChange,
					':modified_by' => $_SESSION['sess_email']
				)
			);
			$statement->closeCursor();
		}

		/**
		 * 
		 */
		public function delete($data) {
			// TRANSACT
			try {
				$this->koneksi->beginTransaction();

				$this->hapusOperasional($data);

				$this->koneksi->commit();

				return true;
			 	
			}
			catch (PDOException $e) {
				$this->koneksi->rollback();
				die($e->getMessage());
				// return false;
			 	
			}
		}

		/**
		 * 
		 */
		public function hapusOperasional($data) {

			$uang = number_format($data['nominal'],2,",",".");

			$ket_mutasi = '';

			if($data['jenis'] == 'UANG MASUK'){
				$ket_mutasi = "UANG KELUAR SEBESAR Rp.".$uang." DIKARENAKAN ADANYA PENGHAPUSAN DATA DI OPERASIONAL DENGAN ID ".$data['id'];
			} else if($data['jenis'] == 'UANG KELUAR') {
				$ket_mutasi = "UANG MASUK SEBESAR Rp.".$uang." DIKARENAKAN ADANYA PENGHAPUSAN DATA DI OPERASIONAL DENGAN ID ".$data['id'];
			}

			$query = "CALL p_hapus_operasional (
				:id,
				:tgl,
				:ket, :modified_by);";
			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id' => $data['id'],
					':tgl' => $data['tgl'],
					':ket' => $ket_mutasi,
					':modified_by' => $_SESSION['sess_email']
				)
			);
			$statement->closeCursor();
		}

		/**
		 * 
		 */
		public function __destruct() {
			$this->closeConnection($this->koneksi);
		}

	}