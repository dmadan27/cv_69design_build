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
			$query = "INSERT INTO kas_kecil (id,nama, alamat, no_telp, email, foto,  saldo, status) VALUES (:id, :nama, :alamat, no_telp, :email, :foto, :saldo, :status);";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $data['id']);
			$statement->bindParam(':nama', $data['nama']);
			$statement->bindParam(':alamat', $data['alamat']);
			$statement->bindParam(':no_telp', $data['no_telp']);
			$statement->bindParam(':email', $data['email']);
			$statement->bindParam(':foto', $data['foto']);
			$statement->bindParam(':saldo', $data['saldo']);
			$statement->bindParam(':status', $data['status']);
			$result = $statement->execute();
			return $result;
		}

		
		/**
		* 
		*/
		public function getById($id){
			$query = "SELECT * FROM kas_kecil WHERE id = :id;";
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
			// $query = "UPDATE kas_kecil SET nama = :nama, status = :status WHERE id = :id;";

			// $statement = $this->koneksi->prepare($query);
			// $statement->bindParam(':nama', $data['nama']);
			// $statement->bindParam(':status', $data['status']);
			// $statement->bindParam(':id', $data['id']);
			// $result = $statement->execute();

			// return $result;
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
			// $id = isset($_POST['id']) ? $_POST['id'] : false;
			// $cari = isset($_POST['cari']) ? $_POST['cari'] : null;
			// $mulai = ($page > 1) ? ($page * 10) - 10 : 0;
			
			// $this->queryMobile = 'SELECT * FROM v_proyek_logistik ';

			// $qWhere = 'WHERE id_sub_kas_kecil = "'.$id.'"';
			// $i = 0;
			// foreach($this->kolomCari_mobile as $value){
			// 	if(!is_null($cari)){
			// 		if($i === 0) $qWhere .= ' AND ('.$value.' LIKE "%'.$cari.'%" ';
			// 		else $qWhere .= 'OR '.$value.' LIKE "%'.$cari.'%"';
			// 	}
			// 	$i++;
			// }
			// if(!is_null($cari)) $qWhere .= " )";

			// $this->queryMobile .= "$qWhere LIMIT $mulai, 10";
		}

		/**
		*
		*/
		public function getAll_mobile($page){
			// $this->setQuery_mobile($page);

			// $statement = $this->koneksi->prepare($this->queryMobile);
			// $statement->execute();
			// $result = $statement->fetchAll();

			// return $result;
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
			// $koneksi = $this->openConnection();

			// $statement = $koneksi->prepare($this->queryMobile);
			// $statement->execute();

			// return $statement->rowCount();
		}

			


		/**
		* 
		*/
		public function __destruct(){
			$this->closeConnection($this->koneksi);
		}
	}