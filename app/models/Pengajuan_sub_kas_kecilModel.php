<?php
	Defined("BASE_PATH") or die(ACCESS_DENIED);

	/**
	 * 
	 */
	class Pengajuan_sub_kas_kecilModel extends Database{

		private $koneksi;
		private $dataTable;

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
			$query = "SELECT * FROM v_pengajuan_sub_kas_kecil_v2 WHERE id = :id;";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

		/**
		 * 
		 */
		public function getDetailById($id) {
			$query = "SELECT * FROM detail_pengajuan_sub_kas_kecil WHERE id_pengajuan = :id;";
			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);

			return $result;
		}

		/**
		 * 
		 */
		public function getAll_pending(){
			$status = "1";
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
			$status = "1";
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
		 * Method acc_pengajuan
		 * @param data {array}
		 */
		public function acc_pengajuan($data){
			try {
				$this->koneksi->beginTransaction();
				
				$query	= "CALL acc_pengajuan_sub_kas_kecil (:id, :id_kas_kecil, ";
				$query .= ":tgl, :dana_disetujui, :status)";

				$statment = $this->koneksi->prepare($query);
				$statment->execute(
					array(
						':id' => $data['id'],
						':id_kas_kecil' => $data['id_kas_kecil'],
						':tgl' => $data['tgl'],
						':dana_disetujui' => $data['dana_disetujui'],
						':status' => $data['status']
					)
				);
				$statment->closeCursor();

				$this->koneksi->commit();

				return array(
					'success' => true,
					'error' => NULL
				);
			} catch (PDOException $e) {
				$this->koneksi->rollback();
				return array(
					'success' => false,
					'error' => $e->getMessage()
				);
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

				return array(
					'success' => true,
					'error' => NULL
				);
			} catch (PDOException $e) {
				$this->koneksi->rollback();
				return array(
					'success' => false,
					'error' => $e->getMessage()
				);
			}
		}

		// ======================== mobile ========================= //

			/**
			*
			*/
			private function querySelectBuilder_mobile($queryKondisi, $kolomCari, $cari=null, $page=1) {
				$mulai = ($page > 1) ? ($page * 10) - 10 : 0;

				$query = "SELECT * FROM pengajuan_sub_kas_kecil ";

				$i = 0;
				foreach($kolomCari as $value){
					if($cari != null){
						if($i === 0)
							$queryKondisi .= ' AND ('.$value.' LIKE "%'.$cari.'%" ';
						else
							$queryKondisi .= 'OR '.$value.' LIKE "%'.$cari.'%"';
					}
					$i++;
				}

				if($cari != null)
					$queryKondisi .= " )";

				$query .= "$queryKondisi ";
				$this->queryBeforeLimitMobile = $query;
				$query .= "LIMIT $mulai, 10";
				return $query;
			}

			/**
			*
			*/
			public function getAllByIdSubKasKecil_mobile($data){
				$id = $data["id_sub_kas_kecil"];
				$cari = $data["cari"];
				$page = $data["page"];

				$filter = ($data["filter"] != null) ? "AND status='".$data['filter']."'" : "";

				$queryKondisi = "WHERE id_sub_kas_kecil='".$id."' AND (status='1' OR status='2') ".$filter;
				$kolomCari = array("id","id_proyek","nama","tgl");
				$query = $this->querySelectBuilder_mobile($queryKondisi, $kolomCari, $cari, $page);

				$statement = $this->koneksi->prepare($query);
				$statement->execute();
				return $statement->fetchAll(PDO::FETCH_ASSOC);
				// return $query;
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
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);

				return $result;
			}

			/**
			*
			*/
			public function getFotoById_mobile($id_pengajuan){
				$id = isset($_POST['id']) ? $_POST['id'] : false;

				$query = "SELECT * FROM upload_laporan_pengajuan_sub_kas_kecil ";
				$query .= "WHERE id_pengajuan = :id";

				$statement = $this->koneksi->prepare($query);
				$statement->bindParam(':id', $id_pengajuan);
				$result = $statement->execute();
				$result = $statement->fetchAll();

				return $result;
			}

			/**
			*
			*/
			public function getRecordFilter_mobile(){
				$koneksi = $this->openConnection();
				$statement = $koneksi->prepare($this->queryBeforeLimitMobile);
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
				$query = "INSERT INTO pengajuan_sub_kas_kecil (id, id_sub_kas_kecil, id_proyek, tgl, nama, total, dana_disetujui, status, status_laporan) VALUES ";
				$query .= "(:id, :id_sub_kas_kecil, :id_proyek, :tgl, :nama, :total, :dana_disetujui, :status, :status_laporan);";
				$statment = $this->koneksi->prepare($query);
				$statment->execute(
					array(
						':id' => $data->id,
						':id_sub_kas_kecil' => $data->id_sub_kas_kecil,
						':id_proyek' => $data->id_proyek,
						':nama' => $data->nama,
						':tgl' => date('Y-m-d'),
						':total' => $data->total,
						':dana_disetujui' => $data->dana_disetujui,
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
				// $query	= "INSERT INTO detail_pengajuan_sub_kas_kecil (id, id_pengajuan, nama, jenis, satuan, qty, harga, subtotal, status, harga_asli, sisa, status_lunas) VALUES";
				// $query .= "(null, :id_pengajuan, :nama, :jenis, :satuan, :qty, :harga, :subtotal, :status, :harga_asli, :sisa, :status_lunas)";

				$query	= "INSERT INTO detail_pengajuan_sub_kas_kecil (id, id_pengajuan, nama, jenis, satuan, qty, harga, subtotal, harga_asli, sisa) VALUES";
				$query .= "(null, :id_pengajuan, :nama, :jenis, :satuan, :qty, :harga, :subtotal, :harga_asli, :sisa)";

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
						// ':status' => null,
						':harga_asli' => null,
						':sisa' => null,
						// ':status_lunas' => null
					)
				);
				$statment->closeCursor();
			}
			
			/**
			 * Edit Pengajuan Sub Kas Kecil.
			 * 
			 * @param data Array yang berisi data pengajuan, detail_pengajuan, dan detail hapus.
			 * @return string/boolean Jika berhasil dieksekusi akan mengembalikan nilai boolean true, 
			 * 						   jika gagal akan mengembalikan string pesan error.
			 */
			public function edit_pengajuan($data) {
				$pengajuan = $data['pengajuan'];
				$detail = $data['detail_pengajuan'];
				$hapus = $data['detail_hapus'];

				try {

					$this->koneksi->beginTransaction();

					// hapus detail
					if ($hapus != null) {
						foreach ($hapus as $key => $id) {
							$query = "DELETE FROM detail_pengajuan_sub_kas_kecil WHERE id=:id";
							$statement = $this->koneksi->prepare($query);
							$statement->execute(array(
								':id' => strval($id)
							));
							$statement->closeCursor();
						}
					}			

					// tambah/edit detail pengajuan
					foreach ($detail as $key => $value) {
						// update data
						if ($value->no != null) {
							$query = "UPDATE detail_pengajuan_sub_kas_kecil SET ";
							$query .= "nama=:nama, jenis=:jenis, satuan=:satuan, qty=:qty, harga=:harga, subtotal=:subtotal ";
							$query .= "WHERE id=:id ";
							$statement = $this->koneksi->prepare($query);
							$statement->execute(array(
								':nama' => $value->nama,
								':jenis' => $value->jenis,
								':satuan' => $value->satuan,
								':qty' => $value->qty,
								':harga' => $value->harga,
								':subtotal' => $value->subtotal,
								':id' => $value->no,
							));
							$statement->closeCursor();
						// tambah data	
						} else {
							$this->insert_detail_pengajuan($value, $pengajuan->id);
						}
					}

					// edit pengajuan
					$query = "UPDATE pengajuan_sub_kas_kecil SET ";
					$query .= "tgl=:tgl, nama=:nama, total=:total, dana_disetujui=:dana_disetujui, status=:status ";
					$query .= "WHERE id=:id"; 
					$statement = $this->koneksi->prepare($query);
					$statement->execute(array(
						':tgl' => date('Y-m-d'),
						':nama' => $pengajuan->nama,
						':total' => $pengajuan->total,
						':dana_disetujui' => $pengajuan->dana_disetujui,
						':status' => $pengajuan->status,
						':id' => $pengajuan->id,
					));
					$statement->closeCursor();

					$this->koneksi->commit();
					return true;
				} catch(PDOException $e) {
					$this->koneksi->rollback();
					return $e->getMessage();
				}
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
					// $this->update_status_laporan($data['id_pengajuan']);

					// update detail pengajuan
					$total = 0;
					foreach($data_detail_laporan as $key => $value){
						$this->update_detail_laporan($value);
						$total += $value->harga_asli;
					}

					// insert upload foto laporan
					foreach($data_foto as $key => $value){
						$this->insert_foto_laporan($value, $data['id_pengajuan']);
					}

					// 
					$query = "CALL pengajuan_laporan_sub_kas_kecil(:id_pengajuan,:id_skk,:tgl,:total,:ket)";
					$statement = $this->koneksi->prepare($query);
					$statement->execute(
						array(
							':id_pengajuan' => $data['id_pengajuan'],
							':id_skk' => $data['id_skk'],
							':tgl' =>  date('Y-m-d'),
							':total' => $total,
							':ket' => "PENGAJUAN LAPORAN ".$data['id_pengajuan'],
						)
					);
					$statement->closeCursor();

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
				$status_laporan = '1';
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
				// $status = 'TUNAI';
				// $status_lunas = 'LUNAS';

				// $query = "UPDATE detail_pengajuan_sub_kas_kecil SET status = :status, harga_asli = :harga_asli, sisa = :sisa, status_lunas = :status_lunas WHERE id = :id";
				$query = "UPDATE detail_pengajuan_sub_kas_kecil SET harga_asli = :harga_asli, sisa = :sisa WHERE id = :id";


				$statement = $this->koneksi->prepare($query);
				$statement->execute(array(
						':id' => $data->id_detail,
						// ':status' => $status, //
						':harga_asli' => $data->harga_asli,
						':sisa' => $data->sisa,
						// ':status_lunas' => $status_lunas, //
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

			/**
			*
			*/
			public function deletePengajuan($id_pengajuan, $id_sub_kas_kecil) {

				try {
					$this->koneksi->beginTransaction();

					// hapus detail pengajuan
					$query = "DELETE dpskk FROM detail_pengajuan_sub_kas_kecil dpskk JOIN pengajuan_sub_kas_kecil pskk ON dpskk.id_pengajuan=pskk.id ";
					$query .= "WHERE dpskk.id_pengajuan=:id_pengajuan AND pskk.id_sub_kas_kecil=:id_sub_kas_kecil AND pskk.status='1'";
					$statement = $this->koneksi->prepare($query);
					$statement->execute(array(
							":id_pengajuan" => $id_pengajuan,
							":id_sub_kas_kecil" => $id_sub_kas_kecil
						)
					);
					$statement->closeCursor();

					// hapus pengajuan
					$query = "DELETE FROM pengajuan_sub_kas_kecil WHERE id=:id_pengajuan AND id_sub_kas_kecil=:id_sub_kas_kecil AND status='1'";
					$statement = $this->koneksi->prepare($query);
					$statement->execute(array(
							":id_pengajuan" => $id_pengajuan,
							":id_sub_kas_kecil" => $id_sub_kas_kecil
						)
					);
					$statement->closeCursor();

					$this->koneksi->commit();

					return true;
				} catch (Exception $e) {
					$this->koneksi->rollback();
					return $e->getMessage();
				}

			}

		// ========================================================= //

		/**
		*
		*/
		public function __destruct(){
			$this->closeConnection($this->koneksi);
		}
	}
