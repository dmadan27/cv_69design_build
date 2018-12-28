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
		* 
		*/
		public function getDetailById($id){
			$query = "SELECT * FROM detail_operasional_proyek WHERE id_operasional_proyek = :id;";
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
						//insert data operasaional proyek kondisi kredit lunas
						$this->insertOperasionalProyek_KreditLunas($dataOperasionalProyek);

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

					$dataOperasionalProyek['sisa'] = $dataOperasionalProyek['total'] - $sum;
					$dataOperasionalProyek['sum'] = $sum;
					
					//insert data operasaional proyek kondisi kredit belum lunas
					$this->insertOperasionalProyek_KreditBelumLunas($dataOperasionalProyek);

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
		private function insertOperasionalProyek_KreditLunas($data) {
			$query = "CALL tambah_operasional_proyek_kreditlunas (
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
					':sisa' => '0',
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
			//insert detail operasional proyek
			$query = 'INSERT INTO detail_operasional_proyek (id_operasional_proyek, id_bank, nama, tgl, total) 
					  VALUES (:id_operasional_proyek, :id_bank, :nama, :tgl, :total)';
			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id_operasional_proyek' => $id_operasional_proyek,
					':id_bank' => $data['id_bank'],
					':nama' => $data['nama_detail'],
					':tgl' => $data['tgl_detail'],
					':total' => $data['total_detail']
				)
			);
			$statement->closeCursor();

		}


		/**
		* 
		*/
		public function update($data){
			$query = "UPDATE operasional_proyek SET tgl = :tgl, nama = :nama, jenis = :jenis, total = :total, sisa = :sisa, status = :status, status_lunas = :status_lunas, ket = :ket WHERE id = :id;";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':tgl', $data['tgl']);
			$statement->bindParam(':nama', $data['nama']);
			$statement->bindParam(':jenis', $data['jenis']);
			$statement->bindParam(':total', $data['total']);
			$statement->bindParam(':sisa', $data['sisa']);
			$statement->bindParam(':status', $data['status']);
			$statement->bindParam(':status_lunas', $data['status_lunas']);
			$statement->bindParam(':ket', $data['ket']);
			
			
			$statement->bindParam(':id', $data['id']);
			$result = $statement->execute();

			return $result;
		}

		/**
		* 
		*/
		public function delete($data){
			// TRANSACT
			try{
				$query = 'CALL hapus_operasional_proyek_versi2 (:id);';

				$this->koneksi->beginTransaction();

				$statement = $this->koneksi->prepare($query);
				$statement->execute(
					array(
						':id' => $data['id'],
							
					)
				);
				$statement->closeCursor();

				$this->koneksi->commit();

				return array(
					'success' => true,
					'error' => NULL
				);
			}
			catch(PDOException $e){
				$this->koneksi->rollback();
				die($e->getMessage());
				// return false;
			}
		}

		public function catatMutasi($data){
			// TRANSACT
			try{
				$query = 'CALL hapus_operasional_proyek_catat_mutasiKredit (
					:id,
					:id_bank,
					:total_detail,
					:tgl,
					:ket
				);';

				$this->koneksi->beginTransaction();

				$statement = $this->koneksi->prepare($query);
				$statement->execute(
					array(
						':id' 			=> $data['id'],
						':id_bank' 		=> $data['id_bank'],
						':total_detail'	=> $data['total'],
						':tgl' 			=> $data['tgl'],
						':ket'			=> $data['ket']							
					)
				);
				$statement->closeCursor();

				$this->koneksi->commit();

				return array(
					'success' => true,
					'error' => NULL
				);
			}
			catch(PDOException $e){
				$this->koneksi->rollback();
				die($e->getMessage());
				// return false;
			}
		}

		public function deleteKredit($data){
			// TRANSACT
			try{
				$query = 'CALL hapus_operasional_proyek_kredit (
					:id
				);';

				$this->koneksi->beginTransaction();

				$statement = $this->koneksi->prepare($query);
				$statement->execute(
					array(
						':id' 			=> $data['id'],							
					)
				);
				$statement->closeCursor();

				$this->koneksi->commit();

				return array(
					'success' => true,
					'error' => NULL
				);
			}
			catch(PDOException $e){
				$this->koneksi->rollback();
				die($e->getMessage());
				// return false;
			}
		}

		public function delete_TunaiBelumLunas($data){
			// TRANSACT
			try{
				$query = 'CALL hapus_operasional_proyek_tunai_blmlunas (:id);';

				$this->koneksi->beginTransaction();

				$statement = $this->koneksi->prepare($query);
				$statement->execute(
					array(
						':id' => $data['id'],							
					)
				);
				$statement->closeCursor();

				$this->koneksi->commit();

				return array(
					'success' => true,
					'error' => NULL
				);
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
