<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	* 
	*/
	class ProyekModel extends Database implements ModelInterface{
		
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
		public function __destruct(){
			$this->closeConnection($this->koneksi);
		}		
	}