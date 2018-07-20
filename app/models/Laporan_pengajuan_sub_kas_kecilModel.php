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

		private $queryBeforeLimitMobile;

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

		private function querySelectBuilder_mobile($queryKondisi, $kolomCari, $cari=null, $page=1) {
			$mulai = ($page > 1) ? ($page * 10) - 10 : 0;

			$query = "SELECT * FROM pengajuan_sub_kas_kecil ";

			$i = 0;
			foreach($kolomCari as $value){
				if($cari != null){
					if($i === 0)
						$queryKondisi .= ' AND ('.$value.' LIKE "%'.$cari.'%" ';
					else
						$queryKondisi .= 'OR '.$value.' LIKE "%'.$cari.'%"';
				}
				$i++;
			}

			if($cari != null)
				$queryKondisi .= " )";

			$query .= "$queryKondisi ";
			$this->queryBeforeLimitMobile = $query;
			$query .= "LIMIT $mulai, 10";
			return $query;
		}


		/**
		*
		*/
		public function getAllByIdSubKasKecil_mobile($data){
			$id = $data["id_sub_kas_kecil"];
			$cari = $data["cari"];
			$page = $data["page"];

			$queryKondisi = "WHERE id_sub_kas_kecil='".$id."' AND (status='DISETUJUI' OR status='LANGSUNG')";
			$kolomCari = array("id","nama","id_proyek","tgl","status_laporan");

			$query = $this->querySelectBuilder_mobile($queryKondisi, $kolomCari, $cari, $page);

			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			return $statement->fetchAll(PDO::FETCH_ASSOC);
		}

		/**
		*
		*/
		public function getRecordFilter_mobile(){
			$koneksi = $this->openConnection();
			$statement = $koneksi->prepare($this->queryBeforeLimitMobile);
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
