<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	* 
	*/
	class Mutasi_saldo_sub_kas_kecilModel extends Database{
		
		protected $koneksi;
		protected $dataTable;

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
			
		}

		/**
		* 
		*/
		public function insert($data){

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

		// ======================== mobile = ======================= //

			/**
			*
			*/
			public function setQuery_mobile($page){
				$id = isset($_POST['id']) ? $_POST['id'] : false;
				$cari = isset($_POST['cari']) ? $_POST['cari'] : null;
				$mulai = ($page > 1) ? ($page * 10) - 10 : 0;

				// $this->queryMobile = 'SELECT * FROM mutasi_saldo_sub_kas_kecil ';
				$query = 'SELECT * FROM mutasi_saldo_sub_kas_kecil ';

				$qWhere = 'WHERE id_sub_kas_kecil = "'.$id.'" ';
				$i = 0;
				foreach($this->kolomCari_mobile as $value){
					if(!is_null($cari)){
						if($i === 0) $qWhere .= ' AND ('.$value.' LIKE "%'.$cari.'%" ';
						else $qWhere .= 'OR '.$value.' LIKE "%'.$cari.'%"';
					}
					$i++;
				}
				if(!is_null($cari)) $qWhere .= " )";

				// $this->queryMobile .= "$qWhere ORDER BY tgl DESC LIMIT $mulai, 10";
				return $query .= "$qWhere ORDER BY tgl DESC LIMIT $mulai, 10";
			}

			/**
			*
			*/
			public function getAll_mobile($page){
				$this->queryMobile = $this->setQuery_mobile($page);

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

				$statement = $koneksi->query("SELECT COUNT(*) FROM mutasi_saldo_sub_kas_kecil")->fetchColumn();

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

		// ========================================================= //

		/**
		* 
		*/
		public function __destruct(){
			$this->closeConnection($this->koneksi);
		}

	}