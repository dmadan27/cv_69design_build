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
			
			//Mendapatkan Total Detail Operasional Proyek
			$sum = 0;
			foreach ($dataDetail as $index => $row) {
				$sum += $row['total_detail'];
				
			}
			// print_r($sum);
			// 	exit;
			try{
				$this->koneksi->beginTransaction();

				// insert data proyek
				$this->insertOperasionalProyek($dataOperasionalProyek);

				// insert data detail
				foreach ($dataDetail as $index => $row) {
					if(!$dataDetail[$index]['delete']){
						array_map('strtoupper', $row);
						$this->insertDetailOperasionalProyek($row, $dataOperasionalProyek['id']);
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
		private function insertOperasionalProyek($data){
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
		public function delete($id){
			// TRANSACT
			try{
				$query = 'CALL hapus_operasional_proyek_versi2 (:id, :tgl, :ket);';

				$this->koneksi->beginTransaction();

				$statement = $this->koneksi->prepare($query);
				$statement->execute(
					array(
						':id' => $id,
						':tgl' => $tgl,
						':ket' => $ket,
							
					)
				);
				$statement->closeCursor();

				$this->koneksi->commit();

				// return array(
				// 	'success' => true,
				// 	'error' => NULL
				// );
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
