<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	 * Class Proyek extend ke Abstract Crud
	 * Extend Abstract CrudAbstract
	 */
	class Proyek extends CrudAbstract{
		
		private $token;
		// private $status = false;

		/** Penambahan beberapa property baru */
		private $success = false;
		private $notif = array();
		private $error = array();
		private $message = NULL;
		/** end penambahan */

		/**
		 * Method __construct
		 * Default load saat pertama kali controller diakses
		 */
		public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
			$this->model('ProyekModel');
			$this->helper();
			$this->validation();
			$this->excel();
		}

		/**
		 * Method index
		 * Render list proyek
		 */
		public function index(){
			$this->list();
		}

		/**
		 * Method list
		 * Proses menampilkan list semua data proyek
		 */
		protected function list(){
			$css = array('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css');
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'app/views/proyek/js/initList.js',
			);

			$config = array(
				'title' => array(
					'main' => 'Data Proyek',
					'sub' => 'List Semua Data Proyek',
				),
				'css' => $css,
				'js' => $js,
			);

			$this->layout('proyek/list', $config, $data = null);
		}

		/**
		 * Method get_list
		 * Proses get data untuk list proyek
		 * Data akan di parsing dalam bentuk dataTable
		 * @return output {object} array berupa json
		 */
		public function get_list(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				// config datatable
				$config_dataTable = array(
					'tabel' => 'proyek',
					'kolomOrder' => array(null, 'id', 'pemilik', 'tgl', 'pembangunan', 'kota', 'total', 'progress', 'status', null),
					'kolomCari' => array('id', 'pemilik', 'tgl', 'pembangunan', 'luas_area', 'kota', 'total', 'status', 'progress'),
					'orderBy' => array('id' => 'desc', 'status' => 'asc'),
					'kondisi' => false,
				);

				$dataProyek = $this->ProyekModel->getAllDataTable($config_dataTable);

				$data = array();
				$no_urut = $_POST['start'];
				foreach($dataProyek as $row){
					$no_urut++;

					$status = (strtolower($row['status']) == "selesai") ? '<span class="label label-success">'.$row['status'].'</span>' : '<span class="label label-primary">'.$row['status'].'</span>';

					if($row['progress'] == 100)
						$progress = '<span class="label label-success">'.$row['progress'].' %</span>';
					else if($row['progress'] >= 50  && $row['progress'] < 100)
						$progress = '<span class="label label-primary">'.$row['progress'].' %</span>';
					else if($row['progress'] >= 20 && $row['progress'] < 50)
						$progress = '<span class="label label-warning">'.$row['progress'].' %</span>';
					else if($row['progress'] < 20)
						$progress = '<span class="label label-danger">'.$row['progress'].' %</span>';

					// button aksi
					$aksiDetail = '<button onclick="getView('."'".strtolower($row["id"])."'".')" type="button" class="btn btn-sm btn-info btn-flat" title="Lihat Detail"><i class="fa fa-eye"></i></button>';
					$aksiEdit = '<button onclick="getEdit('."'".strtolower($row["id"])."'".')" type="button" class="btn btn-sm btn-success btn-flat" title="Edit Data"><i class="fa fa-pencil"></i></button>';
					$aksiHapus = '<button onclick="getDelete('."'".strtolower($row["id"])."'".')" type="button" class="btn btn-sm btn-danger btn-flat" title="Hapus Data"><i class="fa fa-trash"></i></button>';
					
					$aksi = '<div class="btn-group">'.$aksiDetail.$aksiEdit.$aksiHapus.'</div>';
					
					$dataRow = array();
					$dataRow[] = $no_urut;
					$dataRow[] = $row['id'];
					$dataRow[] = $row['pemilik'];
					$dataRow[] = $this->helper->cetakTgl($row['tgl'], 'full');
					$dataRow[] = $row['pembangunan'];
					$dataRow[] = $row['kota'];
					$dataRow[] = $this->helper->cetakRupiah($row['total']);
					$dataRow[] = $progress;
					$dataRow[] = $status;
					$dataRow[] = $aksi;

					$data[] = $dataRow;
				}

				$output = array(
					'draw' => $_POST['draw'],
					'recordsTotal' => $this->ProyekModel->recordTotal(),
					'recordsFiltered' => $this->ProyekModel->recordFilter(),
					'data' => $data,
				);

				echo json_encode($output);
			}
			else { $this->redirect(); }	
		}

		/**
		 * Method form
		 * Proses render form proyek
		 * @param id {string}
		 */
		public function form($id){
			if($id)	{ $this->edit(strtoupper($id)); }
			else { $this->add(); }
		}

		/**
		 * Method add
		 * Proses render form add proyek
		 */
		protected function add(){
			$css = array(
  				'assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
				'assets/bower_components/select2/dist/css/select2.min.css',
				'assets/plugins/bootstrap-slider/slider.css'
  			);
			$js = array(
				'assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
				'assets/bower_components/select2/dist/js/select2.full.min.js',
				'assets/plugins/input-mask/jquery.inputmask.bundle.js',
				'assets/plugins/bootstrap-slider/bootstrap-slider.js',
				'app/views/proyek/js/initForm.js',	
			);

			$config = array(
				'title' => array(
					'main' => 'Data Proyek',
					'sub' => 'Form Tambah Data',
				),
				'css' => $css,
				'js' => $js,
			);

			$data = array(
				'action' => 'action-add', 'id' => '',
				'pemilik' => '', 'tgl' => '', 'pembangunan' => '',
				'luas_area' => '', 'alamat' => '', 'kota' => '',
				'estimasi' => '', 'total' => '', 'dp' => '',
				'cco' => '', 'status' => '', 'progress' => 0,
			);

			$this->layout('proyek/form', $config, $data);
		}

		/**
		 * Method action_add
		 * Proses penambahan data proyek
		 * @return output {object} array berupa json
		 */
		public function action_add(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$data = isset($_POST) ? $_POST : false;
				$dataProyek = isset($_POST['dataProyek']) ? json_decode($_POST['dataProyek'], true) : false;
				$dataDetail = isset($_POST['dataDetail']) ? json_decode($_POST['dataDetail'], true) : false;
				$dataSkk = isset($_POST['dataSkk']) ? json_decode($_POST['dataSkk'], true) : false;
				
				$cekSkk = $cekDetail = true;

				if(!$data){
					$this->notif['default'] = array(
						'type' => 'error',
						'title' => "Pesan Gagal",
						'message' => "Terjadi kesalahan teknis, silahkan coba kembali",
					);
				}
				else{
					// validasi data
					$validasi = $this->set_validation($dataProyek, $data['action']);
					$cek = $validasi['cek'];
					$this->error = $validasi['error'];

					if(!$this->helper->cekArray($dataSkk)){ 
						$cek = false;
						$cekSkk = false;
					}

					if($cek){
						// validasi input
						$data_insertProyek = array(
							'id' => $this->validation->validInput($dataProyek['id']),
							'pemilik' => $this->validation->validInput($dataProyek['pemilik']),
							'tgl' => $this->validation->validInput($dataProyek['tgl']),
							'pembangunan' => $this->validation->validInput($dataProyek['pembangunan']),
							'luas_area' => $this->validation->validInput($dataProyek['luas_area']),
							'alamat' => $this->validation->validInput($dataProyek['alamat']),
							'kota' => $this->validation->validInput($dataProyek['kota']),
							'estimasi' => $this->validation->validInput($dataProyek['estimasi']),
							'total' => $this->validation->validInput($dataProyek['total']),
							'dp' => $this->validation->validInput($dataProyek['dp']),
							'cco' => $this->validation->validInput($dataProyek['cco']),
							'status' => $this->validation->validInput($dataProyek['status']),
							'progress' => $this->validation->validInput($dataProyek['progress'])
						);

						$dataInsert = array(
							'dataProyek' => $data_insertProyek,
							'dataDetail' => $dataDetail,
							'dataSkk' => $dataSkk,
						);

						// insert data proyek
						$insert_proyek = $this->ProyekModel->insert($dataInsert);
						if($insert_proyek['success']){
							$this->success = true;
							$_SESSION['notif'] = array(
								'type' => "success",
								'title' => "Pesan Berhasil",
								'message' => "Tambah Data Proyek Baru Berhasil",
							);
							$this->notif['default'] = $_SESSION['notif'];
						}
						else{
							$this->notif['default'] = array(
								'type' => "error",
								'title' => "Pesan Gagal",
								'message' => "Terjadi kesalahan teknis, silahkan coba kembali",
							);
							$this->message = $insert_proyek['error'];
						}
					}
					else{
						if(!$cekSkk){
							$this->notif['data_skk'] = array(
								'type' => 'warning',
								'title' => "Pesan Pemberitahuan",
								'message' => "Silahkan Cek Kembali Data Pemilihan Sub Kas Kecil Proyek",
							);
						}

						$this->notif['default'] = array(
							'type' => 'warning',
							'title' => "Pesan Pemberitahuan",
							'message' => "Silahkan Cek Kembali Form Isian",
						);
					}
				}

				$output = array(
					'status' => $this->success,
					'notif' => $this->notif,
					'error' => $this->error,
					'message' => $this->message,
					'cek' => array(
						'cek' => $cek,
						'data_skk' => $cekSkk,
						'data_detail' => $cekDetail
					),
					// 'data' => $data,
					'dataProyek' => $dataProyek,
					'dataDetail' => $dataDetail,
					'dataSkk' => $dataSkk,
				);
				
				echo json_encode($output);
			}
			else { $this->redirect(); }
		}

		/**
		 * Method action_add_detail
		 * Proses pengecekan validasi saat penambahan data detail
		 * @return output {object} array berupa json
		 */
		public function action_add_detail(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$data = isset($_POST) ? $_POST : false;

				$validasi = $this->set_validation_detail($data);
				$cek = $validasi['cek'];
				$this->error = $validasi['error'];

				if($cek) { 
					$this->success = true;
					$data['tgl_detail_full'] = $this->helper->cetakTgl($data['tgl_detail'], 'full');
					$data['total_detail_full'] = $this->helper->cetakRupiah($data['total_detail']);
				}

				$output = array(
					'status' => $this->success,
					'error' => $this->error,
					'data' => $data,
				);

				echo json_encode($output);
			}
			else { $this->redirect(); }
				
		}

		/**
		 * Method edit
		 * Proses render form edit proyek
		 * @param id {string}
		 */
		protected function edit($id){
			$id = strtoupper($id);
			// get data proyek
			$dataProyek = !empty($this->ProyekModel->getById($id)) ? $this->ProyekModel->getById($id) : false;

			if((empty($id) || $id == "") || !$dataProyek) { $this->redirect(BASE_URL."proyek/"); }

			$css = array(
  				'assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
				'assets/bower_components/select2/dist/css/select2.min.css',
				'assets/plugins/bootstrap-slider/slider.css',
  			);
			$js = array(
				'assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
				'assets/bower_components/select2/dist/js/select2.full.min.js',
				'assets/plugins/input-mask/jquery.inputmask.bundle.js',
				'assets/plugins/bootstrap-slider/bootstrap-slider.js',
				'app/views/proyek/js/initForm.js',	
			);

			$config = array(
				'title' => array(
					'main' => 'Data Proyek',
					'sub' => 'Form Edit Data',
				),
				'css' => $css,
				'js' => $js,
			);

			$data = array(
				'action' => 'action-edit',
				'id' => $dataProyek['id'], 'pemilik' => $dataProyek['pemilik'], 'tgl' => $dataProyek['tgl'],
				'pembangunan' => $dataProyek['pembangunan'], 'luas_area' => $dataProyek['luas_area'], 'alamat' => $dataProyek['alamat'],
				'kota' => $dataProyek['kota'], 'estimasi' => $dataProyek['estimasi'], 'total' => $dataProyek['total'],
				'dp' => $dataProyek['dp'], 'cco' => $dataProyek['cco'], 'status' => $dataProyek['status'], 'progress' => $dataProyek['progress'],
			);

			$this->layout('proyek/form', $config, $data);
		}

		/**
		 * Method get_edit
		 * Proses get data detail proyek dan detail skk untuk keperluan edit proyek
		 * @param id {string}
		 * @return output {object} array berupa json
		 */
		public function get_edit($id){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$id = strtoupper($id);
				if(empty($id) || $id == "") { $this->redirect(BASE_URL."proyek/"); }

				// get data detail dan skk
				$dataDetail = $this->ProyekModel->getDetailById($id);
				$dataSkk = $this->ProyekModel->getSkkById($id);

				$dataDetail_new = array();
				foreach($dataDetail as $row) {
					$temp = $row;
					$temp['tgl_detail_full'] = $this->helper->cetakTgl($row['tgl_detail'], 'full');
					$temp['total_detail_full'] = $this->helper->cetakRupiah($row['total_detail']);

					$dataDetail_new[] = $temp;
				}

				$output = array(
					'dataDetail' => $dataDetail_new,
					'dataSkk' => $dataSkk,
				);

				echo json_encode($output);
			}
			else { $this->redirect(); }	
		}

		/**
		 * Method action_edit
		 * Proses pengeditan data proyek
		 * @return output {object} array berupa json
		 */
		public function action_edit(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$data = isset($_POST) ? $_POST : false;
				$dataProyek = isset($_POST['dataProyek']) ? json_decode($_POST['dataProyek'], true) : false;
				$dataDetail = isset($_POST['dataDetail']) ? json_decode($_POST['dataDetail'], true) : false;
				$dataSkk = isset($_POST['dataSkk']) ? json_decode($_POST['dataSkk'], true) : false;			
			
				$cekDetail = $cekSkk = true;

				if(!$data){
					$notif = array(
						'title' => "Pesan Gagal",
						'message' => "Terjadi kesalahan teknis, silahkan coba kembali",
					);
				}
				else{
					// validasi data
					$validasi = $this->set_validation($dataProyek, $data['action']);
					$cek = $validasi['cek'];
					$this->error = $validasi['error'];

					if(empty($dataSkk)) {
						$cek = false;
						$cekSkk = false;
					}

					if($cek){
						$data_updateProyek = array(
							'id' => $this->validation->validInput($dataProyek['id']),
							'pemilik' => $this->validation->validInput($dataProyek['pemilik']),
							'tgl' => $this->validation->validInput($dataProyek['tgl']),
							'pembangunan' => $this->validation->validInput($dataProyek['pembangunan']),
							'luas_area' => $this->validation->validInput($dataProyek['luas_area']),
							'alamat' => $this->validation->validInput($dataProyek['alamat']),
							'kota' => $this->validation->validInput($dataProyek['kota']),
							'estimasi' => $this->validation->validInput($dataProyek['estimasi']),
							'total' => $this->validation->validInput($dataProyek['total']),
							'dp' => $this->validation->validInput($dataProyek['dp']),
							'cco' => $this->validation->validInput($dataProyek['cco']),
							'status' => $this->validation->validInput($dataProyek['status']),
							'progress' => $this->validation->validInput($dataProyek['progress'])
						);

						$dataUpdate = array(
							'dataProyek' => $data_updateProyek,
							'dataDetail' => $dataDetail,
							'dataSkk' => $dataSkk,
						);

						// udpate data proyek
						$update_proyek = $this->ProyekModel->update($dataUpdate);
						if($update_proyek['success']){
							$this->success = true;
							$_SESSION['notif'] = array(
								'type' => "success",
								'title' => "Pesan Berhasil",
								'message' => "Edit Data Proyek Berhasil",
							);
							$this->notif['default'] = $_SESSION['notif'];
						}
						else{
							$this->notif['default'] = array(
								'type' => "error",
								'title' => "Pesan Gagal",
								'message' => "Terjadi kesalahan teknis, silahkan coba kembali",
							);
							$this->message = $update_proyek['error'];
						}

					}
					else{
						if(!$cekDetail){
							$this->notif['data_detail'] = array(
								'type' => 'warning',
								'title' => "Pesan Pemberitahuan",
								'message' => "Silahkan Cek Kembali Data Detail",
							);
						}

						if(!$cekSkk){
							$this->notif['data_skk'] = array(
								'type' => 'warning',
								'title' => "Pesan Pemberitahuan",
								'message' => "Silahkan Cek Kembali Data Pemilihan Sub Kas Kecil Proyek",
							);
						}

						$this->notif['default'] = array(
							'type' => "warning",
							'title' => "Pesan Pemberitahuan",
							'message' => "Silahkan Cek Kembali Form Isian ",
						);
					}

				}

				$output = array(
					'status' => $this->success,
					'notif' => $this->notif,
					'error' => $this->error,
					'message' => $this->message,
					'cek' => array(
						'cek' => $cek,
						'data_detail' => $cekDetail,
						'data_skk' => $cekSkk,
					),
					// 'data' => $data,
					'dataProyek' => $dataProyek,
					'dataDetail' => $dataDetail,
					'dataSkk' => $dataSkk,
				);

				echo json_encode($output);
			}
			else { $this->redirect(); }
		}

		/**
		 * Method detail
		 * Proses render detail view proyek
		 * @param id {string}
		 */
		public function detail($id){
			$id = strtoupper($id);
			$dataProyek = !empty($this->ProyekModel->getById($id)) ? $this->ProyekModel->getById($id) : false;

			if((empty($id) || $id == "") || !$dataProyek) { $this->redirect(BASE_URL."proyek/"); }

			$css = array(
				'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css'
			);
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'app/views/proyek/js/initView.js',
			);

			$config = array(
				'title' => array(
					'main' => 'Data Proyek',
					'sub' => 'Detail Data Proyek',
				),
				'css' => $css,
				'js' => $js,
			);

			$parsing_dataProyek = array(
				'id' => $dataProyek['id'],
				'pemilik' => $dataProyek['pemilik'],
				'tgl' => $this->helper->cetakTgl($dataProyek['tgl'], 'full'),
				'pembangunan' => $dataProyek['pembangunan'],
				'luas_area' => $dataProyek['luas_area'],
				'alamat' => $dataProyek['alamat'],
				'kota' => $dataProyek['kota'],
				'estimasi' => $dataProyek['estimasi'].' Bulan',
				'total' => $this->helper->cetakRupiah($dataProyek['total']),
				'dp' => $this->helper->cetakRupiah($dataProyek['dp']),
				'cco' => $this->helper->cetakRupiah($dataProyek['cco']),
				'status' => (strtolower($dataProyek['status']) == "lunas") ? 
					'<span class="label label-success">'.$dataProyek['status'].'</span>' : 
					'<span class="label label-primary">'.$dataProyek['status'].'</span>',
				'progress' => array(
					'style' => 'style="width: '.$dataProyek['progress'].'%"',
					'value' => $dataProyek['progress'],
					'text' => $dataProyek['progress'].'% Success',
				),
			);

			$dataDetail = array();
			foreach($this->ProyekModel->getDetailById($id) as $row){
			 	$dataRow = array();
			 	$dataRow['tgl_detail'] = $this->helper->cetakTgl($row['tgl_detail'], 'full');
			 	$dataRow['nama_detail'] = $row['nama_detail'];
				$dataRow['nama_bank'] = $row['nama_bank'];
				$dataRow['is_DP'] = ($row['is_DP'] == '1') ? 
					'<span class="label label-success">YA</span>' : 
					'<span class="label label-primary">TIDAK</span>';
				$dataRow['total_detail'] = $this->helper->cetakRupiah($row['total_detail']);

				$dataDetail[] = $dataRow;
			}

			$dataSkk = array();
			foreach($this->ProyekModel->getSkkById($id) as $row){
				$dataRow = array();
				$dataRow['id_skk'] = $row['id_skk'];
				$dataRow['nama'] = $row['nama'];

				$dataSkk[] = $dataRow;
			}

			$total_pelaksana_utama = $dataProyek['total'] + $dataProyek['cco'];
			$nilaiTermint_diTerima = $this->ProyekModel->getTermintMasuk($id)['total_termint'];
			$keluaran_tunai = $this->ProyekModel->getKeluaranTunai($id);
			$keluaran_kredit = $this->ProyekModel->getPengeluaran_operasionalProyek($id, 'KREDIT')['total'];
			$saldo_kas_pelaksanaan = $total_pelaksana_utama - ($keluaran_tunai + $keluaran_kredit);
			$selisih = $nilaiTermint_diTerima - $keluaran_tunai;

			$dataArus = array(
				'total_pelaksana_utama' => $this->helper->cetakRupiah($total_pelaksana_utama),
				'nilai_rab' => $this->helper->cetakRupiah($dataProyek['total']),
				'cco' => $this->helper->cetakRupiah($dataProyek['cco']),
				'nilai_terment_diterima' => $this->helper->cetakRupiah($nilaiTermint_diTerima),
				'sisa_terment_project' => $this->helper->cetakRupiah($total_pelaksana_utama - $nilaiTermint_diTerima),
				'nilai_terment_masuk' => $this->helper->cetakRupiah($nilaiTermint_diTerima),
				'total_pelaksana_project' => $this->helper->cetakRupiah($total_pelaksana_utama),
				'keluaran_tunai' => $this->helper->cetakRupiah($keluaran_tunai),
				'keluaran_kredit' => $this->helper->cetakRupiah($keluaran_kredit),
				'saldo_kas_pelaksanaan' => $this->helper->cetakRupiah($saldo_kas_pelaksanaan),
				'selisih' => $this->helper->cetakRupiah($selisih)
			);

			$data = array(
				'data_proyek' => $parsing_dataProyek,
				'data_detail' => $dataDetail,
				'data_skk' => $dataSkk,
				'data_arus' => $dataArus,
			);

			// echo '<pre>';
			// var_dump($data);
			// echo '</pre>';
			// die();

			$this->layout('proyek/view', $config, $data);
		}

		/**
		 * Method get_list_pengajuan_sub_kas_kecil
		 * Proses get data pengajuan sub kas kecil sesuai dengan id proyek
		 * Data akan di parsing dalam bentuk dataTable
		 * @param id {string}
		 * @return result {object} array berupa json
		 */
		public function get_list_pengajuan_sub_kas_kecil($id){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				// config datatable
				$config_dataTable = array(
					'tabel' => 'v_pengajuan_sub_kas_kecil_full',
					'kolomOrder' => array(null, 'id_pengajuan', 'tgl', 'nama_pengajuan', 'nama_skk', 'total', 'dana_disetujui', 'status', null),
					'kolomCari' => array('id_pengajuan', 'tgl', 'nama_pengajuan', 'id_sub_kas_kecil', 'nama_skk', 'total', 'dana_disetujui', 'status'),
					'orderBy' => array('tgl' => 'desc'),
					'kondisi' => 'WHERE id_proyek = "'.$id.'"',
				);

				$this->model('Pengajuan_sub_kas_kecilModel');
				$dataPengajuan = $this->Pengajuan_sub_kas_kecilModel->getAllDataTable($config_dataTable);

				$data = array();
				$no_urut = $_POST['start'];
				foreach($dataPengajuan as $row){
					$no_urut++;

					switch ($row['status']) {
						case "PENDING":
							$status = '<span class="label label-primary">'.$row['status'].'</span>';
							break;
						case "PERBAIKI":
							$status = '<span class="label label-warning">'.$row['status'].'</span>';
							break;
						case "DISETUJUI":
						case "LANGSUNG":
							$status = '<span class="label label-success">'.$row['status'].'</span>';
							break;
						default: // 5
							$status = '<span class="label label-danger">'.$row['status'].'</span>';
					}

					// button aksi
					$aksiDetail = '<button onclick="getView('."'".strtolower($row["id_pengajuan"])."'".')" type="button" ';
					$aksiDetail .= 'class="btn btn-sm btn-info btn-flat" title="Lihat Detail"><i class="fa fa-eye"></i></button>';
					
					$aksi = '<div class="btn-group">'.$aksiDetail.'</div>';
					
					$dataRow = array();
					$dataRow[] = $no_urut;
					$dataRow[] = $row['id_pengajuan'];
					$dataRow[] = $this->helper->cetakTgl($row['tgl'], 'full');
					$dataRow[] = $row['nama_pengajuan'];
					$dataRow[] = $row['id_sub_kas_kecil'].' - '.$row['nama_skk'];
					$dataRow[] = $this->helper->cetakRupiah($row['total']);
					$dataRow[] = $this->helper->cetakRupiah($row['dana_disetujui']);
					$dataRow[] = $status;
					$dataRow[] = $aksi;

					$data[] = $dataRow;
				}

				$output = array(
					'draw' => $_POST['draw'],
					'recordsTotal' => $this->Pengajuan_sub_kas_kecilModel->recordTotal(),
					'recordsFiltered' => $this->Pengajuan_sub_kas_kecilModel->recordFilter(),
					'data' => $data,
				);

				echo json_encode($output);
			}
			else { $this->redirect(); }
		}

		/**
		 * Method get_list_operasional_proyek
		 * Proses get data operasional proyek sesuai dengan id proyek
		 * Data akan di parsing dalam bentuk dataTable
		 * @param id {string}
		 * @return result {object} array berupa json
		 */
		public function get_list_operasional_proyek($proyek){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				// config datatable
				$config_dataTable = array(
					'tabel' => 'v_operasional_proyek',
					'kolomOrder' => array(null, 'id', 'tgl_pengajuan', 'nama_pengajuan', 'nama_kas_besar', 'jenis_pembayaran', 'status_lunas', 'total_pengajuan', null),
					'kolomCari' => array('tgl_pengajuan', 'nama_pengajuan', 'id_kas_besar', 'nama_kas_besar', 'jenis_pembayaran', 'status_lunas', 'total_pengajuan'),
					'orderBy' => array('tgl_pengajuan' => 'desc'),
					'kondisi' => 'WHERE id_proyek = "'.$proyek.'"',
				);

				$this->model('Operasional_proyekModel');
				$dataOperasional = $this->Operasional_proyekModel->getAllDataTable($config_dataTable);

				$data = array();
				$no_urut = $_POST['start'];
				foreach($dataOperasional as $row){
					$no_urut++;

					// button aksi
					$aksiDetail = '<button onclick="getView('."'".strtolower($row["id"])."'".')" type="button" ';
					$aksiDetail .= 'class="btn btn-sm btn-info btn-flat" title="Lihat Detail"><i class="fa fa-eye"></i></button>';
					
					$aksi = '<div class="btn-group">'.$aksiDetail.'</div>';
					
					$jenis_pembayaran = "";
					$status_lunas = "";

					$dataRow = array();
					$dataRow[] = $no_urut;
					$dataRow[] = $row['id'];
					$dataRow[] = $this->helper->cetakTgl($row['tgl_pengajuan'], 'full');
					$dataRow[] = $row['nama_pengajuan'];
					$dataRow[] = $row['id_kas_besar'].' - '.$row['nama_kas_besar'];
					$dataRow[] = $row['jenis_pembayaran'];
					$dataRow[] = $row['status_lunas'];
					$dataRow[] = $this->helper->cetakRupiah($row['total_pengajuan']);
					$dataRow[] = $aksi;

					$data[] = $dataRow;
				}

				$output = array(
					'draw' => $_POST['draw'],
					'recordsTotal' => $this->Operasional_proyekModel->recordTotal(),
					'recordsFiltered' => $this->Operasional_proyekModel->recordFilter(),
					'data' => $data,
				);

				echo json_encode($output);
			}
			else { $this->redirect(); }
		}

		/**
		 * Method delete
		 * Proses hapus data proyek
		 * @param id {string}
		 * @return result 
		 */
		public function delete($id){
			if($_SERVER['REQUEST_METHOD'] == "POST" && $id != ''){
				$id = strtoupper($id);
				if(empty($id) || $id == "") { $this->redirect(BASE_URL."proyek/"); }

				$delete_proyek = $this->ProyekModel->delete($id);
				if($delete_proyek['success']){ 
					$this->success = true;
					$this->notif = array(
						'type' => 'success',
						'title' => 'Pesan Sukses',
						'message' => 'Data Berhasil Dihapus',
					);
				}
				else{
					$this->message = $delete_proyek['error'];
					$this->notif = array(
						'type' => 'error',
						'title' => 'Pesan Error',
						'message' => 'Terjadi Kesalahan Teknis, Silahkan Coba Kembali',
					);
				}

				echo json_encode(array(
					'success' => $this->success,
					'message' => $this->message,
					'notif' => $this->notif
				));
			}
			else { $this->redirect(); }	
		}

		

		/**
		 * Method generate_id
		 * Proses generate id proyek
		 * @return result {object} string berupa json
		 */
		public function generate_id(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$tahun = isset($_POST['get_tahun']) ? $this->validation->validInput($_POST['get_tahun']) : false;

				$id_temp = ($tahun) ? 'PRY'.$tahun : 'PRY'.date('Y');

				$data = !empty($this->ProyekModel->getLastID($id_temp)['id']) ? $this->ProyekModel->getLastID($id_temp)['id'] : false;

				if(!$data) $id = $id_temp.'0001';
				else{
					$noUrut = (int)substr($data, 7, 4);
					$noUrut++;

					$id = $id_temp.sprintf("%04s", $noUrut);
				}
				
				echo json_encode($id);				
			}
			else { $this->redirect(); }	
		}

		/**
		 * Method get_skk
		 * Proses get data skk yang aktif untuk keperluan select
		 * @return data {object} array berupa json
		 */
		public function get_skk(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$data_skk = $this->ProyekModel->get_selectSkk();
				$data = array();

				foreach($data_skk as $row){
					$dataRow = array();
					$dataRow['id'] = $row['id'];
					$dataRow['text'] = $row['id'].' - '.$row['nama'];

					$data[] = $dataRow;
				}

				echo json_encode($data);
			}
			else { $this->redirect(); }
		}

		/**
		 * Method get_bank
		 * Proses get data bank yang aktif untuk keperluan select
		 * @return data {object} array berupa json
		 */
		public function get_bank(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$data_bank = $this->ProyekModel->get_selectBank();
				$data = array();

				foreach($data_bank as $row){
					$dataRow = array();
					$dataRow['id'] = $row['id'];
					$dataRow['text'] = $row['nama'];

					$data[] = $dataRow;
				}

				echo json_encode($data);
			}
			else { $this->redirect(); }
		}

		/**
		 * Method export
		 */
		public function export(){
			
		}

		/**
		 * 
		 */
		public function export_detail($id) {

		}

		/**
		 * Method set_validation
		 * Proses validasi inputan data proyek
		 * @param data {array}
		 * @param action {string}
		 * @return result {array}
		 */
		private function set_validation($data, $action){
			$required = ($action =="action-add") ? 'not_required' : 'required';

			// id
			$this->validation->set_rules($data['id'], 'ID Proyek', 'id', 'string | 1 | 255 | required');
			// pemilik
			$this->validation->set_rules($data['pemilik'], 'Nama Pemilik', 'pemilik', 'string | 1 | 255 | required');
			// tgl
			$this->validation->set_rules($data['tgl'], 'Tanggal Proyek', 'tgl', 'string | 1 | 255 | required');
			// pembangunan
			$this->validation->set_rules($data['pembangunan'], 'Nama Pembangunan', 'pembangunan', 'string | 1 | 255 | required');
			// luas_area
			$this->validation->set_rules($data['luas_area'], 'Luas Area', 'luas_area', 'nilai | 1 | 99999 | required');
			// alamat
			$this->validation->set_rules($data['alamat'], 'Alamat Pembangunan', 'alamat', 'string | 1 | 500 | not_required');
			// kota
			$this->validation->set_rules($data['kota'], 'Kota', 'kota', 'string | 1 | 255 | not_required');
			// estimasi
			$this->validation->set_rules($data['estimasi'], 'Estimasi Pengerjaan', 'estimasi', 'nilai | 1 | 9999 | not_required');
			// total
			$this->validation->set_rules($data['total'], 'Total Dana', 'total', 'nilai | 0 | 999999999999 | required');
			// dp
			$this->validation->set_rules($data['dp'], 'DP Proyek', 'dp', 'nilai | 0 | 999999999999 | required');
			// cco
			$this->validation->set_rules($data['cco'], 'CCO', 'cco', 'nilai | 0 | 999999999999 | not_required');
			// status
			$this->validation->set_rules($data['status'], 'Status Proyek', 'status', 'string | 1 | 255 | required');
			// progress
			$this->validation->set_rules($data['progress'], 'Progress Proyek', 'progress', 'nilai | 0 | 100 | not_required');

			return $this->validation->run();
		}

		/**
		 * Method set_validation_detail
		 * Proses validasi inputan data detail proyek
		 * @param data {array}
		 * @return result {array}
		 */
		private function set_validation_detail($data){
			// pembayaran
			$this->validation->set_rules($data['nama_detail'], 'Pembayaran Proyek', 'nama_detail', 'string | 1 | 255 | required');
			// tgl
			$this->validation->set_rules($data['tgl_detail'], 'Tanggal Pembayaran Proyek', 'tgl_detail', 'string | 1 | 255 | required');
			// id bank
			$this->validation->set_rules($data['id_bank'], 'Bank', 'id_bank', 'string | 1 | 1 | required');
			// total
			$this->validation->set_rules($data['total_detail'], 'Total Angsuran', 'total_detail', 'nilai | 1 | 9999999999 | required');
			

			return $this->validation->run();
		}

	}