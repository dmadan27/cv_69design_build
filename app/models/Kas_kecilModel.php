<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	* 
	*/
	class Kas_kecilModel extends Database implements ModelInterface{

		protected $koneksi;
		protected $dataTable;

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
		public function getAll(){
			$data = array(
				array(
					'id' =>  'Kas-Kecil001',
					'nama' => 'John',
					'alamat' => 'Banjar',
					'no_telp' => '081353012823',
					'email' => 'John@gmail.com',
					'saldo' => '20000000',					
				),
				array(
					'id' =>  'Kas-Kecil002',
					'nama' => 'Micheal',
					'alamat' => 'Banjar',
					'no_telp' => '081353012823',
					'email' => 'Micheal@gmail.com',
					'saldo' => '20000000',					
				),
				array(
					'id' =>  'Kas-Kecil003',
					'nama' => 'Frank',
					'alamat' => 'Banjar',
					'no_telp' => '081353012823',
					'email' => 'Frank@gmail.com',
					'saldo' => '20000000',					
				),
				
				
			);

			return $data;
		}
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
		public function insert($data){
			// $status = "BERJALAN";
			// $query = "INSERT INTO proyek 
			// 				(	
			// 					id,
			// 					pemilik,
			// 					tgl,
			// 					pembangunan,
			// 					luas_area,
			// 					alamat,
			// 					kota,
			// 					estimasi,
			// 					total,
			// 					dp,
			// 					cco,
			// 					status
			// 				) VALUES
			// 				(	
			// 					:id,
			// 					:pemilik,
			// 					:tgl,
			// 					:pembangunan,
			// 					:luas_area,
			// 					:alamat,
			// 					:kota,
			// 					:estimasi,
			// 					:total,
			// 					:dp,
			// 					:cco,
			// 					:status
			// 				);
			// 				";

			// 	$statment = $this->koneksi->prepare($query);
			// 	$statment->bindParam(':id', $data['id']);
			// 	$statment->bindParam(':pemilik', $data['pemilik']);
			// 	$statment->bindParam(':tgl', $data['tgl']);
			// 	$statment->bindParam(':pembangunan', $data['pembangunan']);
			// 	$statment->bindParam(':luas_area', $data['luas_area']);
			// 	$statment->bindParam(':alamat', $data['alamat']);
			// 	$statment->bindParam(':kota', $data['kota']);
			// 	$statment->bindParam(':estimasi', $data['estimasi']);
			// 	$statment->bindParam(':total', $data['total']);
			// 	$statment->bindParam(':dp', $data['dp']);
			// 	$statment->bindParam(':cco', $data['cco']);
			// 	$statment->bindParam(':status', $data['status']);
			// 	$result = $statment->execute();

			// 	return $result;
				



		}

		
		/**
		* 
		*/
		public function getById($id){
			// $query = "SELECT * FROM proyek WHERE id = :id;";
			// $statement = $this->koneksi->prepare($query);
			// $statement->bindParam(':id', $id);
			// $statement->execute();
			// $result = $statement->fetch(PDO::FETCH_ASSOC);

			// return $result;

		}

		/**
		* 
		*/
		public function update($data){
			
		}

		/**
		* 
		*/
		public function delete($id){
			
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
			// $koneksi = $this->openConnection();

			// $statement = $koneksi->query("SELECT COUNT(*) FROM v_proyek_logistik")->fetchColumn();

			// return $statement;
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