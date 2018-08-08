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
		public function getPerbaikanById($id_pengajuan) {
			$query = "SELECT * FROM v_pengajuan_sub_kas_kecil WHERE (status_laporan='2') AND (id=:id_pengajuan)";

			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array (
					':id_pengajuan' => $id_pengajuan
				)
			);
			return $statement->fetch(PDO::FETCH_ASSOC);	
		}

		/**
		 * 
		 */
		public function getDetailLaporanById($id_pengajuan) {
			$query = "SELECT * FROM detail_pengajuan_sub_kas_kecil WHERE id_pengajuan=:id_pengajuan";
			
			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id_pengajuan' => $id_pengajuan,
				)
			);
			return $statement->fetchAll(PDO::FETCH_ASSOC);
		}

		/**
		 * 
		 */
		public function getJumlahFotoById($id_pengajuan) {
			$query = "SELECT COUNT(id_pengajuan) jumlah FROM upload_laporan_pengajuan_sub_kas_kecil WHERE id_pengajuan=:id_pengajuan";
			
			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id_pengajuan' => $id_pengajuan,
				)
			);
			return $statement->fetch(PDO::FETCH_ASSOC)['jumlah'];
		}

		/**
		*
		*/
		public function getAllByIdSubKasKecil_mobile($data){
			$id = $data["id_sub_kas_kecil"];
			$cari = $data["cari"];
			$page = $data["page"];

			$filter = "";
			if (strtoupper($data["filter"]) == "BELUM DIKERJAKAN") 
				$filter = " AND ((status_laporan IS NULL) OR (status_laporan='2'))";
			else if ($data["filter"] != NULL)
				$filter = " AND status_laporan='".$data['filter']."'";
				
			$queryKondisi = "WHERE id_sub_kas_kecil='".$id."' AND (status='3' OR status='4')".$filter;
			$kolomCari = array("id","nama","id_proyek","tgl");

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
