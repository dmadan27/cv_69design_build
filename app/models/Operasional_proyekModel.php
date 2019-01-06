<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
	
	class Operasional_ProyekModel extends Database implements ModelInterface{
	
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
		public function get_selectBank(){
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
		public function getBankById($id){
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
		public function getDetailById($id){
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
		public function getById_fromView($id){
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
		public function getDetailById_fromView($id){
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
		public function getBYid_fromHistoryPembelian($id){
			$query = "SELECT * FROM v_history_pembelian_operasionalproyek WHERE id = :id;";

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
			$dataOperasionalProyek = $data['dataOperasionalProyek'];
			$dataDetail = $data['listDetail'];
			
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
						$sum += $row['total_detail'];
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

						exit;

					}

				} else if($dataOperasionalProyek['status'] == "KREDIT" && $dataOperasionalProyek['status_lunas'] == "BELUM LUNAS"){
					
					//Mendapatkan Total Detail Operasional Proyek
					$sum = 0;
					foreach ($dataDetail as $index => $row) {
						$sum += $row['total_detail'];
					}

					if($dataOperasionalProyek['total'] < $sum) {
						exit;
					}
						
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
		private function insertOperasionalProyek_TunaiLunas($data){
			// insert operasional_proyek
			$query = "CALL tambah_operasional_proyek_tunailunas (
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
				:ket
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
				)
			);
			$statement->closeCursor();
		}

		/**
		*
		*/
		private function insertOperasionalProyek_Kredit($data) {
			$query = "CALL tambah_operasional_proyek_kredit (
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
				:ket
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
				)
			);
			$statement->closeCursor();
		}

		/**
		*
		*/
		private function insertOperasionalProyek_TunaiBelumLunas($data) {
			$query = "CALL tambah_operasional_proyek_tunaiblmlunas (
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
				:ket
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
				)
			);
			$statement->closeCursor();
		}

		/**
		*
		*/
		private function insertOperasionalProyek_KreditBelumLunas($data) {
			$query = "CALL tambah_operasional_proyek_kreditblmlunas (
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
				:sum_detail
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
					':sum_detail' => $data['sum']
				)
			);
			$statement->closeCursor();
		}

		/**
		*
		*/
		private function insertDetailOperasionalProyek($data, $id_operasional_proyek){
			$query = "CALL tambah_detail_operasional_proyek_kredit (
				:id, 
				:id_bank, 
				:tgl, 
				:nama, 
				:total,
				:ket
			);";
			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id' => $id_operasional_proyek,
					':id_bank' => $data['id_bank'],
					':tgl' => $data['tgl_detail'],
					':nama' => $data['nama_detail'],
					':total' => $data['total_detail'],
					':ket' => ''
				)
			);
			$statement->closeCursor();

		}

		/**
		* 
		*/
		public function update($data){

			try{
				$this->koneksi->beginTransaction();

				if($data['dataOperasionalProyek']['status'] == "TUNAI" && $data['dataOperasionalProyek']['status_lunas'] == "LUNAS"){

					if(empty($data['dataDetail'])){
						$id = '';
						$this->edit_OperasionalProyek($id, $data['dataOperasionalProyek']);	
					} else {
						$this->edit_OperasionalProyek($data['dataDetail'][0]['id'], $data['dataOperasionalProyek']);
					}
					
				} else if($data['dataOperasionalProyek']['status'] == "TUNAI" && $data['dataOperasionalProyek']['status_lunas'] == "BELUM LUNAS"){
				
					$this->edit_OperasionalProyek_BelumLunas($data['dataOperasionalProyek']);

				} else if($data['dataOperasionalProyek']['status'] == "KREDIT" && $data['dataOperasionalProyek']['status_lunas'] == "LUNAS"){

					//Mendapatkan Total Detail Operasional Proyek Tambahan
					// $sumDetailTambahan = 0;
					// foreach ($data['dataDetailTambahan'] as $index => $row) {
					// 	$sumDetailTambahan += $row['total_detail'];
					// }

					//Mendapatkan Total Detail Operasional Proyek Lama
					$sumDetail = 0;
					foreach ($data['dataDetail'] as $index => $row) {
						$sumDetail += $row['total_detail'];
					}

					if($sumDetail == $data['dataOperasionalProyek']['total']){

						foreach ($data['dataDetail'] as $index => $row) {
							if($row['id'] != ''){
								$this->updateDetail_OperasionalProyek($row);
							}
						}

						//Update Operasional Proyek
						$this->edit_operasionalProyek_kredit($data['dataOperasionalProyek']);

						//insert detail operasional proyek
						// foreach ($data['dataDetailTambahan'] as $index => $row) {
						// 	if(!$data['dataDetailTambahan'][$index]['delete']){
						// 		array_map('strtoupper', $row);
						// 		$this->insertDetailOperasionalProyek($row, $data['dataOperasionalProyek']['id']);
						// 	}
						// }
						
					} else if($data['dataOperasionalProyek']['total'] < $sumDetail || $data['dataOperasionalProyek']['total'] > $sumDetail) {
						exit;
					}


				} else if($data['dataOperasionalProyek']['status'] == "KREDIT" && $data['dataOperasionalProyek']['status_lunas'] == "BELUM LUNAS"){
						
					// if(!empty($data['toEdit'])){
					// 	foreach ($data['toEdit'] as $index => $row) {
					// 		if($row['id'] != ''){
					// 			$this->hitung_DetailOperasional($row, $data['dataOperasionalProyek']);
					// 		}
					// 	}
					// }

					// if(!empty($data['toDelete'])){
					// 	foreach ($data['toDelete'] as $index => $row) {
					// 		if($row['id'] != ''){
					// 			$this->hapus_DetailOperasional($row, $data['dataOperasionalProyek']['id']);
					// 		}
					// 	}
					// }

					//Mendapatkan Total Detail Operasional Proyek
					$sumDetail = 0;
					foreach ($data['dataDetail'] as $index => $row) {
						$sumDetail += $row['total_detail'];
					}

					if($data['dataOperasionalProyek']['total'] < $sumDetail) {
						exit;
					}

					foreach ($data['dataDetail'] as $index => $row) {
						if($row['id'] != ''){
							$this->updateDetail_OperasionalProyek($row);
						}
					}

					$this->edit_operasionalProyek_kredit($data['dataOperasionalProyek']);

					// $sumDetailTambahan = 0;
					// foreach ($data['dataDetailTambahan'] as $index => $row) {
					// 	$sumDetailTambahan += $row['total_detail'];
					// }

					//Selama total cicilan masih kurang dari total maka detail bisa terus ditambah
					// if($data['dataOperasionalProyek']['total'] > $sumDetail){
					//insert detail operasional proyek
					// 	foreach ($data['dataDetailTambahan'] as $index => $row) {
					// 		if(!$data['dataDetailTambahan'][$index]['delete']){
					// 			array_map('strtoupper', $row);
					// 			$this->insertDetailOperasionalProyek($row, $data['dataOperasionalProyek']['id']);
					// 		}
					// 	}
					// }

					// $data['dataOperasionalProyek']['sisa'] = $data['dataOperasionalProyek']['sisa'] - $sumDetailTambahan;
					
				}
				
				$this->koneksi->commit();
				return true;

			} catch(PDOException $e){
				$this->koneksi->rollback();
				die($e->getMessage());
				// return false;
			}

		}

		/**
		*  Jika Detail Operasional Proyek Ada Yang Didelete
		*/
		private function hitung_DetailOperasional($data, $dataOperasionalProyek){
			$query = "SELECT total FROM detail_operasional_proyek WHERE id = :id;";
			
			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $data['id']);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			//Hitung Perubahan Total dan Update Data
			$total_lama = $result['total'];
			$total_baru = $data['total_detail'];
			
			if($total_lama > $total_baru){

				$total_changes = $total_lama - $total_baru;
				$this->updateDetail_OperasionalProyek_ver1($data, $dataOperasionalProyek, $total_changes);

			} else if($total_lama < $total_baru) {

				$total_changes = $total_baru - $total_lama;
				$this->updateDetail_OperasionalProyek_ver2($data, $dataOperasionalProyek, $total_changes);

			}
		}

		/**
		*  
		*/
		private function updateDetail_OperasionalProyek($data) {
			$query = "CALL edit_detail_operasional_proyek (
				:id_operasional_proyek, 
				:id_detail, 
				:id_bank,
				:tgl_detail,
				:nama_detail,
				:total_detail,
				:ket
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
					':ket' => ''
				)
			);
			$statement->closeCursor();
		}


		/**
		*  Jika Perubahan Total Detail Operasional Proyek > Yang Baru
		*/
		private function updateDetail_OperasionalProyek_ver1($data, $dataOperasionalProyek, $total_changes) {
			$query = "CALL edit_detail_operasional_proyek_ver1 (
				:id_operasional_proyek, 
				:id_detail, 
				:tgl_detail,
				:nama_detail,
				:perubahan_total,
				:total,
				:ket
			);";

			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id_operasional_proyek' => $dataOperasionalProyek['id'],
					':id_detail' => $data['id'],
					':tgl_detail' => $data['tgl_detail'],
					':nama_detail' => $data['nama_detail'],
					':perubahan_total' => $total_changes,
					':total' => $data['total_detail'],
					':ket' => 'Edit Detail '.$data['nama_detail']
				)
			);
			$statement->closeCursor();
		}

		/**
		*  Jika Perubahan Total Detail Operasional Proyek < Yang Baru
		*/
		private function updateDetail_OperasionalProyek_ver2($data, $dataOperasionalProyek, $total_changes) {
			$query = "CALL edit_detail_operasional_proyek_ver2 (
				:id_operasional_proyek, 
				:id_detail, 
				:tgl_detail,
				:nama_detail,
				:perubahan_total,
				:total,
				:ket
			);";

			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id_operasional_proyek' => $dataOperasionalProyek['id'],
					':id_detail' => $data['id'],
					':tgl_detail' => $data['tgl_detail'],
					':nama_detail' => $data['nama_detail'],
					':perubahan_total' => $total_changes,
					':total' => $data['total_detail'],
					':ket' => 'Edit Detail '.$data['nama_detail']
				)
			);
			$statement->closeCursor();
		}

		/**
		*  Jika Detail Operasional Proyek Ada Yang Dihapus
		*/
		private function hapus_DetailOperasional($data, $id) {
			// print_r($data);
			// exit;
			$query = 'CALL hapus_detail_operasional_proyek (:id, :id_operasional_proyek, :total_detail, :ket, :tgl);';
			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id' => $data['id'],
					':id_operasional_proyek' => $id,
					':total_detail' => $data['total_detail'],
					':ket' => 'hapus detail operasional '.$data['nama_detail'],
					':tgl' => $data['tgl_detail']
					
				)
			);
			$statement->closeCursor();
		}

		/**
		* 
		*/
		private function edit_operasionalProyek($id_detail, $data) {
			//Update Mutasi Saldo Tunai Belum Lunas -> Lunas 
			$query = "CALL edit_operasional_proyek (
				:id, 
				:id_detail,
				:id_proyek, 
				:id_bank, 
				:tgl, 
				:nama, 
				:jenis,  
				:total, 
				:sisa, 
				:status, 
				:status_lunas, 
				:ket
			);";

			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id' => $data['id'],
					':id_detail' => $id_detail,
					':id_proyek' => $data['id_proyek'],
					':id_bank' => $data['id_bank'],
					':tgl' => $data['tgl'],
					':nama' => $data['nama'],
					':jenis' => $data['jenis'],
					':total' => $data['total'],
					':sisa' => $data['sisa'],
					':status' => $data['status'],
					':status_lunas' => $data['status_lunas'],
					':ket' => $data['ket']
				)
			);
			$statement->closeCursor();
		}

		/**
		* 
		*/
		private function edit_operasionalProyek_BelumLunas($data) {
			//Update Mutasi Saldo Tunai Lunas -> Belum Lunas 
			$query = "CALL edit_operasional_proyek_BelumLunas (
				:id, 
				:id_proyek, 
				:tgl, 
				:nama, 
				:jenis,  
				:total, 
				:sisa, 
				:status, 
				:status_lunas, 
				:ket
			);";

			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id' => $data['id'],
					':id_proyek' => $data['id_proyek'],
					':tgl' => $data['tgl'],
					':nama' => $data['nama'],
					':jenis' => $data['jenis'],
					':total' => $data['total'],
					':sisa' => $data['sisa'],
					':status' => $data['status'],
					':status_lunas' => $data['status_lunas'],
					':ket' => $data['ket']
				)
			);
			$statement->closeCursor();
		}

		/**
		* 
		*/
		private function edit_operasionalProyek_kredit($data) {
			$query = "CALL edit_operasional_proyek_kredit (
				:id, 
				:id_proyek, 
				:tgl, 
				:nama, 
				:jenis,  
				:total, 
				:sisa, 
				:status, 
				:status_lunas, 
				:ket
			);";

			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id' => $data['id'],
					':id_proyek' => $data['id_proyek'],
					':tgl' => $data['tgl'],
					':nama' => $data['nama'],
					':jenis' => $data['jenis'],
					':total' => $data['total'],
					':sisa' => $data['sisa'],
					':status' => $data['status'],
					':status_lunas' => $data['status_lunas'],
					':ket' => $data['ket']
				)
			);
			$statement->closeCursor();
		}

		/**
		* 
		*/
		public function delete($data){
			// TRANSACT
			// print_r($data);
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

		private function deleteOperasionalProyek_Lunas($data) {
			$query = 'CALL hapus_operasional_proyek_versi2 (:id, :total, :tgl, :ket);';
			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id' => $data['id'],
					':total' => $data['total'],
					':tgl' => $data['tgl'],
					':ket' => $data['ket'],
				)
			);
			$statement->closeCursor();
		} 

		public function deleteOperasionalProyek_BelumLunas($data){
			$query = 'CALL hapus_operasional_proyek_tunai_blmlunas (:id);';
			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id' => $data['id'],							
				)
			);
			$statement->closeCursor();
		}

		public function catatMutasi($data){
			$query = 'CALL hapus_operasional_proyek_kredit_catatMutasi (
				:id,
				:id_bank,
				:total_detail,
				:tgl,
				:ket
			);';

			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id' 			=> $data['id'],
					':id_bank' 		=> $data['id_bank'],
					':total_detail'	=> $data['total_detail'],
					':tgl' 			=> $data['tgl_detail'],
					':ket'			=> ''							
				)
			);
			$statement->closeCursor();
		}

		public function deleteKredit($dataOpr){
			$query = 'CALL hapus_operasional_proyek_kredit (:id);';
			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id' => $dataOpr['id']		
				)
			);
			$statement->closeCursor();
		}

		/**
		* 
		*/
		public function deleteOperasionalProyek($data){
			$query = "CALL hapus_operasional_proyek_versi2 (
			:id, 
			:tgl,
			:total
			);";
			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id' => $data['id'],
					':tgl' => $data['tgl'],
					':ket' => $data['ket']		
				)
			);
			$statement->closeCursor();

		}



		public function getLastID($id){
			$id .= "%";
			$query = "SELECT MAX(id) AS id FROM operasional_proyek WHERE id LIKE :id";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
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
