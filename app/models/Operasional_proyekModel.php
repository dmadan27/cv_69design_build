<?php

Defined("BASE_PATH") or die(ACCESS_DENIED);

class Operasional_proyekModel extends Database implements ModelInterface
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
		$query = "SELECT * FROM operasional_proyek WHERE id = :id;";
		$statement = $this->koneksi->prepare($query);
		$statement->bindParam(':id', $id);
		$statement->execute();
		$result = $statement->fetch(PDO::FETCH_ASSOC);

		return $result;
	}

	/**
	 * Method get_selectBank
	 * Proses get data bank yang aktif untuk keperluan select di operasional proyek
	 * @return result {array}
	 */
	public function get_selectBank() {
		$status = 'AKTIF';
		$query = "SELECT * FROM bank WHERE status = :status";

		$statement = $this->koneksi->prepare($query);
		$statement->bindParam(':status', $status);
		$statement->execute();
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);

		return $result;
	}

	/**
	 * 
	 */
	public function getBankById($id) {
		$query = "SELECT DISTINCT(id_bank) as id_bank FROM detail_operasional_proyek WHERE id_operasional_proyek = :id;";
		$statement = $this->koneksi->prepare($query);
		$statement->bindParam(':id', $id);
		$statement->execute();
		$result = $statement->fetch(PDO::FETCH_ASSOC);

		return $result;
	}

	/**
	 * 
	 */
	public function getSaldoBank($id) {
		$query = "SELECT saldo FROM bank WHERE id = :id;";
		$statement = $this->koneksi->prepare($query);
		$statement->bindParam(':id', $id);
		$statement->execute();
		$result = $statement->fetch(PDO::FETCH_ASSOC);

		return $result;
	}

	/**
	 * 
	 */
	public function getTotalLama($id) {
		$query = "SELECT total FROM detail_operasional_proyek WHERE id = :id;";
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
		$query = "	SELECT 
						detail_operasional_proyek.id, 
						detail_operasional_proyek.id_operasional_proyek,
						detail_operasional_proyek.id_bank,
						bank.nama as nama_bank,
						detail_operasional_proyek.tgl as tgl_detail, 
						detail_operasional_proyek.nama as nama_detail, 
						detail_operasional_proyek.total as total_detail
					FROM detail_operasional_proyek
					JOIN bank ON bank.id = detail_operasional_proyek.id_bank
					WHERE id_operasional_proyek = :id";
		$statement = $this->koneksi->prepare($query);
		$statement->bindParam(':id', $id);
		$statement->execute();
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);

		return $result;
	}

	/**
	 * 
	 */
	public function getById_fromView($id) {
		$query = "SELECT * FROM v_operasional_proyek WHERE id = :id;";

		$statement = $this->koneksi->prepare($query);
		$statement->bindParam(':id', $id);
		$statement->execute();
		$result = $statement->fetch(PDO::FETCH_ASSOC);

		return $result;

	}

	/**
	 * 
	 */
	public function getBYid_fromHistoryPembelian($id) {
		$query = "SELECT * FROM v_history_pembelian_operasionalProyek WHERE id = :id;";

		$statement = $this->koneksi->prepare($query);
		$statement->bindParam(':id', $id);
		$statement->execute();
		$result = $statement->fetch(PDO::FETCH_ASSOC);

		return $result;
	}

	/**
	 * Get Data Operasional Proyek Untuk Di Export
	 */
	public function getExport($tgl_awal, $tgl_akhir) {
		if($tgl_awal == '' && $tgl_akhir == ''){
			$query = "SELECT * FROM v_operasional_proyek_export";
		} else {
			$query = "SELECT * FROM v_operasional_proyek_export WHERE TANGGAL BETWEEN :tgl_awal AND :tgl_akhir;";
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
	public function getExportDetail($id, $tgl_awal, $tgl_akhir) {
		
		$query = "SELECT * FROM v_detail_operasional_proyek_export WHERE TANGGAL BETWEEN :tgl_awal AND :tgl_akhir AND ID = :id;";

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
	 * Get Data Operasional Proyek Untuk Di Export History Pembelian
	 */
	public function getExportHistory($id) {
		
		$query = "SELECT * FROM v_history_pembelian_operasionalProyek_export WHERE ID = :id;";

		$statement = $this->koneksi->prepare($query);
		$statement->execute(
			array(
				':id' => $id,
			)
		);
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

	/**
	 * 
	 */
	public function insert($data){
		$dataOperasionalProyek = $data['dataOperasionalProyek'];
		if($dataOperasionalProyek['id_distributor'] == ''){
			$dataOperasionalProyek['id_distributor'] = NULL;
		}
		$dataDetail = $data['listDetail'];
		
		$response = true;

		try{
			$this->koneksi->beginTransaction();

			if($dataOperasionalProyek['status'] == "TUNAI" && $dataOperasionalProyek['status_lunas'] == "LUNAS"){
				
				// insert data operasional proyek kondisi tunai lunas
				$this->insertOperasionalProyek_TunaiLunas($dataOperasionalProyek);
			
			} else if($dataOperasionalProyek['status'] == "TUNAI" && $dataOperasionalProyek['status_lunas'] == "BELUM LUNAS"){
			
				// insert data operasional proyek kondisi tunai belum lunas
				$this->insertOperasionalProyek_TunaiBelumLunas($dataOperasionalProyek);

			} else if($dataOperasionalProyek['status'] == "KREDIT" && $dataOperasionalProyek['status_lunas'] == "LUNAS"){

				//Mendapatkan Total Detail Operasional Proyek
				$sum = 0;
				foreach ($dataDetail as $index => $row) {
					if(!$dataDetail[$index]['delete']){
						$sum += $row['total_detail'];
					}
				}

				if($dataOperasionalProyek['total'] == $sum){

					$dataOperasionalProyek['sisa'] = '0';

					//insert data operasaional proyek kondisi kredit
					$this->insertOperasionalProyek_Kredit($dataOperasionalProyek);
					
					// insert data detail operasional proyek
					foreach ($dataDetail as $index => $row) {
						
						if(!$dataDetail[$index]['delete']){
							array_map('strtoupper', $row);
							$this->insertDetailOperasionalProyek($row, $dataOperasionalProyek['id']);
						}

					}	

				} else if($dataOperasionalProyek['total'] < $sum || $dataOperasionalProyek['total'] > $sum) {

					$response = false;

				}

			} else if($dataOperasionalProyek['status'] == "KREDIT" && $dataOperasionalProyek['status_lunas'] == "BELUM LUNAS"){
				
				//Mendapatkan Total Detail Operasional Proyek
				$sum = 0;
				foreach ($dataDetail as $index => $row) {
					$sum += $row['total_detail'];
				}

				if($dataOperasionalProyek['total'] < $sum) {

					$response = false;

				} else {
					
					$dataOperasionalProyek['sisa'] = $dataOperasionalProyek['total'] - $sum;

					//insert data operasaional proyek kondisi kredit
					$this->insertOperasionalProyek_Kredit($dataOperasionalProyek);
					
					// insert data detail operasional proyek
					foreach ($dataDetail as $index => $row) {
						if(!$dataDetail[$index]['delete']){
							array_map('strtoupper', $row);
							$this->insertDetailOperasionalProyek($row, $dataOperasionalProyek['id']);
						}
					}	

				}

			}

			if(!$response) {

				$output = array(
					'success' => false,
					'invalidtotaldetail' => true,
					'error' => NULL
				);

			} else {
				
				$output = array(
					'success' => true,
					'invalidtotaldetail' => false,
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
	 * Insert Data Operasional Proyek Tunai Lunas
	 */
	private function insertOperasionalProyek_TunaiLunas($data) {
		// insert operasional_proyek
		$uang = $data['total'];
		$id = $data['id'];

		$ket_mutasi = "UANG KELUAR SEBESAR Rp. ".number_format($uang,2,",",".")." DARI TRANSAKSI DI OPERASIONAL PROYEK DENGAN ID ".$id;

		$query = "CALL p_tambah_operasional_proyek_tunailunas (
			:id, 
			:id_proyek, 
			:id_bank, 
			:id_kas_besar, 
			:id_distributor, 
			:tgl, 
			:nama, 
			:jenis,  
			:total, 
			:sisa, 
			:status, 
			:status_lunas, 
			:ket,
			:ket_mutasi,
			:created_by
		);";
		$statement = $this->koneksi->prepare($query);
		$statement->execute(
			array(
				':id' => $data['id'],
				':id_proyek' => $data['id_proyek'],
				':id_bank' => $data['id_bank'],
				':id_kas_besar' => $data['id_kas_besar'],
				':id_distributor' => $data['id_distributor'],
				':tgl' => $data['tgl'],
				':nama' => $data['nama'],
				':jenis' => $data['jenis'],
				':total' => $data['total'],
				':sisa' => $data['sisa'],
				':status' => $data['status'],
				':status_lunas' => $data['status_lunas'],
				':ket' => $data['ket'],
				':ket_mutasi' => $ket_mutasi,
				':created_by' => $_SESSION['sess_email']
			)
		);
		$statement->closeCursor();
	}

	/**
	 * Insert Data Operasional Proyek Tunai Belum Lunas
	 */
	private function insertOperasionalProyek_TunaiBelumLunas($data) {
		$query = "CALL p_tambah_operasional_proyek_tunaiblmlunas (
			:id, 
			:id_proyek, 
			:id_bank, 
			:id_kas_besar, 
			:id_distributor, 
			:tgl, 
			:nama, 
			:jenis,  
			:total, 
			:sisa, 
			:status, 
			:status_lunas, 
			:ket,
			:created_by
		);";
		$statement = $this->koneksi->prepare($query);
		$statement->execute(
			array(
				':id' => $data['id'],
				':id_proyek' => $data['id_proyek'],
				':id_bank' => $data['id_bank'],
				':id_kas_besar' => $data['id_kas_besar'],
				':id_distributor' => $data['id_distributor'],
				':tgl' => $data['tgl'],
				':nama' => $data['nama'],
				':jenis' => $data['jenis'],
				':total' => $data['total'],
				':sisa' => $data['total'],
				':status' => $data['status'],
				':status_lunas' => $data['status_lunas'],
				':ket' => $data['ket'],
				':created_by' => $_SESSION['sess_email']
			)
		);
		$statement->closeCursor();
	}

	/**
	 * Insert Data Operasional Proyek Jenis Pembayaran Kredit
	 */
	private function insertOperasionalProyek_Kredit($data) {

		$query = "CALL p_tambah_operasional_proyek_kredit (
			:id, 
			:id_proyek, 
			:id_bank, 
			:id_kas_besar, 
			:id_distributor, 
			:tgl, 
			:nama, 
			:jenis,  
			:total, 
			:sisa, 
			:status, 
			:status_lunas, 
			:ket,
			:created_by
		);";
		$statement = $this->koneksi->prepare($query);
		$statement->execute(
			array(
				':id' => $data['id'],
				':id_proyek' => $data['id_proyek'],
				':id_bank' => $data['id_bank'],
				':id_kas_besar' => $data['id_kas_besar'],
				':id_distributor' => $data['id_distributor'],
				':tgl' => $data['tgl'],
				':nama' => $data['nama'],
				':jenis' => $data['jenis'],
				':total' => $data['total'],
				':sisa' => $data['sisa'],
				':status' => $data['status'],
				':status_lunas' => $data['status_lunas'],
				':ket' => $data['ket'],
				':created_by' => $_SESSION['sess_email']
			)
		);
		$statement->closeCursor();
	}

	/**
	 * Insert Data Detail Operasional Proyek
	 */
	private function insertDetailOperasionalProyek($data, $id_operasional_proyek){
		
		$uang = $data['total_detail'];

		$ket_mutasi = "UANG KELUAR SEBESAR Rp. ".number_format($uang,2,",",".")." DARI TRANSAKSI DI OPERASIONAL PROYEK DENGAN ID ".$id_operasional_proyek;

		$query = "CALL p_tambah_detail_operasional_proyek_kredit (
			:id, 
			:id_bank, 
			:tgl, 
			:nama, 
			:total,
			:ket,
			:created_by
		);";
		$statement = $this->koneksi->prepare($query);
		$statement->execute(
			array(
				':id' => $id_operasional_proyek,
				':id_bank' => $data['id_bank'],
				':tgl' => $data['tgl_detail'],
				':nama' => $data['nama_detail'],
				':total' => $data['total_detail'],
				':ket' => $ket_mutasi,
				':created_by' => $_SESSION['sess_email']
			)
		);
		$statement->closeCursor();

	}

	/**
	 * 
	 */
	public function update($data){
		

		$response = true;

		try{
			$this->koneksi->beginTransaction();

			if($data['dataOperasionalProyek']['status'] == "TUNAI" && $data['dataOperasionalProyek']['status_lunas'] == "LUNAS"){
				// print_r($data['dataOperasionalProyek']);
				// exit;
				if($data['dataOperasionalProyek']['id_distributor'] == ''){
					$data['dataOperasionalProyek']['id_distributor'] = NULL;
				}

				if(empty($data['dataDetail'])){
					$id = '';
					$this->edit_OperasionalProyek($id, $data['dataOperasionalProyek']);	
				} else {
					$this->edit_OperasionalProyek($data['dataDetail'][0]['id'], $data['dataOperasionalProyek']);
				}
				
			} else if($data['dataOperasionalProyek']['status'] == "TUNAI" && $data['dataOperasionalProyek']['status_lunas'] == "BELUM LUNAS"){
				
				if($data['dataOperasionalProyek']['id_distributor'] == ''){
					$data['dataOperasionalProyek']['id_distributor'] = NULL;
				}

				$this->edit_OperasionalProyek_BelumLunas($data['dataOperasionalProyek']);

			} else if($data['dataOperasionalProyek']['status'] == "KREDIT" && $data['dataOperasionalProyek']['status_lunas'] == "LUNAS"){

				if($data['dataOperasionalProyek']['id_distributor'] == ''){
					$data['dataOperasionalProyek']['id_distributor'] = NULL;
				}

				//Mendapatkan Total Detail Operasional Proyek
				$sumDetail = 0;
				foreach ($data['dataDetail'] as $index => $row) {
					if(!$data['dataDetail'][$index]['delete']){
						$sumDetail += $row['total_detail'];
					}
				}

				if($sumDetail == $data['dataOperasionalProyek']['total']){

					foreach ($data['dataDetail'] as $index => $row) {
						if($row['aksi'] == 'edit' && !$row['delete']){
							$this->updateDetail_OperasionalProyek($row);
						} else if($row['aksi'] == 'tambah' && !$row['delete']){
							$this->insertDetailOperasionalProyek($row,$data['dataOperasionalProyek']['id']);
						} else if($row['delete']){
							$this->deleteDetailOperasionalProyek($row['id']);
							$this->catatMutasi($row);
						}
					}

					//Update Operasional Proyek
					$this->edit_operasionalProyek_kredit($data['dataOperasionalProyek']);
					
				} else if($data['dataOperasionalProyek']['total'] < $sumDetail || $data['dataOperasionalProyek']['total'] > $sumDetail) {

					$response = false;

				}


			} else if($data['dataOperasionalProyek']['status'] == "KREDIT" && $data['dataOperasionalProyek']['status_lunas'] == "BELUM LUNAS"){

				if($data['dataOperasionalProyek']['id_distributor'] == ''){
					$data['dataOperasionalProyek']['id_distributor'] = NULL;
				}

				//Mendapatkan Total Detail Operasional Proyek
				$sumDetail = 0;
				foreach ($data['dataDetail'] as $index => $row) {
					if(!$row['delete']){
						$sumDetail += $row['total_detail'];
					}
				}

				if($data['dataOperasionalProyek']['total'] < $sumDetail) {

					$response = false;

				} else {

					$data['dataOperasionalProyek']['sisa'] = $data['dataOperasionalProyek']['total'] - $sumDetail;

					foreach ($data['dataDetail'] as $index => $row) {
						if($row['aksi'] == 'edit' && !$row['delete']){
							$this->updateDetail_OperasionalProyek($row);
						} else if($row['aksi'] == 'tambah' && !$row['delete']){
							$this->insertDetailOperasionalProyek($row,$data['dataOperasionalProyek']['id']);
						} else if($row['delete']){
							$this->deleteDetailOperasionalProyek($row['id']);
							$this->catatMutasi($row);
						}
					}

					$this->edit_operasionalProyek_kredit($data['dataOperasionalProyek']);
			
				}

			}

			if(!$response) {
				$output = array(
					'success' => false,
					'invalidtotaldetail' => true,
					'error' => NULL
				);

			} else {
				
				$output = array(
					'success' => true,
					'invalidtotaldetail' => false,
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
	* 	Edit Data Operasional Proyek Tunai Lunas
	*/
	private function edit_operasionalProyek($id_detail, $data) {

		$uang = $data['total'];
		$id = $data['id'];

		$saldo = $this->getSaldoBank($data['id_bank']);
		$saldo = $saldo['saldo'];

		$total = $this->getTotalLama($id_detail);
		$total = $total['total'];

		//Mutasi Jika Data Adalah Perubahan dari Belum Lunas 
		$ket_mutasi = "UANG KELUAR SEBESAR Rp. ".number_format($uang,2,",",".")." DIKARENAKAN ADANYA PERUBAHAN DATA DI OPERASIONAL PROYEK DENGAN ID ".$id;

		//Mutasi Jika Ganti Bank
		$ket_mutasi_keluar = "UANG KELUAR SEBESAR Rp. ".number_format($uang,2,",",".")." DIKARENAKAN ADANYA PERUBAHAN DATA DI OPERASIONAL PROYEK DENGAN ID ".$id;
		$ket_mutasi_masuk = "UANG MASUK SEBESAR Rp. ".number_format($total,2,",",".")." DIKARENAKAN ADANYA PERUBAHAN DATA DI OPERASIONAL PROYEK DENGAN ID ".$id;

		if($uang > $total){
		//Mutasi Jika Saldo Berubah Lebih Besar
			$selisih = $uang - $total;
			$ket_mutasi_kondisi = "UANG KELUAR SEBESAR Rp. ".number_format($selisih,2,",",".")." DIKARENAKAN ADANYA PERUBAHAN DATA DI OPERASIONAL PROYEK DENGAN ID ".$id;
		} else if($uang < $total){
		//Mutasi Jika Saldo Berubah Lebih Kecil
			$selisih = $total - $uang;
			$ket_mutasi_kondisi = "UANG MASUK SEBESAR Rp. ".number_format($selisih,2,",",".")." DIKARENAKAN ADANYA PERUBAHAN DATA DI OPERASIONAL PROYEK DENGAN ID ".$id;
		}
		
		$query = "CALL p_edit_operasional_proyek (
			:id, 
			:id_detail,
			:id_proyek, 
			:id_bank,
			:id_distributor, 
			:tgl, 
			:nama, 
			:jenis,  
			:total, 
			:sisa, 
			:status, 
			:status_lunas, 
			:ket,
			:ket_mutasi,
			:ket_mutasi_masuk,
			:ket_mutasi_keluar,
			:ket_mutasi_kondisi,
			:modified_by
		);";

		$statement = $this->koneksi->prepare($query);
		$statement->execute(
			array(
				':id' => $data['id'],
				':id_detail' => $id_detail,
				':id_proyek' => $data['id_proyek'],
				':id_bank' => $data['id_bank'],
				':id_distributor' => $data['id_distributor'],
				':tgl' => $data['tgl'],
				':nama' => $data['nama'],
				':jenis' => $data['jenis'],
				':total' => $data['total'],
				':sisa' => $data['sisa'],
				':status' => $data['status'],
				':status_lunas' => $data['status_lunas'],
				':ket' => $data['ket'],
				':ket_mutasi' => $ket_mutasi,
				':ket_mutasi_masuk' => $ket_mutasi_masuk,
				':ket_mutasi_keluar' => $ket_mutasi_keluar,
				':ket_mutasi_kondisi' => $ket_mutasi_kondisi,
				':modified_by' => $_SESSION['sess_email']
			)
		);
		$statement->closeCursor();
	}

	/**
	* 	Edit Data Operasional Proyek Tunai Belum Lunas
	*/
	private function edit_operasionalProyek_BelumLunas($data) {

		$uang = $data['total'];
		$id = $data['id'];

		$ket_mutasi = "UANG MASUK SEBESAR Rp. ".number_format($uang,2,",",".")." DIKARENAKAN ADANYA PERUBAHAN DATA DI OPERASIONAL PROYEK DENGAN ID ".$id;

		$query = "CALL p_edit_operasional_proyek_BelumLunas (
			:id, 
			:id_proyek, 
			:id_distributor,
			:tgl, 
			:nama, 
			:jenis,  
			:total, 
			:sisa, 
			:status, 
			:status_lunas, 
			:ket,
			:ket_mutasi,
			:modified_by
		);";

		$statement = $this->koneksi->prepare($query);
		$statement->execute(
			array(
				':id' => $data['id'],
				':id_proyek' => $data['id_proyek'],
				':id_distributor' => $data['id_distributor'],
				':tgl' => $data['tgl'],
				':nama' => $data['nama'],
				':jenis' => $data['jenis'],
				':total' => $data['total'],
				':sisa' => $data['sisa'],
				':status' => $data['status'],
				':status_lunas' => $data['status_lunas'],
				':ket' => $data['ket'],
				':ket_mutasi' => $ket_mutasi,
				':modified_by' => $_SESSION['sess_email']
			)
		);
		$statement->closeCursor();
	}

	/**
	* 	Edit Data Operasional Proyek Jenis Pembayaran Kredit
	*/
	private function edit_operasionalProyek_kredit($data) {
		// print_r($data);
		// exit;
		$query = "CALL p_edit_operasional_proyek_kredit (
			:id, 
			:id_proyek, 
			:id_distributor,
			:tgl, 
			:nama, 
			:jenis,  
			:total, 
			:sisa, 
			:status, 
			:status_lunas, 
			:ket,
			:modified_by
		);";

		$statement = $this->koneksi->prepare($query);
		$statement->execute(
			array(
				':id' => $data['id'],
				':id_proyek' => $data['id_proyek'],
				':id_distributor' => $data['id_distributor'],
				':tgl' => $data['tgl'],
				':nama' => $data['nama'],
				':jenis' => $data['jenis'],
				':total' => $data['total'],
				':sisa' => $data['sisa'],
				':status' => $data['status'],
				':status_lunas' => $data['status_lunas'],
				':ket' => $data['ket'],
				':modified_by' => $_SESSION['sess_email']
			)
		);
		$statement->closeCursor();
	}

	/**
	*  Update Detail Operasional Proyek
	*/
	private function updateDetail_OperasionalProyek($data) {

		$uang = $data['total_detail'];
		$id = $data['id_operasional_proyek'];

		$saldo = $this->getSaldoBank($data['id_bank']);
		$saldo = $saldo['saldo'];

		$total = $this->getTotalLama($data['id']);
		$total = $total['total'];

		//Mutasi Jika Data Adalah Perubahan dari Belum Lunas 
		$ket_mutasi = "UANG KELUAR SEBESAR Rp. ".number_format($uang,2,",",".")." DIKARENAKAN ADANYA PERUBAHAN DATA DI OPERASIONAL PROYEK DENGAN ID ".$id;

		//Mutasi Jika Ganti Bank
		$ket_mutasi_keluar = "UANG KELUAR SEBESAR Rp. ".number_format($uang,2,",",".")." DIKARENAKAN ADANYA PERUBAHAN DATA DI OPERASIONAL PROYEK DENGAN ID ".$id;
		$ket_mutasi_masuk = "UANG MASUK SEBESAR Rp. ".number_format($total,2,",",".")." DIKARENAKAN ADANYA PERUBAHAN DATA DI OPERASIONAL PROYEK DENGAN ID ".$id;

		$ket_mutasi_kondisi = '';

		if($uang > $total) {
		//Mutasi Jika Saldo Berubah Lebih Besar
			$selisih = $uang - $total;
			$ket_mutasi_kondisi = "UANG KELUAR SEBESAR Rp. ".number_format($selisih,2,",",".")." DIKARENAKAN ADANYA PERUBAHAN DATA DI OPERASIONAL PROYEK DENGAN ID ".$id;
		} else if($uang < $total){
		//Mutasi Jika Saldo Berubah Lebih Kecil
			$selisih = $total - $uang;
			$ket_mutasi_kondisi = "UANG MASUK SEBESAR Rp. ".number_format($selisih,2,",",".")." DIKARENAKAN ADANYA PERUBAHAN DATA DI OPERASIONAL PROYEK DENGAN ID ".$id;
		}

		$query = "CALL p_edit_detail_operasional_proyek (
			:id_operasional_proyek, 
			:id_detail, 
			:id_bank,
			:tgl_detail,
			:nama_detail,
			:total_detail,
			:ket_mutasi,
			:ket_mutasi_masuk,
			:ket_mutasi_keluar,
			:ket_mutasi_kondisi,
			:modified_by
		);";

		$statement = $this->koneksi->prepare($query);
		$statement->execute(
			array(
				':id_operasional_proyek' => $data['id_operasional_proyek'],
				':id_detail' => $data['id'],
				':id_bank' => $data['id_bank'],
				':tgl_detail' => $data['tgl_detail'],
				':nama_detail' => $data['nama_detail'],
				':total_detail' => $data['total_detail'],
				':ket_mutasi' => $ket_mutasi,
				':ket_mutasi_masuk' => $ket_mutasi_masuk,
				':ket_mutasi_keluar' => $ket_mutasi_keluar,
				':ket_mutasi_kondisi' => $ket_mutasi_kondisi,
				':modified_by' => $_SESSION['sess_email']
			)
		);
		$statement->closeCursor();
	}

	/**
	*  Delete Detail Operasional Proyek
	*/
	private function deleteDetailOperasionalProyek($id) {
		$query = "DELETE FROM detail_operasional_proyek WHERE id = :id";
		$statement = $this->koneksi->prepare($query);
		$statement->execute(
			array(
				':id' => $id,
			)
		);
		$statement->closeCursor();
	}

	/**
	*  
	*/
	public function delete($data){
		// TRANSACT
		try {
			$this->koneksi->beginTransaction();

			if($data['dataOperasionalProyek']['status'] == "TUNAI" && $data['dataOperasionalProyek']['status_lunas'] == "LUNAS"){

				$this->deleteOperasionalProyek_Lunas($data['dataOperasionalProyek']);	

			} else if($data['dataOperasionalProyek']['status'] == "TUNAI" && $data['dataOperasionalProyek']['status_lunas'] == "BELUM LUNAS") {

				$this->deleteOperasionalProyek_BelumLunas($data['dataOperasionalProyek']);	

			} else if($data['dataOperasionalProyek']['status'] == "KREDIT" && $data['dataOperasionalProyek']['status_lunas'] == "LUNAS") {

				$this->deleteKredit($data['dataOperasionalProyek']);	

				foreach ($data['dataDetail'] as $index => $row) {
					array_map('strtoupper', $row);
					$this->catatMutasi($row);
				}

			} else if($data['dataOperasionalProyek']['status'] == "KREDIT" && $data['dataOperasionalProyek']['status_lunas'] == "BELUM LUNAS") {

				$this->deleteKredit($data['dataOperasionalProyek']);	

				foreach ($data['dataDetail'] as $index => $row) {
					array_map('strtoupper', $row);
					$this->catatMutasi($row);
				}

			}

			$this->koneksi->commit();
			return array(
				'success' => true,
				'error' => NULL
			);

		} catch(PDOException $e){
			$this->koneksi->rollback();
			die($e->getMessage());
		}

	}

	/**
	 * Hapus Data Operasional Proyek Dengan Jenis Pembayaran "LUNAS"
	 */
	private function deleteOperasionalProyek_Lunas($data) {
		
		$uang = $data['total'];
		$id = $data['id'];

		$ket_mutasi = "UANG MASUK SEBESAR Rp. ".number_format($uang,2,",",".")." DIKARENAKAN ADANYA PENGHAPUSAN DATA DI OPERASIONAL PROYEK DENGAN ID ".$id;

		$query = 'CALL p_hapus_operasional_proyek_versi2 (:id, :total, :tgl, :ket, :modified_by);';
		$statement = $this->koneksi->prepare($query);
		$statement->execute(
			array(
				':id' => $data['id'],
				':total' => $data['total'],
				':tgl' => $data['tgl'],
				':ket' => $ket_mutasi,
				':modified_by' => $_SESSION['sess_email']
			)
		);
		$statement->closeCursor();
	} 

	
	/**
	 * Hapus Data Operasional Proyek Dengan Jenis Pembayaran "BELUM LUNAS"
	 */
	public function deleteOperasionalProyek_BelumLunas($data){
		$query = 'CALL p_hapus_operasional_proyek_tunai_blmlunas (:id);';
		$statement = $this->koneksi->prepare($query);
		$statement->execute(
			array(
				':id' => $data['id'],							
			)
		);
		$statement->closeCursor();
	}
	
	/**
	*  Hapus Data Operasional Proyek Dengan Jenis Pembayaran "KREDIT"
	*/
	public function deleteKredit($dataOpr){
		$query = 'CALL p_hapus_operasional_proyek_kredit (:id);';
		$statement = $this->koneksi->prepare($query);
		$statement->execute(
			array(
				':id' => $dataOpr['id']		
			)
		);
		$statement->closeCursor();
	}

	/**
	* 	Catat Mutasi Setelah Data Operasional Proyek Dihapus, Khusus "KREDIT"
	*/
	public function catatMutasi($data){

		$uang = $data['total_detail'];
		$id = $data['id'];

		$ket_mutasi = "UANG MASUK SEBESAR Rp. ".number_format($uang,2,",",".")." DIKARENAKAN ADANYA PENGHAPUSAN DATA DI OPERASIONAL PROYEK DENGAN ID ".$id;

		$query = 'CALL p_hapus_operasional_proyek_kredit_catatMutasi (
			:id,
			:id_bank,
			:total_detail,
			:tgl,
			:ket,
			:modified_by
		);';

		$statement = $this->koneksi->prepare($query);
		$statement->execute(
			array(
				':id' 			=> $data['id'],
				':id_bank' 		=> $data['id_bank'],
				':total_detail'	=> $data['total_detail'],
				':tgl' 			=> $data['tgl_detail'],
				':ket'			=> $ket_mutasi,
				':modified_by' 	=> $_SESSION['sess_email']					
			)
		);
		$statement->closeCursor();
	}

	/**
	 * 
	 */
	public function getLastID($id){
		$id .= "%";
		$query = "SELECT MAX(id) AS id FROM operasional_proyek WHERE id LIKE :id";

		$statement = $this->koneksi->prepare($query);
		$statement->bindParam(':id', $id);
		$statement->execute();
		$result = $statement->fetch(PDO::FETCH_ASSOC);

		return $result;
	}

	// METHOD EXPORT ===============vvvvvvvvvvvv========================================================================

	/**
	 * 
	 */
	public function export($tgl_awal, $tgl_akhir) {
		$query = "SELECT * FROM v_operasional_proyek_export WHERE TANGGAL BETWEEN :tgl_awal AND :tgl_akhir;";

		$statement = $this->koneksi->prepare($query);
		$statement->execute(
			array(
				'tgl_awal' => $tgl_awal,
				'tgl_akhir' => $tgl_akhir,
			)
		);
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

	/**
	 * 
	 */
	public function export_by_id($id_operasional) {
		$query = "SELECT * FROM v_operasional_proyek_export WHERE `ID OPERASIONAL PROYEK` = :id_operasional;";

		$statement = $this->koneksi->prepare($query);
		$statement->execute(
			array(
				':id_operasional' => $id_operasional
			)
		);
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

	public function export_by_proyek($tgl_awal, $tgl_akhir, $id_proyek) {
		$query = "SELECT * FROM v_operasional_proyek_export WHERE `ID PROYEK` = :id_proyek ";
		$query .= "AND (`TANGGAL` BETWEEN :tgl_awal AND :tgl_akhir);";

		$statement = $this->koneksi->prepare($query);
		$statement->execute(
			array(
				':id_proyek' => $id_proyek,
				':tgl_awal' => $tgl_awal,
				':tgl_akhir' => $tgl_akhir,
			)
		);
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

	/**
	 * 
	 */
	public function export_detail($tgl_awal, $tgl_akhir) {
		$query = "SELECT * FROM v_export_detail_operasional_proyek WHERE `TANGGAL DETAIL` BETWEEN :tgl_awal AND :tgl_akhir;";

		$statement = $this->koneksi->prepare($query);
		$statement->execute(
			array(
				'tgl_awal' => $tgl_awal,
				'tgl_akhir' => $tgl_akhir,
			)
		);
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

	/**
	 * 
	 */
	public function export_detail_by_id($id_operasional) {
		$query = "SELECT * FROM v_export_detail_operasional_proyek WHERE `ID OPERASIONAL PROYEK` = :id_operasional;";

		$statement = $this->koneksi->prepare($query);
		$statement->execute(
			array(
				':id_operasional' => $id_operasional
			)
		);
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

	/**
	 * 
	 */
	public function export_detail_by_proyek($tgl_awal, $tgl_akhir, $id_proyek) {
		$query = "SELECT * FROM v_export_detail_operasional_proyek WHERE `ID OPERASIONAL PROYEK` IN ";
		$query .= "(SELECT id FROM operasional_proyek WHERE id_proyek = :id_proyek ";
		$query .= "AND (tgl BETWEEN :tgl_awal AND :tgl_akhir));";

		$statement = $this->koneksi->prepare($query);
		$statement->execute(
			array(
				':id_proyek' => $id_proyek,
				':tgl_awal' => $tgl_awal,
				':tgl_akhir' => $tgl_akhir,
			)
		);
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

	// END METHOD EXPORT ===================================================================================================

	/**
	 * 
	 */
	public function __destruct(){
		$this->closeConnection($this->koneksi);
	}

}
