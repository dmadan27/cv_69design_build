<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	*
	*/
	class Api extends Controller{

		protected $status = true;
		protected $status_aksi = false;

		/**
		*
		*/
		public function __construct(){
			$this->auth();
			$this->auth->mobileOnly();
			if(!$this->auth->cekAuthMobile())
				$this->status = false;

			$this->validation();
			$this->helper();
		}

		/**
		*
		*/
		public function index() {
			echo json_encode(array(
				'status' => $this->status,
			));
		}

		/**
		*
		*/
		public function pengajuan() {
			$this->model('Pengajuan_sub_kas_kecilModel');

			$input["id_sub_kas_kecil"] = isset($_POST["id"]) ? $_POST["id"] : false;
			$input["cari"] = isset($_POST["cari"]) ? $_POST["cari"] : null;
			$input["page"] = ($_POST["page"] != null) ? $this->validation->validInput($_POST["page"]) : 1;

			$output['status'] = $this->status;

			if ($this->status) {
				$dataPengajuan = $this->Pengajuan_sub_kas_kecilModel->getAllByIdSubKasKecil_mobile($input);
				$totalData = $this->Pengajuan_sub_kas_kecilModel->getRecordFilter_mobile();
				$totalPage = ceil($totalData/10);

				$next = ($input["page"] < $totalPage) ? ($input["page"] + 1) : null;

				$output['list_pengajuan'] = $dataPengajuan;
				$output['next'] = $next;
			}
			echo json_encode($output, JSON_PRETTY_PRINT);
		}

		/**
		*
		*/
		public function add_pengajuan(){
			$this->model('Sub_kas_kecilModel');
			// $this->model('Pengajuan_sub_kas_kecilModel');

			$output = array();
			$output['status'] = $this->status;
			$output['status_aksi'] = $this->status_aksi;

			$id_pengajuan = ((isset($_POST['id_pengajuan'])) && !empty($_POST['id_pengajuan'])) ? $this->validation->validInput($_POST['id_pengajuan']) : false;
			$id_skk = ((isset($_POST['id'])) && !empty($_POST['id'])) ? $this->validation->validInput($_POST['id']) : false;

			if ($this->status && ($id_pengajuan != false) && ($id_skk != false)) {

				$sisa_saldo = $this->Sub_kas_kecilModel->getSisaSaldoById($id_skk)['sisa_saldo'];
				$sisa_saldo = ($sisa_saldo !== NULL) ? $sisa_saldo : $this->Sub_kas_kecilModel->getSaldoById($id_skk)['saldo'];

				$output['id_pengajuan'] = $this->generate_id_pengajuan($id_pengajuan);
				$output['saldo'] = $this->Sub_kas_kecilModel->getSaldoById($id_skk)['saldo'];
				$output['sisa_saldo'] = $sisa_saldo;
				$output['status_aksi'] = true;
			}

			echo json_encode($output);
		}

		/**
		*
		*/
		public function action_add_pengajuan(){
			$this->model('Pengajuan_sub_kas_kecilModel');

			$output = array();
			$output['status'] = $this->status;
			$output['status_aksi'] = $this->status_aksi;

			$pengajuan = ((isset($_POST["pengajuan"])) && !empty($_POST["pengajuan"])) ? $_POST["pengajuan"] : false;
			$detail_pengajuan = ((isset($_POST["detail_pengajuan"])) && !empty($_POST["detail_pengajuan"])) ? $_POST["detail_pengajuan"] : false;

    		if ($this->status && ($pengajuan != false) && ($detail_pengajuan != false)) {

				$data = array(
					'pengajuan' => json_decode($pengajuan),
					'detail_pengajuan' => json_decode($detail_pengajuan)
				);

				$resultQuery = $this->Pengajuan_sub_kas_kecilModel->insert($data);

				if ($resultQuery === true) {
					$output['status_aksi'] = true;
				} else {
					$output['error'] = $resultQuery;
					$output['status_aksi'] = false;
				}
			}

			echo json_encode($output);
		}

		/**
		*
		*/
		public function detail_pengajuan(){
			$this->model('Pengajuan_sub_kas_kecilModel');

			$output = array();
			$output['status'] = $this->status;

			$id_pengajuan = ((isset($_POST['id_pengajuan'])) && !empty($_POST['id_pengajuan'])) ? $this->validation->validInput($_POST['id_pengajuan']) : false;

			if ($this->status && ($id_pengajuan != false)) {
				$dataDetail = $this->Pengajuan_sub_kas_kecilModel->getById_mobile(strtoupper($id_pengajuan));

				$output['detail_pengajuan'] = $dataDetail;
			}

			echo json_encode($output);
		}

		/**
		* BELUM FIX (selalu hasil true walau data tidak ada)
		*/
		public function delete_pengajuan() {
			$this->model('Pengajuan_sub_kas_kecilModel');

			$output["status"] = $this->status;
			$output["status_aksi"] = $this->status_aksi;
			$output["error"] = "";

			$id_pengajuan = ($_POST['id_pengajuan'] != null) ? $_POST['id_pengajuan'] : false;
			$id_sub_kas_kecil = ($_POST['id'] != null) ? $_POST['id'] : false;

			if ($output["status"] && ($id_pengajuan != false) && ($id_sub_kas_kecil != false)) {

				$result = $this->Pengajuan_sub_kas_kecilModel->deletePengajuan($id_pengajuan, $id_sub_kas_kecil);

				if ($result === true) {
					$output["status_aksi"] = true;
				} else {
					$output["status_aksi"] = false;
					$output["error"] = $result;
				}
			}

			echo json_encode($output, JSON_PRETTY_PRINT);
		}

		/**
		*
		*/
		public function laporan() {
			$this->model('Laporan_pengajuan_sub_kas_kecilModel');

			$output = array();
			$output['status'] = $this->status;

			if ($this->status) {
				$page = (isset($_POST['page']) && !empty($_POST['page'])) ? $this->validation->validInput($_POST['page']) : 1;

				$dataLaporan = $this->Laporan_pengajuan_sub_kas_kecilModel->getAll_mobile($page);
				$totalData = $this->Laporan_pengajuan_sub_kas_kecilModel->get_recordTotal_mobile();
				$totalPage = ceil($totalData/10);

				$next = ($page < $totalPage) ? ($page + 1) : null;

				$output['list_laporan'] = $dataLaporan;
				$output['next'] = $next;
			}

			echo json_encode($output);
		}

		/**
		*
		*/
		public function add_laporan(){
			$this->model('Sub_kas_kecilModel');
			$this->model('Pengajuan_sub_kas_kecilModel');

			$output = array();
			$output['status'] = $this->status;
			$output['status_aksi'] = $this->status_aksi;

			$id_pengajuan = ((isset($_POST['id_pengajuan'])) && !empty($_POST['id_pengajuan'])) ? $this->validation->validInput($_POST['id_pengajuan']) : false;
			$id_skk = ((isset($_POST['id'])) && !empty($_POST['id'])) ? $this->validation->validInput($_POST['id']) : false;

			if ($this->status && ($id_pengajuan != false) && ($id_skk != false)) {

				$sisa_saldo = $this->Sub_kas_kecilModel->getSisaSaldoById($id_skk)['sisa_saldo'];
				$sisa_saldo = ($sisa_saldo !== NULL) ? $sisa_saldo : $this->Sub_kas_kecilModel->getSaldoById($id_skk)['saldo'];

				$output['id_pengajuan'] = $id_pengajuan;
				$output['saldo'] = $this->Sub_kas_kecilModel->getSaldoById($id_skk)['saldo'];
				$output['sisa_saldo'] = $sisa_saldo;
				$output['pengajuan'] = $this->Pengajuan_sub_kas_kecilModel->getById_mobile(strtoupper($id_pengajuan));
				$output['status_aksi'] = true;
			}

			echo json_encode($output);
		}

		/**
		*
		*/
		public function action_add_laporan(){
			$this->model('Pengajuan_sub_kas_kecilModel');

			$id_pengajuan = isset($_POST['id_pengajuan']) ?
							$this->validation->validInput($_POST['id_pengajuan'], false) : false;
			$detail_laporan = ((isset($_POST["detail_laporan"])) && !empty($_POST["detail_laporan"])) ?
							json_decode($_POST["detail_laporan"]) : false;
			$foto = isset($_FILES['foto']) ? $this->helper->reArrayFiles($_FILES['foto']) : false;
			$jumlah_foto = isset($_POST['jumlah_foto']) ?
							$this->validation->validInput($_POST['jumlah_foto']) : false;

			$status_valid_foto = $status_upload_foto = false;

    		if ($this->status && $id_pengajuan && $detail_laporan && $foto) {

    			// validasi foto
    			$validasi_foto = $this->validasi_foto($foto, $id_pengajuan);
    			if($validasi_foto['status'] && ($validasi_foto['jumlah'] == $jumlah_foto))
    				$status_valid_foto = true;

    			// upload foto
    			if($status_valid_foto){
    				$upload_foto = $this->validasi_uploadFoto($validasi_foto['foto']);
    				if($upload_foto['status'] && ($upload_foto['jumlah']) == $jumlah_foto)
    					$status_upload_foto = true;
    				else
    					$this->rollbackFoto($upload_foto['foto']);
    			}

    			// simpan db
    			if($status_upload_foto){
    				$data_foto = $upload_foto['foto'];

    				$data = array(
    					'id_pengajuan' => $id_pengajuan,
    					'detail_laporan' => $detail_laporan,
    					'foto' => $data_foto,
    				);

    				if($this->Pengajuan_sub_kas_kecilModel->insert_laporan($data))
    					$this->status_aksi = true;
    				else $this->rollbackFoto($upload_foto['foto']);
    			}
    		}

			$output = array(
				'status' => $this->status,
				'status_valid_foto' => $status_valid_foto,
				'status_upload_foto' => $status_upload_foto,
				'status_aksi' => $this->status_aksi,
				'data' => $data,
			);

			echo json_encode($output);
		}

		/**
		*
		*/
		public function detail_foto_laporan(){
			$this->model('Pengajuan_sub_kas_kecilModel');

			$output = array();
			$output['status'] = $this->status;

			$id_pengajuan = ((isset($_POST['id_pengajuan'])) && !empty($_POST['id_pengajuan'])) ?
				$this->validation->validInput($_POST['id_pengajuan']) : false;

			if ($this->status && $id_pengajuan) {
				$dataFoto = $this->Pengajuan_sub_kas_kecilModel->getFotoById_mobile(strtoupper($id_pengajuan));

				// var_dump($dataFoto);
				$data = array();
				if(!empty($dataFoto)){

					foreach ($dataFoto as $value) {
						$row = array();
						$row['id'] = $value['id'];
						$filename = ROOT.DS.'assets'.DS.'images'.DS.'laporan'.DS.$value['foto'];
						if(!file_exists($filename))
							$row['foto'] = null;
						else
							$row['foto'] = BASE_URL.'assets/images/laporan/'.$value["foto"];

						$data[] = $row;
					}

				}

				$output['foto'] = $data;
			}

			echo json_encode($output);
			// echo "<pre>";
			// echo var_dump($dataFoto);
		}

		/**
		*
		*/
		private function validasi_foto($foto, $id){
			$status = true;
			$tempFoto = array();
			$hitungFoto = 0;

			foreach($foto as $key => $value){
				$configFoto = array(
					'jenis' => 'gambar',
					'error' => $value['error'],
					'size' => $value['size'],
					'name' => $value['name'],
					'tmp_name' => $value['tmp_name'],
					'max' => 2*1048576,
				);
				$validasiFoto = $this->validation->validFile($configFoto);
				if(!$validasiFoto['cek']){
					$status = false;
					$tempFoto[] = array(
						'tmp_name' => $value['tmp_name'],
						'foto' => $value['name'],
						'fotoBaru' => '',
						'error' => $validasiFoto['error'],
					);
					break;
				}
				else{
					$hitungFoto++;
					$fotoBaru = md5($id).$validasiFoto['namaFile'];
					$tempFoto[] = array(
						'tmp_name' => $value['tmp_name'],
						'foto' => $value['name'],
						'fotoBaru' => $fotoBaru,
						'error' => '',
					);
				}
			}

			$output = array(
				'foto' => $tempFoto,
				'jumlah' => $hitungFoto,
				'status' => $status,
			);

			return $output;
		}

		/**
		*
		*/
		private function validasi_uploadFoto($foto){
			$status = true;
			$tempFoto = array();
			$hitungFoto = 0;

			foreach($foto as $key => $value){
				$path = ROOT.DS.'assets'.DS.'images'.DS.'laporan'.DS.$value['fotoBaru'];
				if(!move_uploaded_file($value['tmp_name'], $path)){
					$status = false;
					$tempFoto[] = array(
						'tmp_name' => $value['tmp_name'],
						'foto' => $value['foto'],
						'fotoBaru' => $value['fotoBaru'],
						'error' => 'Upload Foto Gagal',
					);
					break;
				}

				$tempFoto[] = array(
					'tmp_name' => $value['tmp_name'],
					'foto' => $value['foto'],
					'fotoBaru' => $value['fotoBaru'],
					'error' => '',
				);
				$hitungFoto++;
			}

			$output = array(
				'foto' => $tempFoto,
				'jumlah' => $hitungFoto,
				'status' => $status,
			);

			return $output;
		}

		/**
		*
		*/
		private function rollbackFoto($foto){
			$this->status_aksi = false;
		}

		/**
		*
		*/
		public function proyek(){
			$this->model('ProyekModel');

			$input["id_sub_kas_kecil"] = isset($_POST["id"]) ? $_POST["id"] : false;
			$input["cari"] = isset($_POST['cari']) ? $_POST['cari'] : null;
			$input["page"] = ($_POST["page"] != null) ? $this->validation->validInput($_POST['page']) : 1;

			$output['status'] = $this->status;

			if ($this->status) {
				$dataProyek = $this->ProyekModel->getAllStatusBerjalan_mobile($input);
				$totalData = $this->ProyekModel->getRecordFilter_mobile();
				$totalPage = ceil($totalData/10);

				$next = ($input["page"] < $totalPage) ? ($input["page"] + 1) : null;

				$output['list_proyek'] = $dataProyek;
				$output['next'] = $next;
			}
			echo json_encode($output, JSON_PRETTY_PRINT);
		}

		/**
		*
		*/
		public function profil(){
			$this->model('Sub_kas_kecilModel');

			$id = isset($_POST['id']) ? $this->validation->validInput($_POST['id'], false) : false;
			$username = isset($_POST['username']) ? $this->validation->validInput($_POST['username'], false) : false;

			$output = array();
			$output['status'] = $this->status;

			if ($this->status) {
				$dataProfil = $this->Sub_kas_kecilModel->getById($id);

				if(($dataProfil['email'] != $username)) $output['status'] = false;
				else{
					// cek kondisi foto
					if(!empty($dataProfil['foto'])){
						// cek foto di storage
						$filename = ROOT.DS.'assets'.DS.'images'.DS.'user'.DS.$dataProfil['foto'];
						if(!file_exists($filename))
							$foto = null;
						else
							$foto = BASE_URL.'assets/images/user/'.$dataProfil['foto'];
					}
					else $foto = null;

					$output['profil'] = array(
						'id' => $dataProfil['id'],
						'nama' => $dataProfil['nama'],
						'alamat' => $dataProfil['alamat'],
						'no_telp' => $dataProfil['no_telp'],
						'email' => $dataProfil['email'],
						'foto' => $foto,
						'saldo' => $dataProfil['saldo'],
						'status' => $dataProfil['status'],
					);
				}
			}
			echo json_encode($output);
		}


		/**
		*
		*/
		public function edit_profil() {
			$this->model('Sub_kas_kecilModel');

			$id = isset($_POST['id']) ? $this->validation->validInput($_POST['id'], false) : false;
			$alamat = isset($_POST['alamat']) ? $this->validation->validInput($_POST['alamat']) : "";
			$telepon = isset($_POST['telepon'])  ? $this->validation->validInput($_POST['telepon']) : "";

			$output = array();
			$output['status'] = $this->status;

			if ($this->status) {
				$hasil = $this->Sub_kas_kecilModel->updateProfil_mobile($id,$telepon,$alamat);

				if ($hasil === true) {
					$output['status_aksi'] = true;
				}
			}

			echo json_encode($output);
		}

		/**
		*
		*/
		public function edit_foto_profil() {
			$id = isset($_POST['id']) ? $this->validation->validInput($_POST['id'], false) : false;
			$foto = isset($_FILES['foto']) ? $_FILES['foto'] : false;

			$error = $notif = array();
			$status_upload = $status_hapus = false;

			if($this->status){
				$this->model('Sub_kas_kecilModel');
				$fotoLama = (!empty($this->Sub_kas_kecilModel->getById($id)['foto'])
								|| $this->Sub_kas_kecilModel->getById($id)['foto'] != '')
									? ROOT.DS.'assets'.DS.'images'.DS.'user'.DS.$this->Sub_kas_kecilModel->getById($id)['foto'] : false;

				// validasi foto
				if($foto){
					$configFoto = array(
						'jenis' => 'gambar',
						'error' => $foto['error'],
						'size' => $foto['size'],
						'name' => $foto['name'],
						'tmp_name' => $foto['tmp_name'],
						'max' => 2*1048576,
					);
					$validasiFoto = $this->validation->validFile($configFoto);
					if(!$validasiFoto['cek']){
						$cek = false;
						$error['foto'] = $validasiFoto['error'];
					}
					else {
						$cek = true;
						$fotoBaru = md5($id).$validasiFoto['namaFile'];
					}
				}
				else{
					$error['foto'] = 'Anda Belum Memilih Foto';
					$cek = false;
				}

				// cek validasi
				if($cek){
					// upload foto ke server
					$path = ROOT.DS.'assets'.DS.'images'.DS.'user'.DS.$fotoBaru;
					if(!move_uploaded_file($foto['tmp_name'], $path)){
						$error['foto'] = "Upload Foto Gagal";
					}
					else $status_upload = true;

					if($status_upload){
						// update db
						if($this->Sub_kas_kecilModel->updateFoto(array('id' => $id, 'foto' => $fotoBaru))) $status_hapus = true;
						else unlink($path);
					}

					if($status_hapus){
						if($fotoLama && file_exists($fotoLama)) unlink($fotoLama);

						$this->status_aksi = true;
					}
				}
			}

			$output = array(
				'status' => $this->status,
				// 'status_upload' => $status_upload,
				// 'status_hapus' => $status_hapus,
				'status_aksi' => $this->status_aksi,
				'foto' => $foto,
				'error' => $error,
				'notif' => $notif,
			);

			echo json_encode($output);
		}

		/**
		*
		*/
		public function histori() {
			$this->model("ProyekModel");

			$input["id_sub_kas_kecil"] = isset($_POST["id"]) ? $_POST["id"] : false;
			$input["cari"] = isset($_POST["cari"]) ? $_POST["cari"] : null;
			$input["page"] = ($_POST["page"] != null) ? $this->validation->validInput($_POST['page']) : 1;

			$output["status"] = $this->status;

			if ($this->status) {
				$dataHistori = $this->ProyekModel->getAllByIdSubKasKecil_mobile($input);
				$totalHistori = $this->ProyekModel->getRecordFilter_mobile();

				$totalPage = ceil($totalHistori/10);
				$next = ($input["page"] < $totalPage) ? ($input["page"] + 1) : null;

				$output["list_histori"] = $dataHistori;
				$output["next"] = $next;
			}

			echo json_encode($output, JSON_PRETTY_PRINT);
		}

		/**
		*
		*/
		public function mutasi(){
			$this->model('Mutasi_saldo_sub_kas_kecilModel');

			$output = array();
			$output['status'] = $this->status;

			if ($this->status) {
				$page = (isset($_POST['page']) && !empty($_POST['page'])) ? $this->validation->validInput($_POST['page']) : 1;

				$dataMutasi = $this->Mutasi_saldo_sub_kas_kecilModel->getAll_mobile($page);
				$totalData = $this->Mutasi_saldo_sub_kas_kecilModel->get_recordTotal_mobile();
				$totalPage = ceil($totalData/10);

				$next = ($page < $totalPage) ? ($page + 1) : null;

				$output['list_mutasi'] = $dataMutasi;
				$output['next'] = $next;
			}
			echo json_encode($output);
		}

		/**
		*
		*/
		private function generate_id_pengajuan($id_pengajuan) {
			$this->model('Pengajuan_sub_kas_kecilModel');

			$data = !empty($this->Pengajuan_sub_kas_kecilModel->getLastID($id_pengajuan)['id']) ? $this->Pengajuan_sub_kas_kecilModel->getLastID($id_pengajuan)['id'] : false;

			if (!$data) {
				$id = $id_pengajuan.'0001';
			} else {
				$kode = $id_pengajuan;
				$noUrut = (int)substr($data, 25, 4);
				$noUrut++;

				$id = $kode.sprintf("%04s", $noUrut);
			}
			return $id;
		}

		/**
		*
		*/
		public function ganti_password(){
			$this->model('UserModel');
			$this->model('Sub_kas_kecilModel');

			$id = isset($_POST['id']) ? $this->validation->validInput($_POST['id'], false) : false;
			$username = isset($_POST['username']) ? $this->validation->validInput($_POST['username'], false) : false;
			$password_lama = isset($_POST['password_lama']) ? $this->validation->validInput($_POST['password_lama'], false) : false;
			$password_baru = isset($_POST['password_baru']) ? $this->validation->validInput($_POST['password_baru'], false) : false;
			$password_konf = isset($_POST['password_konf']) ? $this->validation->validInput($_POST['password_konf'], false) : false;

			$output = array();
			$output['status'] = $this->status;
			$output['status_aksi'] = $this->status_aksi;

			$output['error'] = $error = '';

			if ($this->status) {
				$validasi = $this->set_validation_ganti_password(
					$data = array(
						'password_lama' => $password_lama,
						'password_baru' => $password_baru,
						'password_konf' => $password_konf
					)
				);

				$cek = $validasi['cek'];
				$error = $validasi['error'];

				$dataProfil = $this->Sub_kas_kecilModel->getById($id);
				$verify_password = $this->UserModel->getById($username)['password'];

				// jika username dan password lama sama
				if(($dataProfil['email'] == $username)
					&& (password_verify($password_lama, $verify_password)) ){

					// cek password baru dan konfirmasi
					if($password_baru !== $password_konf){
						$cek = false;
						$error['password_baru'] = $error['password_konf'] = 'Konfirmasi Password dan Password Baru Tidak Sama !';
					}

					// jika lolos semua validasi
					if($cek){
						$data = array(
							'username' => $username,
							'password' => password_hash($password_baru, PASSWORD_BCRYPT),
						);
						if($this->UserModel->updatePassword($data))
							$output['status_aksi'] = true;
					}

				}
				else $error['password_lama'] = 'Password Lama Anda Salah';

				$output['error'] = $error;

			}
			echo json_encode($output);
		}

		/**
		*
		*/
		private function set_validation_pengajuan($data){
			// id
			$this->validation->set_rules($data['id'], 'ID Pengajuan', 'id', 'string | 1 | 255 | required');
			// id_sub_kas_kecil
			$this->validation->set_rules($data['id_sub_kas_kecil'], 'ID Sub Kas Kecil', 'id_sub_kas_kecil', 'string | 1 | 255 | required');
			// id_proyek
			$this->validation->set_rules($data['id_proyek'], 'ID Proyek', 'id', 'string | 1 | 255 | required');
			// total
			$this->validation->set_rules($data['total'], 'Total', 'total', 'nilai | 1 | 99999999 | required');

			return $this->validation->run();
		}

		/**
		*
		*/
		private function set_validation_pengajuan_detail($data){
			// id_pengajuan
			$this->validation->set_rules($data['id_pengajuan'], 'ID Pengajuan', 'id', 'string | 1 | 255 | required');
			// nama
			$this->validation->set_rules($data['nama'], 'Nama', 'nama', 'string | 1 | 255 | required');
			// jenis
			$this->validation->set_rules($data['jenis'], 'Jenis', 'jenis', 'string | 1 | 255 | required');
			// satuan
			$this->validation->set_rules($data['satuan'], 'Satuan', 'satuan', 'string | 1 | 255 | required');
			// qty
			$this->validation->set_rules($data['qty'], 'Qty', 'qty', 'angka | 1 | 5 | required');
			// harga
			$this->validation->set_rules($data['harga'], 'Total', 'total', 'nilai | 1 | 99999999 | required');
			// subtotal
			$this->validation->set_rules($data['subtotal'], 'Total', 'total', 'nilai | 1 | 99999999 | required');

			return $this->validation->run();
		}

		/**
		*
		*/
		private function set_validation_ganti_password($data){
			// password lama
			$this->validation->set_rules($data['password_lama'], 'Password Lama', 'password_lama', 'string | 5 | 255 | required');
			// password baru
			$this->validation->set_rules($data['password_baru'], 'Password Baru', 'password_baru', 'string | 5 | 255 | required');
			// password konf
			$this->validation->set_rules($data['password_konf'], 'Konfirmasi Password', 'password_konf', 'string | 5 | 255 | required');

			return $this->validation->run();
		}

	}
