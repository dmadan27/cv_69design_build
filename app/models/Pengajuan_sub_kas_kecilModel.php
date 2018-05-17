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
		public function getID(){
			$query = "SELECT MAX(id) as id FROM pengajuan_sub_kas_kecil";

			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

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
		public function __destruct(){
			$this->closeConnection($this->koneksi);
		}
	}