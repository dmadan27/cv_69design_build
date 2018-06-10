<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	* 
	*/
	class ProyekModel extends Database implements ModelInterface{
		
		protected $koneksi;
		protected $dataTable;
		protected $kolomCari_mobile = array('id_proyek', 'tgl', 'kota', 'status');
		public $queryMobile;

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
			$query = "SELECT * FROM proyek";

			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll();

			return $result;
		}

		/**
		* 
		*/
		public function getById($id){
			$query = "SELECT * FROM proyek WHERE id = :id;";
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
			$query = "SELECT id, id_proyek, angsuran, persentase, total total_detail, status status_detail ";
			$query .= "FROM detail_proyek WHERE id_proyek = :id;";
			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$statement->execute();
			$result = $statement->fetchAll();

			return $result;
		}

		/**
		*
		*/
		public function getSkkById($id){
			$query = "SELECT * FROM v_get_skk_proyek WHERE id_proyek = :id;";
			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$statement->execute();
			$result = $statement->fetchAll();

			return $result;
		}

		/**
		* 
		*/
		public function insert($data){
			$dataProyek = $data['dataProyek'];
			$dataDetail = $data['dataDetail'];
			$dataSkk = $data['dataSkk'];

			try{
				$this->koneksi->beginTransaction();

				$this->insertProyek($dataProyek);

				foreach($dataDetail as $index => $row){
					if(!$dataDetail[$index]['delete']){
						array_map('strtoupper', $row);
						$this->insertDetail($row);
					}
				}

				foreach($dataSkk as $index => $row){
					if(!$dataSkk[$index]['delete']){
						array_map('strtoupper', $row);
						$this->insertSkk($row);
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
		private function insertProyek($data){
			// insert proyek
			$query = "INSERT INTO proyek (id, pemilik, tgl, pembangunan, luas_area, alamat, kota, estimasi, total, dp, cco, status) ";
			$query .= "VALUES (:id, :pemilik, :tgl, :pembangunan, :luas_area, :alamat, :kota, :estimasi, :total, :dp, :cco, :status);";
			$statment = $this->koneksi->prepare($query);
			$statment->execute(
				array(
					':id' => $data['id'],
					':pemilik' => $data['pemilik'],
					':tgl' => $data['tgl'],
					':pembangunan' => $data['pembangunan'],
					':luas_area' => $data['luas_area'],
					':alamat' => $data['alamat'],
					':kota' => $data['kota'],
					':estimasi' => $data['estimasi'],
					':total' => $data['total'],
					':dp' => $data['dp'],
					':cco' => $data['cco'],
					':status' => $data['status'],
				)
			);
			$statment->closeCursor();
		}

		/**
		*
		*/
		private function insertDetail($data){
			// insert detail_proyek
			$query = 'INSERT INTO detail_proyek (id_proyek, angsuran, persentase, total, status) ';
			$query .= 'VALUES (:id_proyek, :angsuran, :persentase, :total, :status);';
			$statment = $this->koneksi->prepare($query);
			$statment->execute(
				array(
					':id_proyek' => $data['id_proyek'],
					':angsuran' => $data['angsuran'],
					':persentase' => $data['persentase'],
					':total' => $data['total_detail'],
					':status' => $data['status_detail'],
				)
			);
			$statment->closeCursor();
		}

		/**
		*
		*/
		private function insertSkk($data){
			$query = 'INSERT INTO logistik_proyek (id_proyek, id_sub_kas_kecil) VALUES (:id_proyek, :id_sub_kas_kecil);';
			$statment = $this->koneksi->prepare($query);
			$statment->execute(
				array(
					':id_proyek' => $data['id_proyek'],
					':id_sub_kas_kecil' => $data['id_skk'],
				)
			);
			$statment->closeCursor();
		}

		/**
		* 
		*/
		public function update($data){
			$dataProyek = $data['dataProyek'];
			$dataDetail = $data['dataDetail'];
			$dataSkk = $data['dataSkk'];

			try{
				$this->koneksi->beginTransaction();

				$this->updateProyek($dataProyek);

				foreach($dataDetail as $index => $row){
					array_map('strtoupper', $row);
					if(!$dataDetail[$index]['delete'] && $dataDetail[$index]['status'] == "edit") $this->updateDetail($row);
					else if(!$dataDetail[$index]['delete'] && $dataDetail[$index]['status'] == "tambah") $this->deleteDetail($row);
				}

				foreach($dataSkk as $index => $row){
					array_map('strtoupper', $row);
					if(!$dataSkk[$index]['delete'] && $dataSkk[$index]['status'] == "edit") $this->updateSkk($row);
					else if(!$dataSkk[$index]['delete'] && $dataSkk[$index]['status'] == "tambah") $this->deleteSkk($row);
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
		private function updateProyek($data){
			$query = "UPDATE proyek SET id = :id, pemilik = :pemilik, tgl = :tgl, pembangunan = :pembangunan, luas_area = :luas_area, ";
			$query .= "alamat = :alamat, kota = :kota, estimasi = :estimasi, total = :total, dp = :dp, cco = :cco, status = :status;";
			$statment = $this->koneksi->prepare($query);
			$statment->execute(
				array(
					':id' => $data['id'],
					':pemilik' => $data['pemilik'],
					':tgl' => $data['tgl'],
					':pembangunan' => $data['pembangunan'],
					':luas_area' => $data['luas_area'],
					':alamat' => $data['alamat'],
					':kota' => $data['kota'],
					':estimasi' => $data['estimasi'],
					':total' => $data['total'],
					':dp' => $data['dp'],
					':cco' => $data['cco'],
					':status' => $data['status'],
				)
			);
			$statment->closeCursor();
		}

		/**
		*
		*/
		private function updateDetail($data){
			$query = 'UPDATE detail_proyek SET angsuran = :angsuran, persentase = :persentase, total = :total, status = :status WHERE id = :id;';
			$statment = $this->koneksi->prepare($query);
			$statment->execute(
				array(
					':id_proyek' => $data['id_proyek'],
					':angsuran' => $data['angsuran'],
					':persentase' => $data['persentase'],
					':total' => $data['total_detail'],
					':status' => $data['status_detail'],
				)
			);
			$statment->closeCursor();
		}

		/**
		*
		*/
		private function updateSkk($data){
			$query = 'UPDATE logistik_proyek SET id_sub_kas_kecil = :id_sub_kas_kecil;';
			$statment = $this->koneksi->prepare($query);
			$statment->execute(
				array(
					':id_proyek' => $data['id_proyek'],
					':id_sub_kas_kecil' => $data['id_skk'],
				)
			);
			$statment->closeCursor();
		}

		/**
		* 
		*/
		public function delete($id){
			try{
				$query = 'CALL hapus_proyek (:id);';
				$statment = $this->koneksi->prepare($query);
				$statment->execute(
					array(':id' => $id)
				);
				$statment->closeCursor();

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
		private function deleteDetail($data){

		}

		/**
		*
		*/
		private function deleteSkk($data){

		}

		/**
		*
		*/
		public function getLastID(){
			$query = "SELECT MAX(id) id FROM proyek;";

			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

		// ======================== mobile = ======================= //
		
			/**
			* 
			*/
			public function setQuery_mobile($page){
				$id = isset($_POST['id']) ? $_POST['id'] : false;
				$cari = isset($_POST['cari']) ? $_POST['cari'] : null;
				$mulai = ($page > 1) ? ($page * 10) - 10 : 0;
				
				$this->queryMobile = 'SELECT * FROM v_proyek_logistik ';

				$qWhere = 'WHERE id_sub_kas_kecil = "'.$id.'"';
				$i = 0;
				foreach($this->kolomCari_mobile as $value){
					if(!is_null($cari)){
						if($i === 0) $qWhere .= ' AND ('.$value.' LIKE "%'.$cari.'%" ';
						else $qWhere .= 'OR '.$value.' LIKE "%'.$cari.'%"';
					}
					$i++;
				}
				if(!is_null($cari)) $qWhere .= " )";

				$this->queryMobile .= "$qWhere LIMIT $mulai, 10";
			}

			/**
			*
			*/
			public function getAll_mobile($page){
				$this->setQuery_mobile($page);

				$statement = $this->koneksi->prepare($this->queryMobile);
				$statement->execute();
				$result = $statement->fetchAll();

				return $result;
			}

			/**
			* 
			*/
			public function get_recordTotal_mobile(){
				$koneksi = $this->openConnection();

				$statement = $koneksi->query("SELECT COUNT(*) FROM v_proyek_logistik")->fetchColumn();

				return $statement;
			}

			/**
			* 
			*/
			public function get_recordFilter_mobile(){
				$koneksi = $this->openConnection();

				$statement = $koneksi->prepare($this->queryMobile);
				$statement->execute();

				return $statement->rowCount();
			}

		// ========================================================= //

		/**
		* 
		*/
		public function __destruct(){
			$this->closeConnection($this->koneksi);
		}		
	}