<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	* 
	*/
	class ProyekModel extends Database implements ModelInterface{
		
		protected $koneksi;
		protected $dataTable;
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
		public function insert($data){
			$status = "BERJALAN";
			$query_proyek = "INSERT INTO proyek 
							(
								pemilik,
								tgl,
								pembangunan,
								luas_area,
								alamat,
								kota,
								estimasi,
								total,
								dp,
								cco,
								status
							) VALUES
							(
								:pemilik,
								:tgl,
								:pembangunan,
								:luas_area,
								:alamat,
								:kota,
								:estimasi,
								:total,
								:dp,
								:cco
							);
							";

				$statment = $this->koneksi->prepare($query_proyek);
				$statment->bindParam(':pemilik', $data['pemilik']);
				$statment->bindParam(':tgl', $data['tgl']);
				$statment->bindParam(':pembangunan', $data['pembangunan']);
				$statment->bindParam(':luas_area', $data['luas_area']);
				$statment->bindParam(':alamat', $data['alamat']);
				$statment->bindParam(':kota', $data['kota']);
				$statment->bindParam(':estimasi', $data['estimasi']);
				$statment->bindParam(':total', $data['total']);
				$statment->bindParam(':dp', $data['dp']);
				$statment->bindParam(':cco', $data['cco']);
				return $result;
				



		}

		/**
		* 
		*/
		public function getAll(){

		}

		/**
		* 
		*/
		public function getBYId($id){
			$query_proyek = "SELECT * FROM proyek WHERE id = :id;";
			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;

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

		/**
		* 
		*/
		public function __destruct(){
			$this->closeConnection($this->koneksi);
		}		
	}