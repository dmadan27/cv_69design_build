<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	* Class Proyek extend ke Abstract Crud
	*/
	class Proyek extends CrudAbstract{
		
		private $token;
		private $status = false;

		/**
		* Default load saat pertama kali controller di akses
		*/
		public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
			$this->model('ProyekModel');
			$this->helper();
			$this->validation();
		}

		/**
		* Method pertama kali yang di akses
		*/
		public function index(){
			$this->list();
		}

		/**
		* Method List
		* Menampilkan list semua data proyek
		* Passing data css dan js yang dibutuhkan di list proyek
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
		* Method get list
		* Get data semua list proyek yang akan di passing ke dataTable
		* Request berupa POST dan output berupa JSON
		*/
		public function get_list(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				// config datatable
				$config_dataTable = array(
					'tabel' => 'proyek',
					'kolomOrder' => array(null, 'id', 'pemilik', 'tgl', 'pembangunan', 'kota', 'total', 'progress', 'status', null),
					'kolomCari' => array('id', 'pemilik', 'tgl', 'pembangunan', 'luas_area', 'status', 'progress'),
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
			else $this->redirect();	
		}

		/**
		* Method Form
		* Menampilkan form tambah atau edit
		* Parameter id sebagai pembeda form tambah dengan form edit
		*/
		public function form($id){
			if($id)	$this->edit(strtoupper($id));
			else $this->add();
		}

		/**
		* Method add
		* Menampilkan form tambah
		* Set value field secara default
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
				'action' => 'action-add',
				'id' => '',
				'pemilik' => '',
				'tgl' => '',
				'pembangunan' => '',
				'luas_area' => '',
				'alamat' => '',
				'kota' => '',
				'estimasi' => '',
				'total' => '',
				'dp' => '',
				'cco' => '',
				'status' => '',
				'progress' => 0,
			);

			$this->layout('proyek/form', $config, $data);
		}

		/**
		* Method action add
		* Get data dari client yang akan diolah dan disimpan ke db
		* Request berupa POST dan output berupa JSON
		*/
		public function action_add(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$data = isset($_POST) ? $_POST : false;
				$dataProyek = isset($_POST['dataProyek']) ? json_decode($_POST['dataProyek'], true) : false;
				$dataDetail = isset($_POST['dataDetail']) ? json_decode($_POST['dataDetail'], true) : false;
				$dataSkk = isset($_POST['dataSkk']) ? json_decode($_POST['dataSkk'], true) : false;
				
				$error = $notif = array();
				$cekDetail = $cekSkk = true;

				if(!$data){
					$notif['default'] = array(
						'type' => 'error',
						'title' => "Pesan Gagal",
						'message' => "Terjadi kesalahan teknis, silahkan coba kembali",
					);
				}
				else{
					// validasi data
					$validasi = $this->set_validation($dataProyek, $data['action']);
					$cek = $validasi['cek'];
					$error = $validasi['error'];

					if(empty($dataDetail)){
						$cek = false;
						$cekDetail = false;
					}
					if(empty($dataSkk)) {
						$cek = false;
						$cekSkk = false;
					}

					if($cek){
						// validasi input
						$dataProyek = array(
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
							'dataProyek' => $dataProyek,
							'dataDetail' => $dataDetail,
							'dataSkk' => $dataSkk,
						);

						// insert data proyek
						if($this->ProyekModel->insert($dataInsert)){
							$this->status = true;
							$_SESSION['notif'] = array(
								'type' => "success",
								'title' => "Pesan Berhasil",
								'message' => "Tambah Data Proyek Baru Berhasil",
							);
							$notif['default'] = $_SESSION['notif'];
						}
						else{
							$notif['default'] = array(
								'type' => "error",
								'title' => "Pesan Gagal",
								'message' => "Terjadi kesalahan teknis, silahkan coba kembali",
							);
						}
					}
					else{
						if(!$cekDetail){
							$notif['data_detail'] = array(
								'type' => 'warning',
								'title' => "Pesan Pemberitahuan",
								'message' => "Silahkan Cek Kembali Data Detail",
							);
						}

						if(!$cekSkk){
							$notif['data_skk'] = array(
								'type' => 'warning',
								'title' => "Pesan Pemberitahuan",
								'message' => "Silahkan Cek Kembali Data Logistik Proyek",
							);
						}

						$notif['default'] = array(
							'type' => 'warning',
							'title' => "Pesan Pemberitahuan",
							'message' => "Silahkan Cek Kembali Form Isian",
						);
					}
				}

				$output = array(
					'status' => $this->status,
					'notif' => $notif,
					'error' => $error,
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
			else $this->redirect();
				
		}

		/**
		* Method edit
		* Menampilkan form edit yang fieldnya sudah terisi sesuai dengan id
		* Parameter id => id proyek
		*/
		protected function edit($id){
			$id = strtoupper($id);
			// get data proyek
			$dataProyek = !empty($this->ProyekModel->getById($id)) ? $this->ProyekModel->getById($id) : false;

			if((empty($id) || $id == "") || !$dataProyek) $this->redirect(BASE_URL."proyek/");

			// if(!$dataProyek) $this->redirect(BASE_URL."proyek/");

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
				'id' => $dataProyek['id'],
				'pemilik' => $dataProyek['pemilik'],
				'tgl' => $dataProyek['tgl'],
				'pembangunan' => $dataProyek['pembangunan'],
				'luas_area' => $dataProyek['luas_area'],
				'alamat' => $dataProyek['alamat'],
				'kota' => $dataProyek['kota'],
				'estimasi' => $dataProyek['estimasi'],
				'total' => $dataProyek['total'],
				'dp' => $dataProyek['dp'],
				'cco' => $dataProyek['cco'],
				'status' => $dataProyek['status'],
				'progress' => $dataProyek['progress'],
			);

			$this->layout('proyek/form', $config, $data);
		}

		/**
		* Method get edit
		* Get data detail proyek dan detail skk
		* Request berupa POST dan output berupa JSON
		* Parameter id => id proyek
		*/
		public function get_edit($id){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$id = strtoupper($id);
				if(empty($id) || $id == "") $this->redirect(BASE_URL."proyek/");

				// get data detail dan skk
				$dataDetail = $this->ProyekModel->getDetailById($id);
				$dataSkk = $this->ProyekModel->getSkkById($id);

				$output = array(
					'dataDetail' => $dataDetail,
					'dataSkk' => $dataSkk,
				);

				echo json_encode($output);
			}
			else $this->redirect();	
		}

		/**
		* Method action edit
		* Get data dari client yang akan diolah dan disimpan ke db
		* Request berupa POST dan output berupa JSON
		* 
		*/
		public function action_edit(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$data = isset($_POST) ? $_POST : false;
				$dataProyek = isset($_POST['dataProyek']) ? json_decode($_POST['dataProyek'], true) : false;
				$dataDetail = isset($_POST['dataDetail']) ? json_decode($_POST['dataDetail'], true) : false;
				$dataSkk = isset($_POST['dataSkk']) ? json_decode($_POST['dataSkk'], true) : false;			
				
				$error = $notif = array();
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
					$error = $validasi['error'];

					if(empty($dataDetail)){
						$cek = false;
						$cekDetail = false;
					}

					if(empty($dataSkk)) {
						$cek = false;
						$cekSkk = false;
					}

					if($cek){
						$dataProyek = array(
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
							'dataProyek' => $dataProyek,
							'dataDetail' => $dataDetail,
							'dataSkk' => $dataSkk,
						);

						// insert data proyek
						if($this->ProyekModel->update($dataUpdate)){
							$this->status = true;
							$_SESSION['notif'] = array(
								'type' => "success",
								'title' => "Pesan Berhasil",
								'message' => "Edit Data Proyek Berhasil",
							);
							$notif['default'] = $_SESSION['notif'];
						}
						else{
							$notif['default'] = array(
								'type' => "error",
								'title' => "Pesan Gagal",
								'message' => "Terjadi kesalahan teknis, silahkan coba kembali",
							);
						}

					}
					else{
						if(!$cekDetail){
							$notif['data_detail'] = array(
								'type' => 'warning',
								'title' => "Pesan Pemberitahuan",
								'message' => "Silahkan Cek Kembali Data Detail",
							);
						}

						if(!$cekSkk){
							$notif['data_skk'] = array(
								'type' => 'warning',
								'title' => "Pesan Pemberitahuan",
								'message' => "Silahkan Cek Kembali Data Logistik Proyek",
							);
						}

						$notif['default'] = array(
							'type' => "warning",
							'title' => "Pesan Pemberitahuan",
							'message' => "Silahkan Cek Kembali Form Isian ",
						);
					}

				}

				$output = array(
					'status' => $this->status,
					'notif' => $notif,
					'error' => $error,
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
			else $this->redirect();
		}

		/**
		* Method detail
		* Menampilkan detail data proyek sesuai dengan id proyek yang dipilih
		* Paramtere id => id proyek
		*/
		public function detail($id){
			$id = strtoupper($id);
			$dataProyek = !empty($this->ProyekModel->getById($id)) ? $this->ProyekModel->getById($id) : false;

			if((empty($id) || $id == "") || !$dataProyek) $this->redirect(BASE_URL."proyek/");

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

			$total = $dataProyek['total'];
			$dp = $dataProyek['dp'];
			$cco = $dataProyek['cco'];

			$dataProyek = array(
				'id' => $dataProyek['id'],
				'pemilik' => $dataProyek['pemilik'],
				'tgl' => $this->helper->cetakTgl($dataProyek['tgl'], 'full'),
				'pembangunan' => $dataProyek['pembangunan'],
				'luas_area' => $dataProyek['luas_area'],
				'alamat' => $dataProyek['alamat'],
				'kota' => $dataProyek['kota'],
				'estimasi' => $dataProyek['estimasi'].' Bulan',
				'total' => $this->helper->cetakRupiah($total),
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
			 	$dataRow['angsuran'] = $row['angsuran'];
			 	$dataRow['persentase'] = $row['persentase'].' %';
				$dataRow['total'] = $this->helper->cetakRupiah($row['total_detail']);
				$dataRow['status'] = (strtolower($row['status_detail']) == "selesai") ? 
					'<span class="label label-success">'.$row['status_detail'].'</span>' : 
					'<span class="label label-primary">'.$row['status_detail'].'</span>';
				$dataRow[] = '<button onclick="getEditDetail('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-success btn-flat" title="Edit Detail Proyek"><i class="fa fa-pencil"></i></button>';

				$dataDetail[] = $dataRow;
			}

			$dataSkk = array();
			foreach($this->ProyekModel->getSkkById($id) as $row){
				$dataRow = array();
				// $dataRow['id'] = $row['id'];
				$dataRow['id_skk'] = $row['id_skk'];
				$dataRow['nama'] = $row['nama'];

				$dataSkk[] = $dataRow;
			}

			$total_pelaksana_utama = $total + $cco;
			$dataArus = array(
				'total_pelaksana_utama' => $this->helper->cetakRupiah($total_pelaksana_utama),
				'nilai_rab' => $dataProyek['total'],
				'cco' => $dataProyek['cco'],
				'nilai_terment_diterima' => $this->helper->cetakRupiah(0),
				'sisa_terment_project' => $this->helper->cetakRupiah(0),
				'nilai_terment_masuk' => $this->helper->cetakRupiah(0),
				'total_pelaksana_project' => $this->helper->cetakRupiah(0),
				'keluaran_tunai' => $this->helper->cetakRupiah(0),
				'keluaran_kredit' => $this->helper->cetakRupiah(0),
				'saldo_kas_pelaksanaan' => $this->helper->cetakRupiah(0),
				'selisih' => $this->helper->cetakRupiah(0)
			);

			$data = array(
				'data_proyek' => $dataProyek,
				'data_detail' => $dataDetail,
				'data_skk' => $dataSkk,
				'data_arus' => $dataArus,
			);

			$this->layout('proyek/view', $config, $data);
		}

		/**
		* Method get list pengajuan
		* Get data semua list pengajuan sub kas kecil sesuai dengan proyek yang dipilih
		* Data akan dipassing ke dataTable
		* Request berupa POST dan output berupa JSON
		* Parameter proyek => id proyek
		*/
		public function get_list_pengajuan($proyek){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				// config datatable
				$config_dataTable = array(
					'tabel' => 'pengajuan_sub_kas_kecil',
					'kolomOrder' => array(null, 'id', 'nama', 'id_sub_kas_kecil', 'tgl', null),
					'kolomCari' => array('id', 'nama', 'id_sub_kas_kecil', 'tgl', 'total'),
					'orderBy' => array('tgl' => 'desc'),
					'kondisi' => 'WHERE id_proyek = "'.$proyek.'"',
				);

				$this->model('Pengajuan_sub_kas_kecilModel');
				$dataPengajuan = $this->Pengajuan_sub_kas_kecilModel->getAllDataTable($config_dataTable);

				$data = array();
				$no_urut = $_POST['start'];
				foreach($dataPengajuan as $row){
					$no_urut++;

					// button aksi
					$aksiDetail = '<button onclick="getView('."'".strtolower($row["id"])."'".')" type="button" ';
					$aksiDetail .= 'class="btn btn-sm btn-info btn-flat" title="Lihat Detail"><i class="fa fa-eye"></i></button>';
					
					$aksi = '<div class="btn-group">'.$aksiDetail.'</div>';
					
					$dataRow = array();
					$dataRow[] = $no_urut;
					$dataRow[] = $row['id'];
					$dataRow[] = $row['nama'];
					$dataRow[] = $row['id_sub_kas_kecil'];
					$dataRow[] = $this->helper->cetakTgl($row['tgl'], 'full');
					$dataRow[] = $this->helper->cetakRupiah($row['total']);
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
			else $this->redirect();
		}

		/**
		* Method get list operasional
		* Get data semua list operasional proyek kas besar sesuai dengan proyek yang dipilih
		* Data akan dipassing ke dataTable
		* Request berupa POST dan output berupa JSON
		* Parameter proyek => id proyek
		*/
		public function get_list_operasional($proyek){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				// config datatable
				$config_dataTable = array(
					'tabel' => 'operasional_proyek',
					'kolomOrder' => array(null, 'id', 'nama', 'id_kas_besar', 'tgl', null),
					'kolomCari' => array('id', 'nama', 'id_kas_besar', 'tgl', 'total'),
					'orderBy' => array('tgl' => 'desc'),
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
					
					$dataRow = array();
					$dataRow[] = $no_urut;
					$dataRow[] = $row['id'];
					$dataRow[] = $row['nama'];
					$dataRow[] = $row['id_kas_besar'];
					$dataRow[] = $this->helper->cetakTgl($row['tgl'], 'full');
					$dataRow[] = $this->helper->cetakRupiah($row['total']);
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
			else $this->redirect();
		}

		/**
		* Method delete
		* Menghapus data proyek dari db
		* Request berupa POST dan output berupa JSON
		* Parameter id => id proyek
		*/
		public function delete($id){
			if($_SERVER['REQUEST_METHOD'] == "POST" && $id != ''){
				$id = strtoupper($id);
				if(empty($id) || $id == "") $this->redirect(BASE_URL."proyek/");

				if($this->ProyekModel->delete($id)) $this->status = true;

				echo json_encode($this->status);
			}
			else $this->redirect();	
		}

		/**
		* Method get last id
		* Get id proyek terbaru
		* Request berupa POST dan output berupa JSON
		*/
		public function get_last_id(){
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
			else $this->redirect();	
		}

		/**
		* Method get skk
		* Get list data skk yang aktif
		* Request berupa POST dan output berupa JSON
		*/
		public function get_skk(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$this->model('Sub_kas_kecilModel');

				$data_skk = $this->Sub_kas_kecilModel->getAll();
				$data = array();

				foreach($data_skk as $row){
					$dataRow = array();
					$dataRow['id'] = $row['id'];
					$dataRow['text'] = $row['id'].' - '.$row['nama'];

					$data[] = $dataRow;
				}

				echo json_encode($data);
			}
			else $this->redirect();
		}

		/**
		*
		*/
		public function export(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){

			}
			else $this->redirect();
		}

		/**
		* Method action add detail
		*/
		public function action_add_detail(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$data = isset($_POST) ? $_POST : false;
				$error = array();

				$validasi = $this->set_validation_detail($data);
				$cek = $validasi['cek'];
				$error = $validasi['error'];

				if($cek) $this->status = true;

				$output = array(
					'status' => $this->status,
					// 'notif' => $notif,
					'error' => $error,
					'data' => $data,
				);

				echo json_encode($output);
			}
			else $this->redirect();
				
		}

		/**
		* Function validasi form utama
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
			$this->validation->set_rules($data['alamat'], 'Alamat Pembangunan', 'alamat', 'string | 1 | 500 | required');
			// kota
			$this->validation->set_rules($data['kota'], 'Kota', 'kota', 'string | 1 | 255 | required');
			// estimasi
			$this->validation->set_rules($data['estimasi'], 'Estimasi Pengerjaan', 'estimasi', 'nilai | 1 | 255 | required');
			// total
			$this->validation->set_rules($data['total'], 'Total Dana', 'total', 'nilai | 0 | 99999999999 | required');
			// dp
			$this->validation->set_rules($data['dp'], 'DP Proyek', 'dp', 'nilai | 0 | 99999999999 | required');
			// cco
			$this->validation->set_rules($data['cco'], 'CCO', 'cco', 'nilai | 0 | 99999999999 | not_required');
			// status
			$this->validation->set_rules($data['status'], 'Status Proyek', 'status', 'string | 1 | 255 | required');
			// progress
			$this->validation->set_rules($data['progress'], 'Progress Proyek', 'progress', 'nilai | 0 | 100 | required');

			return $this->validation->run();
		}

		/**
		* Function validasi form detail
		*/
		private function set_validation_detail($data){
			// angsuran
			$this->validation->set_rules($data['angsuran'], 'Angsuran Proyek', 'angsuran', 'string | 1 | 255 | required');
			// persentase
			$this->validation->set_rules($data['persentase'], 'Persentase Angsuran', 'persentase', 'nilai | 1 | 100 | required');
			// total
			$this->validation->set_rules($data['total_detail'], 'Total Angsuran', 'total_detail', 'nilai | 1 | 9999999999 | required');
			// status
			$this->validation->set_rules($data['status_detail'], 'Status Detail', 'status_detail', 'string | 1 | 255 | required');

			return $this->validation->run();
		}

	}