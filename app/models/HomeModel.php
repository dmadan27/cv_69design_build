<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
	/**
	*	Class HomeModel, implementasi ModelInterface
	*/

	class HomeModel extends Database implements ModelInterface{

		protected $koneksi;
		protected $dataTable;

		/**
		* fungsi yang dijalankan saat memanggil kelas model
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

        // ========================================================= //
            /**
            *   DASHBOARD KAS BESAR - START
            */
        // ========================================================= //
        
        /**
		* 
        */
        public function getCountUser() {
            $query = "SELECT COUNT(username) AS 'user_aktif' FROM user WHERE status = 'AKTIF'";

			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
        }

        /**
		* 
        */
        public function getAccpkk() {
            $query = "SELECT COUNT(id) AS 'jml_transaksi_disetujui', SUM(total_disetujui) AS 'total_disetujui' FROM pengajuan_kas_kecil WHERE STATUS = '2'";

			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
        }

        /**
		* 
        */
        public function getPendingpkk() {
            $query = "SELECT COUNT(id) AS 'jml_transaksi_pending', SUM(total) AS 'total_pengajuan' FROM pengajuan_kas_kecil WHERE STATUS = '0'";

			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
        }

        /**
		* 
        */
        public function getOprMasuk() {
            $query = "SELECT COUNT(id) AS 'jml_uang_masuk', SUM(nominal) AS 'total_uang_masuk' FROM operasional WHERE jenis = 'UANG MASUK'";

			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
        }

         /**
		* 
        */
        public function getOprKeluar() {
            $query = "SELECT COUNT(id) AS 'jml_uang_keluar', SUM(nominal) AS 'total_uang_keluar' FROM operasional WHERE jenis = 'UANG KELUAR'";

			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
        }

        // ========================================================= //
            /**
            *   DASHBOARD KAS BESAR - END
            */
        // ========================================================= //

        // ========================================================= //
            /**
            *   DASHBOARD KAS KECIL - START
            */
        // ========================================================= //

        /**
		*   Total Uang Pengajuan Sub Kas Kecil Yang Di Acc
        */
        public function getSumAccSpkk() {
            $query = "SELECT COALESCE(SUM(dana_disetujui),0) AS 'dana_disetujui' FROM pengajuan_sub_kas_kecil WHERE STATUS = '3'";

			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
        }

        /**
		*   Total Uang Pengajuan Sub Kas Kecil Yang Di Acc
        */
        public function getSumPendingSpkk() {
            $query = "SELECT COALESCE(SUM(dana_disetujui),0) AS 'dana_transaksi_pending' FROM pengajuan_sub_kas_kecil WHERE STATUS = '1'";

			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
        }

        /**
		*   Total Transaksi Pengajuan Sub Kas Kecil Yang Di Acc
        */
        public function getPendingSpkk() {
            $query = "SELECT COUNT(id) AS 'jml_transaksi_pending_spkk' FROM pengajuan_sub_kas_kecil WHERE STATUS = '1'";

			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
        }

        /**
		*   Total Transaksi Pengajuan Sub Kas Kecil Yang Di Acc
        */
        public function getPkk() {
            $query = "SELECT COUNT(id) AS 'jml_transaksi_pkk' FROM pengajuan_kas_kecil";

			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

		 /**
		*   Total Transaksi Pengajuan Sub Kas Kecil Yang Di Acc
        */
        public function getSpkk() {
            $query = "SELECT COUNT(id) AS 'jml_transaksi_spkk' FROM pengajuan_sub_kas_kecil";

			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}
		
		/**
		*   Jumlah Transaksi Pengajuan Operasional Proyek Kredit
        */
        public function getOprProyekKredit() {
            $query = "SELECT COUNT(id) AS 'jml_transaksi_kredit' FROM operasional_proyek WHERE status = 'KREDIT'";

			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}
		
		/**
		*   Jumlah Transaksi Pengajuan Operasional Proyek Tunai
        */
        public function getOprProyekTunai() {
            $query = "SELECT COUNT(id) AS 'jml_transaksi_tunai' FROM operasional_proyek WHERE status = 'TUNAI'";

			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
        }

        // ========================================================= //
            /**
            *   DASHBOARD KAS KECIL - END
            */
        // ========================================================= //
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
		
		public function __destruct(){
			$this->closeConnection($this->koneksi);
		}
	}
