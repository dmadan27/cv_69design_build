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

				// insert data proyek
				$this->insertProyek($dataProyek);

				// insert data detail
				foreach($dataDetail as $index => $row){
					if(!$dataDetail[$index]['delete']){
						array_map('strtoupper', $row);
						$this->insertDetail($row);
					}
				}

				// insert data logistik proyek / skk
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
			$query = "INSERT INTO proyek (id, pemilik, tgl, pembangunan, luas_area, alamat, kota, estimasi, total, dp, cco, progress, status) ";
			$query .= "VALUES (:id, :pemilik, :tgl, :pembangunan, :luas_area, :alamat, :kota, :estimasi, :total, :dp, :cco, :progress, :status);";
			$statement = $this->koneksi->prepare($query);
			$statement->execute(
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
					':progress' => $data['progress'],
				)
			);
			$statement->closeCursor();
		}

		/**
		*
		*/
		private function insertDetail($data){
			// insert detail_proyek
			$query = 'INSERT INTO detail_proyek (id_proyek, angsuran, persentase, total, status) ';
			$query .= 'VALUES (:id_proyek, :angsuran, :persentase, :total, :status);';

			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id_proyek' => $data['id_proyek'],
					':angsuran' => $data['angsuran'],
					':persentase' => $data['persentase'],
					':total' => $data['total_detail'],
					':status' => $data['status_detail'],
				)
			);
			$statement->closeCursor();
		}

		/**
		*
		*/
		private function insertSkk($data){
			$query = 'INSERT INTO logistik_proyek (id_proyek, id_sub_kas_kecil) VALUES (:id_proyek, :id_sub_kas_kecil);';
			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id_proyek' => $data['id_proyek'],
					':id_sub_kas_kecil' => $data['id_skk'],
				)
			);
			$statement->closeCursor();
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


				// update proyek
				$this->updateProyek($dataProyek);

				// update data detail
				foreach($dataDetail as $index => $row){
					array_map('strtoupper', $row);
					// jika diedit
					if(!$dataDetail[$index]['delete'] && $dataDetail[$index]['aksi'] == "edit")
						$this->updateDetail($row);
					// jika ada penambahan
					else if(!$dataDetail[$index]['delete'] && $dataDetail[$index]['aksi'] == "tambah")
						$this->insertDetail($row);
					// jika ada penghapusan
					else if($dataDetail[$index]['delete'] && $dataDetail[$index]['aksi'] == "edit")
						$this->deleteDetail($row['id']);
				}

				// update logistik proyek / skk
				foreach($dataSkk as $index => $row){
					array_map('strtoupper', $row);
					// jika ada penambahan
					if(!$dataSkk[$index]['delete'] && $dataSkk[$index]['aksi'] == "tambah")
						$this->insertSkk($row);
					// jika ada penghapusan
					else if($dataSkk[$index]['delete'] && $dataSkk[$index]['aksi'] == "edit")
						$this->deleteSkk($row['id']);
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
			$query = "UPDATE proyek SET pemilik = :pemilik, tgl = :tgl, pembangunan = :pembangunan, luas_area = :luas_area, ";
			$query .= "alamat = :alamat, kota = :kota, estimasi = :estimasi, total = :total, ";
			$query .= "dp = :dp, cco = :cco, progress = :progress, status = :status WHERE id = :id;";

			$statement = $this->koneksi->prepare($query);
			$statement->execute(
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
					':progress' => $data['progress'],
				)
			);
			$statement->closeCursor();
		}

		/**
		*
		*/
		private function updateDetail($data){
			$query = 'UPDATE detail_proyek SET angsuran = :angsuran, persentase = :persentase, total = :total, status = :status WHERE id = :id;';
			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id' => $data['id'],
					':angsuran' => $data['angsuran'],
					':persentase' => $data['persentase'],
					':total' => $data['total_detail'],
					':status' => $data['status_detail'],
				)
			);
			$statement->closeCursor();
		}

		/**
		*
		*/
		public function delete($id){
			try{
				$query = 'CALL hapus_proyek (:id);';

				$this->koneksi->beginTransaction();

				$statement = $this->koneksi->prepare($query);
				$statement->execute(
					array(':id' => $id)
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
		private function deleteDetail($id){
			$query = 'DELETE FROM detail_proyek WHERE id = :id';
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
		private function deleteSkk($id){
			$query = 'DELETE FROM logistik_proyek WHERE id=:id;';
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
		public function getLastID($id){
			// $query = "SELECT MAX(id) id FROM proyek;";
			$id .= "%";
			$query = "SELECT MAX(id) AS id FROM proyek WHERE id LIKE :id";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
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
			private function querySelectBuilder_mobile($queryKondisi, $kolomCari, $cari=null, $page=1) {
				$mulai = ($page > 1) ? ($page * 10) - 10 : 0;

				$query = "SELECT * FROM v_proyek_logistik ";

				$i = 0;
				foreach ($kolomCari as $value) {
					if ($cari != null) {
						if ($i === 0)
							$queryKondisi .= " AND (".$value." LIKE '%".$cari."%' ";
						else
							$queryKondisi .= "OR ".$value." LIKE '%".$cari."%' ";
						$i++;
					}
				}

				if ($cari != null)
					$queryKondisi .= ")";

			 	$query .= "$queryKondisi LIMIT $mulai, 10";
				return $query;
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
			public function getAllByIdSubKasKecil_mobile($data) {
				$id = $data["id_sub_kas_kecil"];
				$cari = $data["cari"];
				$page = $data["page"];

				$queryKondisi = "WHERE id_sub_kas_kecil='".$id."'";
				$kolomCari = array("pemilik","tgl","alamat","kota","status");
				$query = $this->querySelectBuilder_mobile($queryKondisi, $kolomCari, $cari, $page);

				$statement = $this->koneksi->prepare($query);
				$statement->execute();
				return $statement->fetchAll(PDO::FETCH_ASSOC);
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
