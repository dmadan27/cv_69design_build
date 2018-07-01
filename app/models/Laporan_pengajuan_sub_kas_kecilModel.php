<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	*
	*/
	class Laporan_pengajuan_sub_kas_kecilModel extends Database{

		protected $koneksi;
		protected $dataTable;
		protected $kolomCari_mobile = array('id', 'id_proyek', 'tgl', 'total', 'dana_disetujui', 'status');
		public $queryMobile;

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
			$data = array(
				array(
					'ID Pengajuan' =>  'Peng-001',
					'Nama' => 'Baja Ringan',
					'Jenis' => 'Teknis',
					'Satuan' => 'Blok',
					'Qty' => '10',
					'Harga' => '5000000',
					'Harga Asli' => '5000000',
					'Status' => 'Tunai',
					'Status Lunas' => 'Lunas',

				),

				array(
					'ID Pengajuan' =>  'Peng-001',
					'Nama' => 'Semen',
					'Jenis' => 'Teknis',
					'Satuan' => 'Sak',
					'Qty' => '20',
					'Harga' => '5000000',
					'Harga Asli' => '5000000',
					'Status' => 'Tunai',
					'Status Lunas' => 'Lunas',

				),



			);

			return $data;
		}

		/**
		*
		*/
		// public function getUser($username){
		// 	$query = "SELECT * FROM sub_kas_kecil WHERE BINARY email = :username";

		// 	$statement = $this->koneksi->prepare($query);
		// 	$statement->bindParam(':username', $username);
		// 	$statement->execute();
		// 	$result = $statement->fetch(PDO::FETCH_ASSOC);

		// 	return $result;
		// }

		// ======================== mobile ========================= //

		public function setQuery_mobile($page) {
			$id = isset($_POST['id']) ? $_POST['id'] : false;
			$cari = isset($_POST['cari']) ? $_POST['cari'] : null;
			$mulai = ($page > 1) ? ($page * 10) - 10 : 0;

			$this->queryMobile = 'SELECT * FROM pengajuan_sub_kas_kecil ';

			$qWhere = 'WHERE id_sub_kas_kecil = "'.$id.'" AND (status = "LANGSUNG" OR status = "DISETUJUI")';
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
		public function get_recordTotal_mobile(){
			$koneksi = $this->openConnection();

			$statement = $koneksi->query("SELECT COUNT(*) FROM pengajuan_sub_kas_kecil")->fetchColumn();

			return $statement;
		}

		/**
		*
		*/
		public function __destruct(){
			$this->closeConnection($this->koneksi);
		}

	}
