<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
	
	class Operasional_ProyekModel extends Database implements ModelInterface{
	
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
			// $query = "SELECT * FROM bank WHERE id = :id;";

			// $statement = $this->koneksi->prepare($query);
			// $statement->bindParam(':id', $id);
			// $statement->execute();
			// $result = $statement->fetch(PDO::FETCH_ASSOC);

			// return $result;
		}

		/**
		* 
		*/
		public function insert($data){
			$dataOperasionalProyek = $data['dataOperasionalProyek'];
			$dataDetail = $data['dataDetail'];
			

			try{
				$this->koneksi->beginTransaction();

				// insert data proyek
				$this->insertOperasionalProyek($dataOperasionalProyek);

				// insert data detail
				foreach ($dataDetail as $index => $row) {
					if(!$dataDetail[$index]['delete']){
						array_map('strtoupper', $row);
						$this->insertDetailOperasionalProyek($row, $dataOperasionalProyek['id']);
					}
				}
								
				$this->koneksi->commit();

				return true;
			}
			catch(PDOException $e){
				$this->koneksi->rollback();
				die($e->getMessage());
				// return false;
			}
		}

		/**
		*
		*/
		private function insertOperasionalProyek($data){
			// insert operasional_proyek
			$query = "CALL tambah_operasional_proyek (:id, :id_proyek, :id_bank, :id_kas_besar, :tgl, :nama,  :total, :ket);";
			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id' => $data['id'],
					':id_proyek' => $data['id_proyek'],
					':id_bank' => $data['id_bank'],
					':id_kas_besar' => $data['id_kas_besar'],
					':tgl' => $data['tgl'],
					':nama' => $data['nama'],
					':total' => $data['total'],
					':ket' => $data['ket']
				)
			);
			$statement->closeCursor();
		}

		/**
		*
		*/
		private function insertDetailOperasionalProyek($data, $id_operasional_proyek){
			$query = 'INSERT INTO detail_operasional_proyek (id_operasional_proyek, nama, jenis, satuan, qty, harga, subtotal, status, harga_asli, sisa, status_lunas) VALUES (:id_operasional_proyek, :nama, :jenis, :satuan, :qty, :harga, :subtotal, :status, :harga_asli, :sisa, :status_lunas)';
			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id_operasional_proyek' => $id_operasional_proyek,
					':nama' => $data['nama_detail'],
					':jenis' => $data['jenis_detail'],
					':satuan' => $data['satuan_detail'],
					':qty' => $data['qty_detail'],
					':harga' => $data['harga_detail'],
					':subtotal' => $data['sub_total_detail'],
					':status' => $data['status_detail'],
					':harga_asli' => $data['harga_asli_detail'],
					':sisa' => $data['sisa_detail'],
					':status_lunas' => $data['status_lunas_detail'],
				)
			);
			$statement->closeCursor();

		}


		/**
		* 
		*/
		public function update($data){
			// $query = "UPDATE bank SET nama = :nama, status = :status WHERE id = :id;";

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
			// $query = "DELETE FROM bank WHERE id = :id";
			
			// $statement = $this->koneksi->prepare($query);
			// $statement->bindParam(':id', $id);
			// $result = $statement->execute();

			// return $result;
		}

		public function getLastID($id){
			$id .= "%";
			$query = "SELECT MAX(id) AS id FROM operasional_proyek WHERE id LIKE :id";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

	
		/**
		* 
		*/
		public function __destruct(){
			$this->closeConnection($this->koneksi);
		}


}