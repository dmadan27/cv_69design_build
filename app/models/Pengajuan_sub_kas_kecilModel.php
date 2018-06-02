<?php
	// Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	* 
	*/
	class Pengajuan_sub_kas_kecilModel extends Database{
		
		protected $koneksi;
		protected $dataTable;
		// protected $id_sub_kas_kecil = isset($_POST['username']) ? $_POST['username'] : false;
		protected $kolomCari_mobile = array('id', 'id_proyek', 'tgl', 'total', 'dana_disetujui', 'status');
		public $queryMobile;

		/**
		* 
		*/
		public function __construct(){
			$this->koneksi = $this->openConnection();
			$this->dataTable = new Datatable();
		}

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

		/**
		* 
		*/
		public function getAll(){
			$query = "SELECT * FROM pengajuan_sub_kas_kecil";

			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll();

			return $result;
		}

		/**
		* 
		*/
		public function getAll_pending(){
			$status = "PENDING";
			$query = "SELECT * FROM pengajuan_sub_kas_kecil LIMIT  WHERE status = :status ";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':status', $status);
			$statement->execute();
			$result = $statement->fetchAll();

			return $result;
		}

		/**
		* 
		*/
		public function setQuery_mobile($page){
			$id = isset($_POST['id']) ? $_POST['id'] : false;
			$cari = isset($_POST['cari']) ? $_POST['cari'] : null;
			$mulai = ($page > 1) ? ($page * 10) - 10 : 0;
			
			$this->queryMobile = 'SELECT * FROM pengajuan_sub_kas_kecil ';

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

			$this->queryMobile .= "$qWhere ORDER BY id DESC LIMIT $mulai, 10";
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
		public function getById_mobile($id_pengajuan){
			$id = isset($_POST['id']) ? $_POST['id'] : false;

			$query = "SELECT pskc.id id_pengajuan, pskc.id_sub_kas_kecil, pskc.id_proyek, pskc.tgl, ";
			$query .= "pskc.total, pskc.dana_disetujui, pskc.status, pskc.status_laporan, dp.id id_detail, ";
			$query .= "dp.nama, dp.jenis, dp.satuan, dp.qty, dp.harga, dp.subtotal, dp.status status_detail, ";
			$query .= "dp.harga_asli, dp.sisa, dp.status_lunas FROM pengajuan_sub_kas_kecil pskc ";
			$query .= "JOIN detail_pengajuan_sub_kas_kecil dp ON dp.id_pengajuan = pskc.id ";
			$query .= "WHERE pskc.id_sub_kas_kecil = :id AND dp.id_pengajuan = :id_pengajuan";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$statement->bindParam(':id_pengajuan', $id_pengajuan);
			$result = $statement->execute();
			$result = $statement->fetchAll();

			return $result;
		}

		/**
		* 
		*/
		public function get_recordTotal_mobile(){
			$koneksi = $this->openConnection();

			$statement = $koneksi->query("SELECT COUNT(*) FROM pengajuan_sub_kas_kecil")->fetchColumn();

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
		* 
		*/
		public function insert($data) {

			$data_pengajuan = $data["pengajuan"];
			$data_detail_pengajuan = $data["detail_pengajuan"];

			
			try {
				$this->koneksi->beginTransaction();

				$this->insert_pengajuan($data_pengajuan);

				foreach ($data_detail_pengajuan as $key => $value) {
					$this->insert_detail_pengajuan($value);
				}
				$this->koneksi->commit();
				return true;
			} catch (PDOException $e) {
				$this->koneksi->rollback();
				// die($e->getMessage());
				return $e;
			}

		}

		/**
		* 
		*/	
		private function insert_pengajuan($data) {
			$query_pengajuan = "INSERT INTO pengajuan_sub_kas_kecil (id, id_sub_kas_kecil, id_proyek, tgl, total, dana_disetujui, status, status_laporan) VALUES ";
			$query_pengajuan .= "(:id, :id_sub_kas_kecil, :id_proyek, :tgl, :total, :dana_disetujui, :status, :status_laporan);";
			$statment = $this->koneksi->prepare($query_pengajuan);
			$statment->execute(
				array(
					':id' => $data->id,
					':id_sub_kas_kecil' => $data->id_sub_kas_kecil,
					':id_proyek' => $data->id_proyek,
					':tgl' => date('Y-m-d'),
					':total' => $data->total,
					':dana_disetujui' => null,
					':status' => null,
					':status_laporan' => null
				)
			);
			$statment->closeCursor();
		}

		/**
		* 
		*/
		private function insert_detail_pengajuan($data) {
			$query_detail_pengajuan	= "INSERT INTO detail_pengajuan_sub_kas_kecil (id, id_pengajuan, nama, jenis, satuan, qty, harga, subtotal, status, harga_asli, sisa, status_lunas) VALUES";
			$query_detail_pengajuan .= "(null, :id_pengajuan, :nama, :jenis, :satuan, :qty, :harga, :subtotal, :status, :harga_asli, :sisa, :status_lunas)";

			$statment = $this->koneksi->prepare($query_detail_pengajuan);
			$statment->execute(
				array(
					':id_pengajuan' => $data->id_pengajuan,
					':nama' => $data->nama,
					':jenis' => $data->jenis,
					':satuan' => $data->satuan,
					':qty' => $data->qty,
					':harga' => $data->harga,
					':subtotal' => $data->subtotal,
					':status' => null,
					':harga_asli' => null,
					':sisa' => null,
					':status_lunas' => null
				)
			);
			$statment->closeCursor();
		}	

		/**
		* 
		*/
		public function __destruct(){
			$this->closeConnection($this->koneksi);
		}
	}