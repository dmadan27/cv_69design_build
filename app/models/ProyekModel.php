<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	 * Class ProyekModel
	 * Implements ModelInterface
	 */
	class ProyekModel extends Database implements ModelInterface{

		protected $koneksi;
		protected $dataTable;

		private $queryBeforeLimitMobile;

		/**
		 * Method __construct
		 * Open connection to DB
		 * Access library dataTable
		 */
		public function __construct(){
			$this->koneksi = $this->openConnection();
			$this->dataTable = new Datatable();
		}

		// ======================= dataTable ======================= //

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

		// ========================================================= //

		/**
		 * Method getAll
		 * Proses get semua data proyek
		 * @return result {array}
		 */
		public function getAll(){
			$query = "SELECT * FROM proyek;";

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
			$query = "SELECT * FROM proyek WHERE id = :id;";
			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

		/**
		 * Method getDetailById
		 * Proses get data detail proyek berdasarkan id proyek
		 * @param id {string}
		 * @return result {array}
		 */
		public function getDetailById($id){
			$query = "SELECT dp.id, id_proyek, id_bank, b.nama nama_bank, dp.tgl tgl_detail, dp.nama nama_detail, dp.total total_detail, is_DP ";
			$query .= "FROM detail_proyek dp "; 
			$query .= "JOIN bank b ON b.id = dp.id_bank ";
			$query .= "JOIN proyek p ON p.id = dp.id_proyek ";
			$query .= "WHERE p.id = :id;";
			
			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$statement->execute();
			$result = $statement->fetchAll();

			return $result;
		}

		/**
		 * Method getSkkById
		 * Proses get data detail SKK proyek berdasarkan id proyek
		 * @param id {string}
		 * @return result {array}
		 */
		public function getSkkById($id){
			$query = "SELECT * FROM v_get_skk_proyek WHERE id_proyek = :id;";
			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$statement->execute();
			$result = $statement->fetchAll();

			return $result;
		}

		/**
		 * Method getTermintMasuk
		 * Proses get data sum total detail berdasarkan id proyek
		 * @param id {string}
		 * @return result {array}
		 */
		public function getTermintMasuk($id) {
			$query = "SELECT sum(total) total_termint FROM detail_proyek ";
			$query .= "WHERE id_proyek = :id ";
			$query .= "GROUP BY id_proyek;";
			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

		/**
		 * 
		 */
		public function getKeluaranTunai($id) {
			$total_sub_kas_kecil = $this->getPengeluaran_SubKasKecil($id)['total'];
			$total_operasional_proyek = $this->getPengeluaran_operasionalProyek($id, 'TUNAI')['total'];

			return $total_sub_kas_kecil + $total_operasional_proyek;
		}

		/**
		 * 
		 */
		private function getPengeluaran_SubKasKecil($id_proyek) {
			$query = "SELECT total FROM v_get_pengeluaran_sub_kas_kecil WHERE id_proyek = :id_proyek;";
			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id_proyek', $id_proyek);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

		/**
		 * 
		 */
		public function getPengeluaran_operasionalProyek($id_proyek, $status) {
			$query = "SELECT total FROM v_get_pengeluaran_operasional_proyek ";
			$query .= "WHERE id_proyek = :id_proyek AND status = :status;";
			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id_proyek' => $id_proyek,
					':status' => $status,
				)
			);
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

		/**
		 * Method get_selectSkk
		 * Proses get data skk yang aktif untuk keperluan select di proyek
		 * @return result {array}
		 */
		public function get_selectSkk(){
			$status = 'AKTIF';
			$query = "SELECT * FROM sub_kas_kecil WHERE status = :status";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':status', $status);
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);

			return $result;
		}

		/**
		 * Method get_selectBank
		 * Proses get data bank yang aktif untuk keperluan select di proyek
		 * @return result {array}
		 */
		public function get_selectBank(){
			$status = 'AKTIF';
			$query = "SELECT * FROM bank WHERE status = :status";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':status', $status);
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);

			return $result;
		}

		/**
		 * Method getLastID
		 * Proses get data id proyek terakhir
		 * @param id {string}
		 * @return result {array}
		 */
		public function getLastID($id){
			$id .= "%";
			$query = "SELECT MAX(id) AS id FROM proyek WHERE id LIKE :id";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
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
			$dataProyek = $data['dataProyek'];
			$dataDetail = $data['dataDetail'];
			$dataSkk = $data['dataSkk'];

			try{
				$this->koneksi->beginTransaction();

				// insert data proyek
				$this->insertProyek($dataProyek);

				// insert data detail
				foreach($dataDetail as $index => $row){
					if(!$dataDetail[$index]['delete']){
						array_map('strtoupper', $row);
						$this->insertDetail($row);
					}
				}

				// insert data logistik proyek / skk
				foreach($dataSkk as $index => $row){
					if(!$dataSkk[$index]['delete']){
						array_map('strtoupper', $row);
						$this->insertSkk($row);
					}
				}

				$this->koneksi->commit();

				return array(
					'success' => true,
					'error' => NULL
				);
			}
			catch(PDOException $e){
				$this->koneksi->rollback();
				return array(
					'success' => false,
					'error' => $e->getMessage()
				);
			}
		}

		/**
		 * Method insertProyek
		 * Proses insert data proyek
		 * @param data {array}
		 * @return result {array}
		 */
		private function insertProyek($data){
			// insert proyek
			$query = "INSERT INTO proyek (id, pemilik, tgl, pembangunan, luas_area, alamat, kota, estimasi, total, dp, cco, progress, status) ";
			$query .= "VALUES (:id, :pemilik, :tgl, :pembangunan, :luas_area, :alamat, :kota, :estimasi, :total, :dp, :cco, :progress, :status);";
			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id' => $data['id'],
					':pemilik' => $data['pemilik'],
					':tgl' => $data['tgl'],
					':pembangunan' => $data['pembangunan'],
					':luas_area' => $data['luas_area'],
					':alamat' => $data['alamat'],
					':kota' => $data['kota'],
					':estimasi' => $data['estimasi'],
					':total' => $data['total'],
					':dp' => $data['dp'],
					':cco' => $data['cco'],
					':status' => $data['status'],
					':progress' => $data['progress'],
				)
			);
			$statement->closeCursor();
		}

		/**
		 * Method insertDetail
		 * Proses insert data detail proyek
		 * @param data {array}
		 * @return result {array}
		 */
		private function insertDetail($data){
			$ket = "UANG MASUK SEBESAR RP ".number_format($data['total_detail'], 2, ',', '.')." DARI TRANSAKSI PROYEK (".$data['id_proyek'].") - ".$data['nama_detail'];
			
			// insert detail_proyek
			$query = 'CALL tambah_detail_proyek (:id_proyek, :id_bank, :tgl_detail, :nama_detail, :total_detail, :is_DP, :ket);';

			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id_proyek' => $data['id_proyek'],
					':id_bank' => $data['id_bank'],
					':tgl_detail' => $data['tgl_detail'],
					':nama_detail' => $data['nama_detail'],
					':total_detail' => $data['total_detail'],
					':is_DP' => $data['is_DP'],
					':ket' => $ket
				)
			);
			$statement->closeCursor();
		}

		/**
		 * Method insertSkk
		 * Proses insert data detail skk
		 * @param data {array}
		 * @return result {array}
		 */
		private function insertSkk($data){
			$query = 'INSERT INTO logistik_proyek (id_proyek, id_sub_kas_kecil) VALUES (:id_proyek, :id_sub_kas_kecil);';
			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id_proyek' => $data['id_proyek'],
					':id_sub_kas_kecil' => $data['id_skk'],
				)
			);
			$statement->closeCursor();
		}

		/**
		 * Method update
		 * Proses update data proyek secara menyeluruh
		 * Update proyek, udpate detail proyek, dan udpate detail skk proyek
		 * @param data {array}
		 * @return result {array}
		 */
		public function update($data){
			$dataProyek = $data['dataProyek'];
			$dataDetail = $data['dataDetail'];
			$dataSkk = $data['dataSkk'];

			try{
				$this->koneksi->beginTransaction();

				// update proyek
				$this->updateProyek($dataProyek);

				// update data detail
				foreach($dataDetail as $index => $row){
					array_map('strtoupper', $row);
					// jika diedit
					if(!$dataDetail[$index]['delete'] && $dataDetail[$index]['aksi'] == "edit")
						$this->updateDetail($row);
					// jika ada penambahan
					else if(!$dataDetail[$index]['delete'] && $dataDetail[$index]['aksi'] == "tambah")
						$this->insertDetail($row);
					// jika ada penghapusan
					else if($dataDetail[$index]['delete'] && $dataDetail[$index]['aksi'] == "edit")
						$this->deleteDetail($row['id']);
				}

				// update logistik proyek / skk
				foreach($dataSkk as $index => $row){
					array_map('strtoupper', $row);
					// jika ada penambahan
					if(!$dataSkk[$index]['delete'] && $dataSkk[$index]['aksi'] == "tambah")
						$this->insertSkk($row);
					// jika ada penghapusan
					else if($dataSkk[$index]['delete'] && $dataSkk[$index]['aksi'] == "edit")
						$this->deleteSkk($row['id']);
				}

				$this->koneksi->commit();

				return array(
					'success' => true,
					'error' => NULL
				);
			}
			catch(PDOException $e){
				$this->koneksi->rollback();
				return array(
					'success' => false,
					'error' => $e->getMessage()
				);
			}
		}

		/**
		 * Method updateProyek
		 * Proses update data proyek
		 * @param data {array}
		 * @return result {array}
		 */
		private function updateProyek($data){
			$query = "UPDATE proyek SET pemilik = :pemilik, tgl = :tgl, pembangunan = :pembangunan, luas_area = :luas_area, ";
			$query .= "alamat = :alamat, kota = :kota, estimasi = :estimasi, total = :total, ";
			$query .= "dp = :dp, cco = :cco, progress = :progress, status = :status WHERE id = :id;";

			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id' => $data['id'],
					':pemilik' => $data['pemilik'],
					':tgl' => $data['tgl'],
					':pembangunan' => $data['pembangunan'],
					':luas_area' => $data['luas_area'],
					':alamat' => $data['alamat'],
					':kota' => $data['kota'],
					':estimasi' => $data['estimasi'],
					':total' => $data['total'],
					':dp' => $data['dp'],
					':cco' => $data['cco'],
					':status' => $data['status'],
					':progress' => $data['progress'],
				)
			);
			$statement->closeCursor();
		}

		/**
		 * Method updateDetail
		 * Proses update data detail proyek
		 * @param data {array}
		 * @return result {array}
		 */
		private function updateDetail($data){
			$query = 'CALL edit_detail_proyek (:id, :id_proyek, :id_bank, :tgl_detail, :nama_detail, :total_detail, :is_DP)';
			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id' => $data['id'],
					':id_proyek' => $data['id_proyek'],
					':id_bank' => $data['id_bank'],
					':tgl_detail' => $data['tgl_detail'],
					':nama_detail' => $data['nama_detail'],
					':total_detail' => $data['total_detail'],
					':is_DP' => $data['is_DP']
				)
			);
			$statement->closeCursor();
		}

		/**
		 * Method deleteDetail
		 * Proses hapus data detail proyek
		 * Kegunaan untuk di Method Update
		 * @param id {string}
		 */
		private function deleteDetail($id){
			$tgl = date('Y-m-d');

			$query = 'CALL hapus_detail_proyek (:id, :tgl)';
			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id' => $id,
					':tgl' => $tgl
				)
			);
			$statement->closeCursor();
		}

		/**
		 * Method deleteSkk
		 * Proses hapus data detail skk
		 * Kegunaan untuk di Method Update
		 * @param id {string}
		 */
		private function deleteSkk($id){
			$query = 'DELETE FROM logistik_proyek WHERE id=:id;';
			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id' => $id,
				)
			);
			$statement->closeCursor();
		}

		/**
		 * Method delete
		 * Proses penghapusan data proyek beserta data yang berelasi denganya
		 */
		public function delete($id){
			try{
				$query = 'CALL hapus_proyek (:id);';

				$this->koneksi->beginTransaction();

				$statement = $this->koneksi->prepare($query);
				$statement->execute(
					array(':id' => $id)
				);
				$statement->closeCursor();

				$this->koneksi->commit();

				return array(
					'success' => true,
					'error' => NULL
				);
			}
			catch(PDOException $e){
				$this->koneksi->rollback();
				return array(
					'success' => false,
					'error' => $e->getMessage()
				);
			}
		}

		// ======================== mobile = ======================= //

			/**
			 * Method querySelectBuilder_mobile
			 * 
			 * @param queryKondisi {}
			 * @param kolomCari {}
			 * @param cari {} default NULL
			 * @param page {int} default 1
			 * @return query {string}
			 */
			private function querySelectBuilder_mobile($queryKondisi, $kolomCari, $cari=null, $page=1) {
				$mulai = ($page > 1) ? ($page * 10) - 10 : 0;

				$query = "SELECT * FROM v_proyek_logistik ";

				$i = 0;
				foreach ($kolomCari as $value) {
					if ($cari != null) {
						if ($i === 0)
							$queryKondisi .= " AND (".$value." LIKE '%".$cari."%' ";
						else
							$queryKondisi .= "OR ".$value." LIKE '%".$cari."%' ";
						$i++;
					}
				}

				if ($cari != null)
					$queryKondisi .= ")";

				$query .= "$queryKondisi ";
				$this->queryBeforeLimitMobile = $query;
				$query .= "LIMIT $mulai, 10";
				return $query;
			}

			/**
			 * Method getAllByIdSubKasKecil_mobile
			 * 
			 * @param data {array}
			 * @return result {array}
			 */
			public function getAllByIdSubKasKecil_mobile($data) {
				$id = $data["id_sub_kas_kecil"];
				$cari = $data["cari"];
				$page = $data["page"];

				$queryKondisi = "WHERE id_sub_kas_kecil='".$id."'";
				$kolomCari = array("pemilik","tgl","alamat","kota","status");
				$query = $this->querySelectBuilder_mobile($queryKondisi, $kolomCari, $cari, $page);

				$statement = $this->koneksi->prepare($query);
				$statement->execute();
				return $statement->fetchAll(PDO::FETCH_ASSOC);
			}

			/**
			 * Method getAllStatusBerjalan_mobile
			 * 
			 * @param data {array}
			 * @return result {array}
			 */
			public function getAllStatusBerjalan_mobile($data) {
				$id = $data["id_sub_kas_kecil"];
				$cari = $data["cari"];
				$page = $data["page"];

				$queryKondisi = "WHERE (id_sub_kas_kecil='".$id."' AND status='BERJALAN')";
				$kolomCari = array("pemilik","tgl","alamat","kota");
				$query = $this->querySelectBuilder_mobile($queryKondisi, $kolomCari, $cari, $page);

				$statement = $this->koneksi->prepare($query);
				$statement->execute();
				return $statement->fetchAll(PDO::FETCH_ASSOC);
			}

			/**
			 * Method getRecordFilter_mobile
			 * 
			 * @return result {int}
			 */
			public function getRecordFilter_mobile(){
				$koneksi = $this->openConnection();
				$statement = $koneksi->prepare($this->queryBeforeLimitMobile);
				$statement->execute();

				return $statement->rowCount();
			}

		// ========================================================= //

		/**
		 * Method __destruct
		 * Close connection to DB
		 */
		public function __destruct(){
			$this->closeConnection($this->koneksi);
		}
	}
