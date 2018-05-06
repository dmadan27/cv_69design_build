<?php 
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); 

	/**
	* 
	*/
	class Laporan_pengajuan_sub_kas_kecilModel extends Database{
		
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

		/**
		* 
		*/
		public function __destruct(){
			$this->closeConnection($this->koneksi);
		}

	}