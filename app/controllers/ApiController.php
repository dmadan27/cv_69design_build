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
		 * Menampilkan list pengajuan yang belum disetujui berdasarkan sub kas kecil tertentu.
		 * 
		 * @return $output berisi status authentikasi dan list data pengajuan yang belum disetujui.
		 */
		public function pengajuan() {
			$this->model('Pengajuan_sub_kas_kecilModel');

			$input["id_sub_kas_kecil"] = $_POST["id"] ?? false;
			$input["filter"] = $this->helper->getIdStatusPengajuanSKK($_POST["filter"] ?? null);
			$input["cari"] = $_POST["cari"] ?? null;
			$input["page"] = ($_POST["page"] != null) ? $this->validation->validInput($_POST["page"]) : 1;

			$output['status'] = $this->status;

			if ($this->status) {

				$dataPengajuan = $this->Pengajuan_sub_kas_kecilModel->getAllByIdSubKasKecil_mobile($input);
				$totalData = $this->Pengajuan_sub_kas_kecilModel->getRecordFilter_mobile();
				$totalPage = ceil($totalData/10);

				$next = ($input["page"] < $totalPage) ? ($input["page"] + 1) : null;

				foreach ($dataPengajuan as $key => $value) {
					$dataPengajuan[$key]['status'] = $this->helper->getNamaStatusPengajuanSKK($dataPengajuan[$key]['status']);
				}

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

			$output = array();
			$output['status'] = $this->status;
			$output['status_aksi'] = $this->status_aksi;

			$id_pengajuan = ((isset($_POST['id_pengajuan'])) && !empty($_POST['id_pengajuan'])) ? $this->validation->validInput($_POST['id_pengajuan']) : false;
			$id_skk = ((isset($_POST['id'])) && !empty($_POST['id'])) ? $this->validation->validInput($_POST['id']) : false;

			if ($this->status && ($id_pengajuan != false) && ($id_skk != false)) {

				$info_skk = $this->Sub_kas_kecilModel->getByIdFromV($id_skk);

				// pengecekan integritas input
				if ($info_skk['email'] == $_POST['username']) {
					$output['id_pengajuan'] = $this->generate_id_pengajuan($id_pengajuan);
					$output['saldo'] = $info_skk['saldo'];
					$output['sisa_saldo'] = $info_skk['sisa_saldo'];
					$output['status_aksi'] = true;
				}
			}

			echo json_encode($output, JSON_PRETTY_PRINT);
		}

		/**
		*
		*/
		public function action_add_pengajuan(){
			$this->model('Pengajuan_sub_kas_kecilModel');
			$this->model('Sub_kas_kecilModel');

			$output = array();
			$output['status'] = $this->status;
			$output['status_aksi'] = $this->status_aksi;

			$pengajuan = ((isset($_POST["pengajuan"])) && !empty($_POST["pengajuan"])) ? $_POST["pengajuan"] : false;
			$detail_pengajuan = ((isset($_POST["detail_pengajuan"])) && !empty($_POST["detail_pengajuan"])) ? $_POST["detail_pengajuan"] : false;

    		if ($this->status && ($pengajuan != false) && ($detail_pengajuan != false)) {
				$info_skk = $this->Sub_kas_kecilModel->getByIdFromV($_POST['id']);

				// pengecekan integritas input
				if ($info_skk['email'] == $_POST['username']) {

					$data = array(
						'pengajuan' => json_decode($pengajuan),
						'detail_pengajuan' => json_decode($detail_pengajuan)
					);

					// menghitung total pengajuan
					$total_pengajuan = 0.0;
					foreach ($data["detail_pengajuan"] as $key => $value) {
						$total_pengajuan += $value->subtotal;
					}

					// mendapatkan status dan total pengajuan yang sudah dihitung/dicek dengan sisa saldo
					if ($total_pengajuan <= $info_skk["sisa_saldo"]) {
						$data["pengajuan"]->dana_disetujui = 0;
						$data["pengajuan"]->total = $total_pengajuan;
						$data["pengajuan"]->status = "4"; // status : LANGSUNG
					} else {
						$data["pengajuan"]->dana_disetujui = null;
						$data['pengajuan']->total = $total_pengajuan - $info_skk['sisa_saldo'];
						$data['pengajuan']->status = "1"; // status : PENDING
					}
	
					$resultQuery = $this->Pengajuan_sub_kas_kecilModel->insert($data);
	
					if ($resultQuery === true) {
						$output['status_aksi'] = true;
					} else {
						$output['error'] = $resultQuery;
						$output['status_aksi'] = false;
					}

					
				}
			}

			echo json_encode($output, JSON_PRETTY_PRINT);
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

				foreach ($dataDetail as $key => $value) {
					$dataDetail[$key]['jenis'] = $this->helper->getNamaJenisDetailPengajuanSKK($dataDetail[$key]['jenis']);
				}

				$output['detail_pengajuan'] = $dataDetail;
			}

			echo json_encode($output, JSON_PRETTY_PRINT);
		}

		/**
		 * 
		 */
		public function delete_pengajuan() {
			$this->model('Pengajuan_sub_kas_kecilModel');

			$output["status"] = $this->status;
			$output["status_aksi"] = $this->status_aksi;
			$output["error"] = "";

			$id_pengajuan = $_POST['id_pengajuan'] ?? false;
			$id_sub_kas_kecil = $_POST['id'] ?? false;

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

			$input["id_sub_kas_kecil"] = $_POST["id"] ?? false;
			$input["cari"] = $_POST["cari"] ?? null;
			$input["page"] = ($_POST["page"] != null) ? $this->validation->validInput($_POST["page"]) : 1;
			$input["filter"] = $_POST["filter"] ?? null;

			if (strtoupper($input["filter"]) != "BELUM SELESAI") 
				$input["filter"] = $this->helper->getIdStatusLaporanSKK($input["filter"]);

			$output['status'] = $this->status;

			if ($this->status) {

				$dataLaporan = $this->Laporan_pengajuan_sub_kas_kecilModel->getAllByIdSubKasKecil_mobile($input);
				$totalData = $this->Laporan_pengajuan_sub_kas_kecilModel->getRecordFilter_mobile();
				$totalPage = ceil($totalData/10);

				$next = ($input["page"] < $totalPage) ? ($input["page"] + 1) : null;

				foreach ($dataLaporan as $key => $value) {
					$dataLaporan[$key]['status'] = $this->helper->getNamaStatusPengajuanSKK($dataLaporan[$key]['status']);
					$dataLaporan[$key]['status_laporan'] = $this->helper->getNamaStatusLaporanSKK($dataLaporan[$key]['status_laporan']);
				}

				$output['list_laporan'] = $dataLaporan;
				$output['next'] = $next;
			}

			echo json_encode($output, JSON_PRETTY_PRINT);
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

				$info_skk = $this->Sub_kas_kecilModel->getByIdFromV($id_skk);
				
				// mengecek integritas input
				if ($info_skk['email'] == $_POST['username']) {
					
					$output['id_pengajuan'] = $id_pengajuan;
					$output['saldo'] = $info_skk['saldo'];
					$output['sisa_saldo'] = $info_skk['sisa_saldo'];
					$output['pengajuan'] = $this->Pengajuan_sub_kas_kecilModel->getById_mobile(strtoupper($id_pengajuan));
					$output['status_aksi'] = true;	
				}
			}

			echo json_encode($output, JSON_PRETTY_PRINT);
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
						'id_skk' => $_POST['id'],
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
		public function edit_laporan() {
			$this->model('Laporan_pengajuan_sub_kas_kecilModel');
			$this->model('Sub_kas_kecilModel');

			$id_pengajuan = $_POST['id_pengajuan'] ?? null;
			$id_skk = $_POST['id'] ?? null;
			$username = $_POST['username'] ?? null;

			$output = array();
			$output['status'] = $this->status;
			$output['status_aksi'] = $this->status_aksi;

			if ($output['status']) {
				$info_skk = $this->Sub_kas_kecilModel->getByIdFromV($id_skk);

				if ($username == $info_skk['email'] && ($id_pengajuan != null)) {
					$laporan = $this->Laporan_pengajuan_sub_kas_kecilModel->getPerbaikanById($id_pengajuan);
					if ($laporan != null) {
						$output['status_aksi'] = true;
						$output['id_pengajuan'] = $laporan['id'];
						$output['tgl_pengajuan'] = $laporan['tgl'];
						$output['biaya_laporan'] = $laporan['biaya_laporan'];
						$output['jumlah_foto'] = $this->Laporan_pengajuan_sub_kas_kecilModel->getJumlahFotoById($id_pengajuan);
						$output['detail_laporan'] = $this->Laporan_pengajuan_sub_kas_kecilModel->getDetailLaporanById($id_pengajuan);
					}
					
				}
			}

			echo json_encode($output, JSON_PRETTY_PRINT);
		}

		/**
		 * 
		 */
		public function action_edit_laporan() {
			$this->model("Laporan_pengajuan_sub_kas_kecilModel");

			$id_pengajuan = isset($_POST['id_pengajuan']) ?
							$this->validation->validInput($_POST['id_pengajuan'], false) : false;
			$detail_laporan = ((isset($_POST["detail_laporan"])) && !empty($_POST["detail_laporan"])) ?
							json_decode($_POST["detail_laporan"]) : false;
			$id_hapus_foto = $_POST['id_hapus_foto'] ?? null;				
			$foto = isset($_FILES['foto']) ? $this->helper->reArrayFiles($_FILES['foto']) : false;
			$jumlah_foto = isset($_POST['jumlah_foto']) ?
							$this->validation->validInput($_POST['jumlah_foto']) : false;

			$status_valid_foto = $status_upload_foto = false;

			/**
			 * ALUR AKSI DATABASE ACTION UPDATE LAPORAN
			 * 
			 * 	1. Dapatkan selisih biaya laporan = biaya_laporan (lama) - biaya_laporan (baru)
			 * 		a. ambil biaya laporan lama dari v_pengajuan_sub_kas_kecil
			 * 		b. dapatkan biaya laporan baru dari jumlah isi kolom harga_asli $detail laporan
			 * 	2. Update tabel detail_pengajuan_sub_kas_kecil dengan $detail_laporan
			 * 		a. update kolom harga_asli, sisa
			 * 	3. Update tabel pengajuan_sub_kas_kecil
			 * 		a. update kolom status_laporan ke '1' (PENDING)
			 * 	4. Hapus foto pada tabel upload_laporan pengajuan_sub_kas_kecil berdasarkan $id_hapus_foto (JIKA ADA)
			 * 	5. Tambah foto baru kedalam tabel upload_laporan pengajuan_sub_kas_kecil dengan $foto
			 * 	6. Dapatkan saldo_sub_kas_kecil dari tabel sub_kas_kecil
			 * 	7. Tambah mutasi_saldo_sub_kas_kecil
			 * 		a. jika selisih biaya laporan bernilai + maka insert biaya laporan di uang_masuk
			 * 		b. jika selisih biaya laporan bernilai - maka kalikan dengan -1 dan simpan di uang keluar
			 * 		c. beri keterangan 'PENGAJUAN PERBAIKAN LAPORAN [id_pengajuan]'
			 * 		d. tambahkan saldo dengan menambahkan saldo lama (No.5) dengan selisih biaya laporan
			 * 	8. Update saldo sub_kas_kecil dengan menambahkan saldo lama (No.5) dengan selisih biaya laporan
			 * 
			 */
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

			echo json_encode($output, JSON_PRETTY_PRINT);
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
				$dataProfil = $this->Sub_kas_kecilModel->getByIdFromV($id);

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

					$output['profil'] = $dataProfil;
					$output['profil']['foto'] = $foto;
					$output['profil']['token'] = "";
				}
			}
			echo json_encode($output, JSON_PRETTY_PRINT);
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
