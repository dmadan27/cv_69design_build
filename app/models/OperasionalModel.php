<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	* Class BankModel, implementasi ke ModelInterface
	*/
	class OperasionalModel extends Database implements ModelInterface{

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
			$query = "SELECT * FROM operasional WHERE id = :id;";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

		/**
		*
		*/
		public function getByid_fromView($id){
			$query = "SELECT * FROM v_operasional WHERE id = :id;";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;

		}

		/*
		*
		*/
		public function getExport($tgl_awal, $tgl_akhir){
			if($tgl_awal == '' || $tgl_akhir == ''){
				$query = "SELECT * FROM v_operasional_export;";
			} else {
				$query = "SELECT * FROM v_operasional_export WHERE TANGGAL BETWEEN :tgl_awal AND :tgl_akhir;";
			}
			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':tgl_awal' => $tgl_awal,
					':tgl_akhir' => $tgl_akhir
				)
			);
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}

		/**
		* 
		*/
		public function insert($data){
			try{
				$this->koneksi->beginTransaction();

				$query = "CALL tambah_operasional (
					:id_bank,
					:id_kas_besar,
					:tgl,
					:nama,
					:nominal,
					:jenis,
					:ket,
					:ket_mutasi
				)";

				$statement = $this->koneksi->prepare($query);
				$statement->execute(
					array(
						':id_bank' => $data['id_bank'],
						':id_kas_besar' => $data['id_kas_besar'],
						':tgl' => $data['tgl'],
						':nama' => $data['nama'],
						':nominal' => $data['nominal'],
						':jenis' => $data['jenis'],
						':ket' => $data['ket'],
						':ket_mutasi' => ''
					)
				);
				$statement->closeCursor();

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
		public function update($data){
			try{
				$this->koneksi->beginTransaction();

				if($data['jenis'] == "UANG MASUK"){
					$this->editMasuk($data);
				} else if($data['jenis'] == "UANG KELUAR") {
					$this->editKeluar($data);
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
		private function editMasuk($data) {
			$query = "CALL edit_operasional_masuk (
				:id,
				:id_bank,
				:tgl,
				:nama,
				:nominal,
				:jenis,
				:ket,
				:ket_mutasi
			)";

			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id' => $data['id'],
					':id_bank' => $data['id_bank'],
					':tgl' => $data['tgl'],
					':nama' => $data['nama'],
					':nominal' => $data['nominal'],
					':jenis' => $data['jenis'],
					':ket' => $data['ket'],
					':ket_mutasi' => ''
				)
			);
			$statement->closeCursor();
		}

		/**
		* 
		*/
		private function editKeluar($data) {
			$query = "CALL edit_operasional_keluar (
				:id,
				:id_bank,
				:tgl,
				:nama,
				:nominal,
				:jenis,
				:ket,
				:ket_mutasi
			)";

			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id' => $data['id'],
					':id_bank' => $data['id_bank'],
					':tgl' => $data['tgl'],
					':nama' => $data['nama'],
					':nominal' => $data['nominal'],
					':jenis' => $data['jenis'],
					':ket' => $data['ket'],
					':ket_mutasi' => ''
				)
			);
			$statement->closeCursor();
		}

		/**
		* 
		*/
		public function delete($data){
			// TRANSACT
			 try {
			 	$this->koneksi->beginTransaction();

				$this->hapusOperasional($data);

				$this->koneksi->commit();

				return true;
			 	
			 }
			  catch (PDOException $e) {
				 	$this->koneksi->rollback();
					die($e->getMessage());
					// return false;
			 	
			 }


			
		}

		/**
		*
		*/
		public function hapusOperasional($data){
			// $level = ""
			$query = "CALL hapus_operasional (
				:id,
				:tgl,
				:ket);";
			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id' => $data['id'],
					':tgl' => $data['tgl'],
					':ket' => $data['ket'],
				)
			);
			$statement->closeCursor();
		}

		/**
		* 
		*/
		public function __destruct(){
			$this->closeConnection($this->koneksi);
		}

	}