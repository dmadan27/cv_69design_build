<?php
	// Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	*
	*/
	class Pengajuan_sub_kas_kecilModel extends Database{

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
			$query = "SELECT * FROM pengajuan_sub_kas_kecil";

			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll();

			return $result;
		}

		/**
		*
		*/
		public function getById($id){
			$query = "SELECT * FROM pengajuan_sub_kas_kecil WHERE id = :id;";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

		/**
		*
		*/
		public function getAll_pending(){
			$status = "PENDING";
			$query = "SELECT pskc.id, skc.id id_skc, skc.nama nama_skc, pskc.total FROM pengajuan_sub_kas_kecil pskc ";
			$query .= "JOIN sub_kas_kecil skc ON skc.id = pskc.id_sub_kas_kecil WHERE pskc.status = :status ORDER BY id DESC LIMIT 5";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':status', $status);
			$statement->execute();
			$result = $statement->fetchAll();

			return $result;
		}

		/**
		*
		*/
		public function getTotal_pending(){
			$status = "PENDING";
			$query = "SELECT COUNT(*) FROM pengajuan_sub_kas_kecil WHERE status = :status";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':status', $status);
			$statement->execute();
			$result = $statement->fetchColumn();

			return $result;
		}

		/**
		*
		*/
		public function getLastID($id_pengajuan) {
			// $query = "SELECT MAX(id) as id from pengajuan_sub_kas_kecil WHERE id LIKE :id_pengajuan"."%";
			$id_pengajuan .= "%";
			$query = "SELECT MAX(id) as id from pengajuan_sub_kas_kecil WHERE id LIKE :id_pengajuan";


			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id_pengajuan', $id_pengajuan);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

		/**
		*
		*/
		public function acc_pengajuan($data){
			try {
				$this->koneksi->beginTransaction();

				$query	= "CALL acc_pengajuan_sub_kas_kecil (:id, :id_kas_kecil, ";
				$query .= ":tgl, :dana_disetujui, :status, :ket_kas_kecil, :ket_sub_kas_kecil)";

				$statment = $this->koneksi->prepare($query);
				$statment->execute(
					array(
						':id' => $data['id'],
						':id_kas_kecil' => $data['id_kas_kecil'],
						':tgl' => $data['tgl'],
						':dana_disetujui' => $data['dana_disetujui'],
						':status' => $data['status'],
						':ket_kas_kecil' => $data['ket_kas_kecil'],
						':ket_sub_kas_kecil' => $data['ket_sub_kas_kecil'],
					)
				);
				$statment->closeCursor();

				$this->koneksi->commit();

				return true;
			} catch (PDOException $e) {
				$this->koneksi->rollback();
				return $e->getMessage();
			}
		}

		/**
		*
		*/
		public function update_status($data){

			try {
				$this->koneksi->beginTransaction();

				$query	= "UPDATE pengajuan_sub_kas_kecil SET status = :status WHERE id = :id";

				$statment = $this->koneksi->prepare($query);
				$statment->execute(
					array(
						':id' => $data['id'],
						':status' => $data['status'],
					)
				);
				$statment->closeCursor();

				$this->koneksi->commit();

				return true;
			} catch (PDOException $e) {
				$this->koneksi->rollback();
				return $e->getMessage();
			}
		}

		// ======================== mobile ========================= //

			/**
			*
			*/
			public function setQuery_mobile($page){
				$id = isset($_POST['id']) ? $_POST['id'] : false;
				$cari = isset($_POST['cari']) ? $_POST['cari'] : null;
				$mulai = ($page > 1) ? ($page * 10) - 10 : 0;

				$this->queryMobile = 'SELECT * FROM pengajuan_sub_kas_kecil ';

				$qWhere = 'WHERE id_sub_kas_kecil = "'.$id.'" AND (status = "PENDING" OR status = "PERBAIKI")';
				$i = 0;
				foreach($this->kolomCari_mobile as $value){
					if(!is_null($cari)){
						if($i === 0) $qWhere .= ' AND ('.$value.' LIKE "%'.$cari.'%" ';
						else $qWhere .= 'OR '.$value.' LIKE "%'.$cari.'%"';
					}
					$i++;
				}
				if(!is_null($cari)) $qWhere .= " )";

				$this->queryMobile .= "$qWhere ORDER BY id DESC LIMIT $mulai, 10";
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
			public function getById_mobile($id_pengajuan){
				$id = isset($_POST['id']) ? $_POST['id'] : false;

				$query = "SELECT * FROM v_pengajuan_sub_kas_kecil_full ";
				$query .= "WHERE id_sub_kas_kecil = :id AND id_pengajuan = :id_pengajuan";

				$statement = $this->koneksi->prepare($query);
				$statement->bindParam(':id', $id);
				$statement->bindParam(':id_pengajuan', $id_pengajuan);
				$result = $statement->execute();
				$result = $statement->fetchAll();

				return $result;
			}

			/**
			*
			*/
			public function get_recordTotal_mobile(){
				$koneksi = $this->openConnection();

				$statement = $koneksi->query("SELECT COUNT(*) FROM pengajuan_sub_kas_kecil")->fetchColumn();

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
			public function insert($data) {

				$data_pengajuan = $data["pengajuan"];
				$data_detail_pengajuan = $data["detail_pengajuan"];

				try {
					$this->koneksi->beginTransaction();

					$this->insert_pengajuan($data_pengajuan);

					foreach ($data_detail_pengajuan as $key => $value) {
						$this->insert_detail_pengajuan($value, $data_pengajuan->id);
					}

					$this->koneksi->commit();

					return true;
				} catch (PDOException $e) {
					$this->koneksi->rollback();
					return $e->getMessage();
				}
			}

			/**
			*
			*/
			private function insert_pengajuan($data) {
				$query = "INSERT INTO pengajuan_sub_kas_kecil (id, id_sub_kas_kecil, id_proyek, tgl, total, dana_disetujui, status, status_laporan) VALUES ";
				$query .= "(:id, :id_sub_kas_kecil, :id_proyek, :tgl, :total, :dana_disetujui, :status, :status_laporan);";
				$statment = $this->koneksi->prepare($query);
				$statment->execute(
					array(
						':id' => $data->id,
						':id_sub_kas_kecil' => $data->id_sub_kas_kecil,
						':id_proyek' => $data->id_proyek,
						':tgl' => date('Y-m-d'),
						':total' => $data->total,
						':dana_disetujui' => null,
						':status' => $data->status,
						':status_laporan' => null
					)
				);
				$statment->closeCursor();
			}

			/**
			*
			*/
			private function insert_detail_pengajuan($data, $id_pengajuan) {
				$query	= "INSERT INTO detail_pengajuan_sub_kas_kecil (id, id_pengajuan, nama, jenis, satuan, qty, harga, subtotal, status, harga_asli, sisa, status_lunas) VALUES";
				$query .= "(null, :id_pengajuan, :nama, :jenis, :satuan, :qty, :harga, :subtotal, :status, :harga_asli, :sisa, :status_lunas)";

				$statment = $this->koneksi->prepare($query);
				$statment->execute(
					array(
						':id_pengajuan' => $id_pengajuan,
						':nama' => $data->nama,
						':jenis' => $data->jenis,
						':satuan' => $data->satuan,
						':qty' => $data->qty,
						':harga' => $data->harga,
						':subtotal' => $data->subtotal,
						':status' => null,
						':harga_asli' => null,
						':sisa' => null,
						':status_lunas' => null
					)
				);
				$statment->closeCursor();
			}

			/**
			*
			*/
			public function insert_laporan($data){
				$data_detail_laporan = $data['detail_laporan'];
				$data_foto = $data['foto'];

				try {
					$this->koneksi->beginTransaction();

					// update pengajuan - status laporan
					$this->update_status_laporan($data['id_pengajuan']);

					// update detail pengajuan
					foreach($data_detail_laporan as $key => $value){
						$this->update_detail_laporan($value);
					}

					// insert upload foto laporan
					foreach($data_foto as $key => $value){
						$this->insert_foto_laporan($value, $data['id_pengajuan']);
					}

					$this->koneksi->commit();

					return true;
				} catch (PDOException $e) {
					$this->koneksi->rollback();
					return $e->getMessage();
					// return false;
				}
			}

			/**
			*
			*/
			private function update_status_laporan($id){
				$status_laporan = 'PENDING';
				$query = "UPDATE pengajuan_sub_kas_kecil SET status_laporan = :status_laporan WHERE id = :id";

				$statement = $this->koneksi->prepare($query);
				$statement->execute(array(
						':id' => $id,
						':status_laporan' => $status_laporan,
					)
				);
				$statement->closeCursor();
			}

			/**
			*
			*/
			private function update_detail_laporan($data){
				$status = 'TUNAI';
				$status_lunas = 'LUNAS';

				$query = "UPDATE detail_pengajuan_sub_kas_kecil SET status = :status, harga_asli = :harga_asli, sisa = :sisa, status_lunas = :status_lunas WHERE id = :id";

				$statement = $this->koneksi->prepare($query);
				$statement->execute(array(
						':id' => $data->id,
						':status' => $status, //
						':harga_asli' => $data->harga_asli,
						':sisa' => $data->sisa,
						':status_lunas' => $status_lunas, //
					)
				);
				$statement->closeCursor();
			}

			/**
			*
			*/
			private function insert_foto_laporan($data, $id_pengajuan){
				$query = 'INSERT INTO upload_laporan_pengajuan_sub_kas_kecil (id_pengajuan, foto) VALUES (:id_pengajuan, :foto)';

				$statement = $this->koneksi->prepare($query);
				$statement->execute(array(
						':id_pengajuan' => $id_pengajuan,
						':foto' => $data['fotoBaru'],
					)
				);
				$statement->closeCursor();
			}

		// ========================================================= //

		/**
		*
		*/
		public function __destruct(){
			$this->closeConnection($this->koneksi);
		}
	}
