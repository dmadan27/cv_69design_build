<?php
class Pengajuan_sub_kas_kecilModel extends Database{

	protected $koneksi;
	protected $dataTable;

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
					'ID Sub Kas Kecil' => 'LOG-001',
					'ID Proyek' => 'Proy-001',
					'Nama Proyek' => 'Rumah Tingkat 2',
					'Tanggal' => '1-4-2018',
					'Total' => 'Rp.300.000.000',
					'Status Pengajuan' => 'Disetujui',
					'Status Laporan' => 'Disetujui',
								
				),
				array(
					'ID Sub Kas Kecil' => 'LOG-002',
					'ID Proyek' => 'Proy-002',
					'Nama Proyek' => 'Rumah Tingkat 5',
					'Tanggal' => '2-4-2018',
					'Total' => 'Rp.200.000.000',
					'Status Pengajuan' => 'Disetujui',
					'Status Laporan' => 'Disetujui',
								
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

		/**
		* 
		*/
		public function __destruct(){
			$this->closeConnection($this->koneksi);
		}
}