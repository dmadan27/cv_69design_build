<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	* 
	*/
	class ProyekModel extends Database implements ModelInterface{
		
		protected $koneksi;
		protected $dataTable;
		protected $kolomCari_mobile = array('id_proyek', 'tgl', 'kota', 'status');
		public $queryMobile;

		/**
		* 
		*/
		public function __construct(){
			$this->koneksi = $this->openConnection();
			$this->dataTable = new Datatable();
		}

		// ======================= dataTable ======================= //

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

		// ========================================================= //
			

		/**
		* 
		*/
		public function getAll(){

		}

		/**
		* 
		*/
		public function getById($id){
			$query = "SELECT * FROM proyek WHERE id = :id;";
			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;

		}

		/**
		*
		*/
		public function getDetailById($id){
			$query = "SELECT id, id_proyek, angsuran, persentase, total total_detail, status status_detail ";
			$query .= "FROM detail_proyek WHERE id_proyek = :id;";
			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$statement->execute();
			$result = $statement->fetchAll();

			return $result;
		}

		/**
		*
		*/
		public function getSkcById($id){
			$query = "SELECT lp.id, lp.id_proyek, skc.id id_skc, skc.nama FROM logistik_proyek lp ";
			$query .= "JOIN sub_kas_kecil skc ON skc.id = lp.id_sub_kas_kecil ";
			$query .= "WHERE lp.id_proyek = :id;";
			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$statement->execute();
			$result = $statement->fetchAll();

			return $result;
		}

		/**
		* 
		*/
		public function insert($data){
			$dataProyek = $data['dataProyek'];
			$dataDetail = $data['dataDetail'];
			$dataSkc = $data['dataSkc'];

			try{
				$this->koneksi->beginTransaction();

				// insert proyek
				$queryProyek = "INSERT INTO proyek (id, pemilik, tgl, pembangunan, luas_area, alamat, kota, estimasi, total, dp, cco, status) ";
				$queryProyek .= "VALUES (:id, :pemilik, :tgl, :pembangunan, :luas_area, :alamat, :kota, :estimasi, :total, :dp, :cco, :status);";
				$statment = $this->koneksi->prepare($queryProyek);
				$statment->execute(
					array(
						':id' => $dataProyek['id'],
						':pemilik' => $dataProyek['pemilik'],
						':tgl' => $dataProyek['tgl'],
						':pembangunan' => $dataProyek['pembangunan'],
						':luas_area' => $dataProyek['luas_area'],
						':alamat' => $dataProyek['alamat'],
						':kota' => $dataProyek['kota'],
						':estimasi' => $dataProyek['estimasi'],
						':total' => $dataProyek['total'],
						':dp' => $dataProyek['dp'],
						':cco' => $dataProyek['cco'],
						':status' => $dataProyek['status'],
					)
				);
				$statment->closeCursor();

				// insert detail_proyek
				$queryDetail = 'INSERT INTO detail_proyek (id_proyek, angsuran, persentase, total, status) ';
				$queryDetail .= 'VALUES (:id_proyek, :angsuran, :persentase, :total, :status);';
				$statment = $this->koneksi->prepare($queryDetail);

				foreach($dataDetail as $index => $row){
					if(!$dataDetail[$index]['delete']){
						array_map('strtoupper', $row);
						$statment->execute(
							array(
								':id_proyek' => $row['id_proyek'],
								':angsuran' => $row['angsuran'],
								':persentase' => $row['persentase'],
								':total' => $row['total_detail'],
								':status' => $row['status_detail'],
							)
						);
					}
				}
				$statment->closeCursor();

				// insert logistik_proyek
				$querySkc = 'INSERT INTO logistik_proyek (id_proyek, id_sub_kas_kecil) VALUES (:id_proyek, :id_sub_kas_kecil);';
				$statment = $this->koneksi->prepare($querySkc);

				foreach($dataSkc as $index => $row){
					if(!$dataSkc[$index]['delete']){
						array_map('strtoupper', $row);
						$statment->execute(
							array(
								':id_proyek' => $row['id_proyek'],
								':id_sub_kas_kecil' => $row['id_skc'],
							)
						);
					}
				}
				$statment->closeCursor();

				$this->koneksi->commit();

				return true;
			}
			catch(PDOException $e){
				$this->koneksi->rollback();
				die($e->getMessage());
				// return false;
			}



			

			
			
			$result = $statment->execute();

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
		public function deleteDetail($data){

		}

		/**
		*
		*/
		public function deleteSkc($data){

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