<?php
	Defined("BASE_PATH") or die(ACCESS_DENIED);

	/**
	 * 
	 */
	class Sub_kas_kecilModel extends Database implements ModelInterface
	{

		protected $koneksi;

		/**
		 * 
		 */
		public function __construct(){
			$this->koneksi = $this->openConnection();
		}

		/**
		*
		*/
		public function getAll(){
			$query = "SELECT * FROM sub_kas_kecil";

			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll();

			return $result;
		}

		/**
		*
		*/
		public function getById($id){
			$query = "SELECT * FROM sub_kas_kecil WHERE id = :id;";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

		/**
		*
		*/
		public function getSisaSaldoById($id){
			$query = "SELECT skk.saldo, (skk.saldo - SUM(dpskk.subtotal)) sisa_saldo FROM sub_kas_kecil skk  ";
			$query .= "JOIN pengajuan_sub_kas_kecil pskk ON skk.id=pskk.id_sub_kas_kecil ";
			$query .= "JOIN detail_pengajuan_sub_kas_kecil dpskk ON pskk.id=dpskk.id_pengajuan ";
			$query .= "WHERE skk.id = :id AND (pskk.status ='DISETUJUI' OR pskk.status='LANGSUNG') AND pskk.status_laporan IS NULL GROUP BY skk.id";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

		/**
		*
		*/
		public function checkExistEmail($email){
			$query = "SELECT COUNT(*) total FROM sub_kas_kecil WHERE email =:email";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':email', $email);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			if($result['total'] > 0) return false;
			else return true;
		}

		/**
		*
		*/
		public function insert($data){
			try{
				$this->koneksi->beginTransaction();

				$this->insertSubKasKecil($data);

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
		private function insertSubKasKecil($data){
			$level = "SUB KAS KECIL";
			$query = "CALL p_tambah_sub_kas_kecil (:id, :nama, :alamat, :no_telp, :email, :foto, :saldo, :tgl, :status, :password, :level, :created_by);";

			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id' => $data['id'],
					':nama' => $data['nama'],
					':alamat' => $data['alamat'],
					':no_telp' => $data['no_telp'],
					':email' => $data['email'],
					':foto' => $data['foto'],
					':saldo' => $data['saldo'],
					':tgl' => date('Y-m-d'),
					':status' => $data['status'],
					':password' => $data['password'],
					':level' => $level,
					':created_by' => $_SESSION['sess_email']
				)
			);
			$statement->closeCursor();
		}

		/**
		*
		*/
		public function update($data){
			$query = "UPDATE sub_kas_kecil SET nama = :nama, alamat = :alamat, no_telp = :no_telp, email = :email, status = :status, modified_by = :modified_by WHERE id = :id;";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':nama', $data['nama']);
			$statement->bindParam(':alamat', $data['alamat']);
			$statement->bindParam(':no_telp', $data['no_telp']);
			$statement->bindParam(':email', $data['email']);
			$statement->bindParam(':status', $data['status']);
			$statement->bindParam(':id', $data['id']);
			$statement->bindParam(':modified_by', $_SESSION['sess_email']);
			$result = $statement->execute();

			return $result;
		}

		/**
		*
		*/
		public function updateFoto($data){
			$query = "UPDATE sub_kas_kecil SET foto = :foto WHERE id = :id";

			try{
				$this->koneksi->beginTransaction();

				$statement = $this->koneksi->prepare($query);
				$statement->execute(
					array(
						':foto' => $data['foto'],
						':id' => $data['id'],
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
		public function updatePassword($id, $password){
			try {
				$this->koneksi->beginTransaction();

				$query	= "UPDATE sub_kas_kecil SET password = :password WHERE id = :id";

				$statment = $this->koneksi->prepare($query);
				$statment->execute(
					array(
						':id' => $id,
						':password' => $password,
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
		public function delete($id){
			try {
				$query = "CALL p_hapus_sub_kas_kecil (:id)";

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

		/**
		*
		*/
		public function getLastID(){
			$query = "SELECT MAX(id) id FROM sub_kas_kecil;";

			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

		/**
		*
		*/
		public function getUser($username){
			$query = "SELECT * FROM sub_kas_kecil WHERE BINARY email = :username";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':username', $username);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}


		/* ============================== mobile ===================================== */

		/**
		*
		*/
		public function getByIdFromV($id_skk) {
			$query = "SELECT * FROM v_sub_kas_kecil WHERE id=:id_skk;";

			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id_skk' => $id_skk,
				)
			);

			return $statement->fetch(PDO::FETCH_ASSOC);
		}

		public function updateProfil_mobile($id, $telepon, $alamat) {
			$query = "UPDATE sub_kas_kecil SET alamat = :alamat, no_telp = :no_telp WHERE id = :id;";

			try {

				$this->koneksi->beginTransaction();
				$statement = $this->koneksi->prepare($query);
				$statement->execute(
					array(
						':alamat' => $alamat,
						':no_telp' => $telepon,
						':id' => $id,
					)
				);
				$statement->closeCursor();
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
		public function export(){
			$query = "SELECT * FROM v_sub_kas_kecil_export";
			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}

		/**
		 * 
		 */
		public function export_detail_mutasi($id_skk, $tgl_awal, $tgl_akhir) {
			$query = "SELECT `ID SUB KAS KECIL`, TANGGAL, `UANG MASUK`, `UANG KELUAR`, SALDO, KETERANGAN FROM v_mutasi_saldo_sub_kas_kecil_export ";
			$query .= "WHERE `ID SUB KAS KECIL`=:id_skk ";
			$query .= "AND TANGGAL BETWEEN :tgl_awal AND :tgl_akhir ";
			$query .= "ORDER BY `ID` DESC;";

			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id_skk' => $id_skk,
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
		public function export_detail_pengajuan($id_skk, $tgl_awal, $tgl_akhir) {
			$query = "SELECT * FROM v_pengajuan_sub_kas_kecil_export_v2 ";
			$query .= "WHERE `ID SUB KAS KECIL`=:id_skk ";
			$query .= "AND `TANGGAL PENGAJUAN` BETWEEN :tgl_awal AND :tgl_akhir ";
			$query .= "ORDER BY `TANGGAL PENGAJUAN` DESC;";

			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':id_skk' => $id_skk,
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
		public function getByIdExport($id){
			$query = "SELECT * FROM v_sub_kas_kecil_export WHERE id=:id";
			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}

		/**
		*
		*/
		public function countSubKasKecil(){
			$query = "SELECT count(id) FROM sub_kas_kecil";
			$statement = $this->koneksi->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $result;			 
		}

		/**
		*
		*/
		public function __destruct(){
			$this->closeConnection($this->koneksi);
		}
	}
