<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");


	class Beranda_kas_besarModel extends Database implements ModelInterface{

		protected $koneksi;
		protected $dataTable;


		public function __construct(){
			$this->koneksi = $this->openConnection();
			$this->dataTable = new Datatable();
		}

			/**
			 * Method getAllDataTable
			 * @param config {array}
			 * @return result {array}
			 */
			public function getAllDataTable($config){
				$this->dataTable->set_config($config);
				$statement = $this->koneksi->prepare($this->dataTable->getDataTable());
				$statement->execute();
				$result = $statement->fetchAll();

				return $result;
			}

			/**
			 * Method getAllDataTable
			 * @param config {array}
			 * @return result {array}
			 */
			public function getAllDataTable_2($config){
				$this->dataTable->set_config($config);
				$statement = $this->koneksi->prepare($this->dataTable->getDataTable());
				$statement->execute();
				$result = $statement->fetchAll();

				return $result;
			}


			/**
			 * Method recordFilter
			 * @return result {int}
			 */
			public function recordFilter(){
				return $this->dataTable->recordFilter();
			}

			/**
			 * Method recordTotal
			 * @return result {int}
			 */
			public function recordTotal(){
				return $this->dataTable->recordTotal();
			}

			/**
		 * Method getAll
		 * Proses get semua data proyek
		 * @return result {array}
		 */
		public function getAll(){
			$query = "SELECT * FROM v_tes_berandakasbesar";

			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll();

			return $result;
		}

		/**
		 * Method getById
		 * Proses get data proyek berdasarkan id
		 * @param id {string}
		 * @return result {array}
		 */
		public function getById($id){
			$query = "SELECT * FROM v_tes_berandakasbesar WHERE id = :id;";
			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

		/*
			Method untuk mendapatkan saldo kas kecil dan sub kas kecil
		*/
		public function getSaldoKK_SKK(){
			$query = "SELECT * FROM v_saldo_kasbesar_and_subkaskecil";
			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

		/**
		 * Method insert
		 * Proses insert data proyek secara menyeluruh
		 * Insert proyek, insert detail proyek, dan insert detail skk proyek
		 * @param data {array}
		 * @return result {array}
		 */
		public function insert($data){
			
		}


		/**
		 * Method update
		 * Proses update data proyek secara menyeluruh
		 * Update proyek, udpate detail proyek, dan udpate detail skk proyek
		 * @param data {array}
		 * @return result {array}
		 */
		public function update($data){
			
		}


		/**
		 * Method delete
		 * Proses penghapusan data proyek beserta data yang berelasi denganya
		 */
		public function delete($id){
			
		}


		/**
			 * Method __destruct
			 * Close connection to DB
			 */
			public function __destruct(){
				$this->closeConnection($this->koneksi);
			}

	}