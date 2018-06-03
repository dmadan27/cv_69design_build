<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	* 
	*/
	class Proyek extends CrudAbstract{
		
		protected $token;

		/**
		* 
		*/
		public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
			$this->model('ProyekModel');
			$this->helper();
			$this->validation();
		}

		/**
		* 
		*/
		public function index(){
			$this->list();
		}

		/**
		* 
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

			// set token
			$_SESSION['token_proyek'] = array(
				'list' => md5($this->auth->getToken()),
				'add' => md5($this->auth->getToken()),
			);

			$this->token = array(
				'list' => password_hash($_SESSION['token_proyek']['list'], PASSWORD_BCRYPT),
				'add' => password_hash($_SESSION['token_proyek']['add'], PASSWORD_BCRYPT),	
			);

			$data = array(
				'token_list' => $this->token['list'],
				'token_add' => $this->token['add'],
			);

			$this->layout('proyek/list', $config, $data);
		}

		/**
		* 
		*/
		public function get_list(){
			// cek token
			$token = isset($_POST['token_list']) ? $_POST['token_list'] : false;
			$this->auth->cekToken($_SESSION['token_proyek']['list'], $token, 'proyek');
			
			// config datatable
			$config_dataTable = array(
				'tabel' => 'proyek',
				'kolomOrder' => array(null, 'id', 'pemilik', 'tgl', 'pembangunan', 'kota', 'total', 'status', null),
				'kolomCari' => array('id', 'pemilik', 'tgl', 'pembangunan', 'luas_area', 'status'),
				'orderBy' => array('id' => 'desc', 'status' => 'asc'),
				'kondisi' => false,
			);

			$dataProyek = $this->ProyekModel->getAllDataTable($config_dataTable);

			// set token
			$_SESSION['token_proyek']['view'] = md5($this->auth->getToken());
			$_SESSION['token_proyek']['edit'] = md5($this->auth->getToken());
			$_SESSION['token_proyek']['delete'] = md5($this->auth->getToken());
			
			$this->token = array(
				'view' => password_hash($_SESSION['token_proyek']['view'], PASSWORD_BCRYPT),
				'edit' => password_hash($_SESSION['token_proyek']['edit'], PASSWORD_BCRYPT),
				'delete' => password_hash($_SESSION['token_proyek']['delete'], PASSWORD_BCRYPT),	
			);

			$data = array();
			$no_urut = $_POST['start'];
			foreach($dataProyek as $row){
				$no_urut++;

				$status = (strtolower($row['status']) == "selesai") ? '<span class="label label-success">'.$row['status'].'</span>' : '<span class="label label-primary">'.$row['status'].'</span>';

				// button aksi
				$aksiDetail = '<button onclick="getView('."'".strtolower($row["id"])."'".', '."'".$this->token["view"]."'".')" type="button" class="btn btn-sm btn-info btn-flat" title="Lihat Detail"><i class="fa fa-eye"></i></button>';
				$aksiEdit = '<button onclick="getEdit('."'".strtolower($row["id"])."'".', '."'".$this->token["edit"]."'".')" type="button" class="btn btn-sm btn-success btn-flat" title="Edit Data"><i class="fa fa-pencil"></i></button>';
				$aksiHapus = '<button onclick="getDelete('."'".strtolower($row["id"])."'".', '."'".$this->token["delete"]."'".')" type="button" class="btn btn-sm btn-danger btn-flat" title="Hapus Data"><i class="fa fa-trash"></i></button>';
				
				$aksi = '<div class="btn-group">'.$aksiDetail.$aksiEdit.$aksiHapus.'</div>';
				
				$dataRow = array();
				$dataRow[] = $no_urut;
				$dataRow[] = $row['id'];
				$dataRow[] = $row['pemilik'];
				$dataRow[] = $this->helper->cetakTgl($row['tgl'], 'full');
				$dataRow[] = $row['pembangunan'];
				$dataRow[] = $row['kota'];
				$dataRow[] = $this->helper->cetakRupiah($row['total']);
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

		/**
		* 
		*/
		public function form($id){
			if($id)	$this->edit(strtoupper($id));
			else $this->add();
		}

		/**
		* 
		*/
		protected function add(){
			$css = array(
  				'assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
				'assets/bower_components/select2/dist/css/select2.min.css',
  			);
			$js = array(
				'assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
				'assets/bower_components/select2/dist/js/select2.full.min.js',
				'assets/plugins/input-mask/jquery.inputmask.bundle.js',
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

			$_SESSION['token_proyek'] = array(
				'add' => md5($this->auth->getToken()),
			);
			$this->token = array(
				'add' => password_hash($_SESSION['token_proyek']['add'], PASSWORD_BCRYPT),	
			);
			$data = array(
				'token_form' => $this->token['add'],
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
			);

			$this->layout('proyek/form', $config, $data);
		}

		/**
		* 
		*/
		public function action_add(){
			$data = isset($_POST) ? $_POST : false;
			$dataProyek = isset($_POST['dataProyek']) ? json_decode($_POST['dataProyek'], true) : false;
			$dataDetail = isset($_POST['dataDetail']) ? json_decode($_POST['dataDetail'], true) : false;
			$dataSkc = isset($_POST['dataSkc']) ? json_decode($_POST['dataSkc'], true) : false;

			$this->auth->cekToken($_SESSION['token_proyek']['add'], $data['token'], 'proyek');
			
			$status = false;
			$error = "";

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

				if(empty($dataDetail) || empty($dataSkc)) $cek = false;

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
					);

					$dataInsert = array(
						'dataProyek' => $dataProyek,
						'dataDetail' => $dataDetail,
						'dataSkc' => $dataSkc,
					);

					// insert data proyek
					if($this->ProyekModel->insert($dataInsert)){
						$status = true;
						$_SESSION['notif'] = array(
							'title' => "Pesan Berhasil",
							'message' => "Tambah Data Proyek Baru Berhasil",
						);
						$notif = $_SESSION['notif'];
					}
					else{
						$notif = array(
							'title' => "Pesan Gagal",
							'message' => "Terjadi kesalahan teknis, silahkan coba kembali",
						);
					}
				}
				else{
					$notif = array(
						'title' => "Pesan Pemberitahuan",
						'message' => "Silahkan Cek Kembali Form Isian ",
					);
				}
			}

			$output = array(
				'status' => $status,
				'notif' => $notif,
				'error' => $error,
				// 'data' => $data,
				// 'dataProyek' => $dataProyek,
				// 'dataDetail' => $dataDetail,
				// 'dataSkc' => $dataSkc,
			);
			echo json_encode($output);
		}

		/**
		* 
		*/
		protected function edit($id){
			if(empty($id) || $id == "") $this->redirect(BASE_URL."proyek/");

			// get data proyek
			$dataProyek = !empty($this->ProyekModel->getById($id)) ? $this->ProyekModel->getById($id) : false;

			if(!$dataProyek) $this->redirect(BASE_URL."proyek/");

			$css = array(
  				'assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
				'assets/bower_components/select2/dist/css/select2.min.css',
  			);
			$js = array(
				'assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
				'assets/bower_components/select2/dist/js/select2.full.min.js',
				'assets/plugins/input-mask/jquery.inputmask.bundle.js',
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

			$_SESSION['token_proyek'] = array(
				'edit' => md5($this->auth->getToken()),
			);
			$this->token = array(
				'edit' => password_hash($_SESSION['token_proyek']['edit'], PASSWORD_BCRYPT),
			);

			$data = array(
				'token_form' => $this->token['edit'],
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
			);

			$this->layout('proyek/form', $config, $data);
		}

		/**
		*
		*/
		public function get_edit($id){
			$id = strtoupper($id);
			if(empty($id) || $id == "") $this->redirect(BASE_URL."proyek/");

			$token = isset($_POST['token_edit']) ? $_POST['token_edit'] : false;
			$this->auth->cekToken($_SESSION['token_proyek']['edit'], $token, 'proyek');

			// get data detail dan skc
			$dataDetail = $this->ProyekModel->getDetailById($id);
			$dataSkc = $this->ProyekModel->getSkcById($id);

			$output = array(
				'dataDetail' => $dataDetail,
				'dataSkc' => $dataSkc,
			);

			echo json_encode($output);
		}

		/**
		* 
		*/
		public function action_edit(){
			$data = isset($_POST) ? $_POST : false;
			$dataProyek = isset($_POST['dataProyek']) ? json_decode($_POST['dataProyek'], true) : false;
			$dataDetail = isset($_POST['dataDetail']) ? json_decode($_POST['dataDetail'], true) : false;
			$dataSkc = isset($_POST['dataSkc']) ? json_decode($_POST['dataSkc'], true) : false;

			$this->auth->cekToken($_SESSION['token_proyek']['edit'], $data['token'], 'proyek');
			
			$status = false;
			$error = "";

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

				if(empty($dataDetail) || empty($dataSkc)) $cek = false;

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
					);

					// update db
					// transact

					// insert data proyek
					if($this->ProyekModel->update($dataProyek)){
						// insert data detail
						foreach($dataDetail as $index => $row){
							if(!$dataDetail[$index]['delete']) $this->ProyekModel->insertDetail(array_map('strtoupper', $row));
						}

						// insert data skc
						foreach($dataSkc as $index => $row){
							if(!$dataSkc[$index]['delete']) $this->ProyekModel->insertSkc(array_map('strtoupper', $row));	
						}

						$status = true;
						$_SESSION['notif'] = array(
							'title' => "Pesan Berhasil",
							'message' => "Tambah Data Proyek Baru Berhasil",
						);
						$notif = $_SESSION['notif'];
					}
					else{
						$notif = array(
							'title' => "Pesan Gagal",
							'message' => "Terjadi Kesalahan ",
						);
					}

					// commit
				}
				else{
					$notif = array(
						'title' => "Pesan Pemberitahuan",
						'message' => "Silahkan Cek Kembali Form Isian ",
					);
				}
			}

			$output = array(
				'status' => $status,
				'notif' => $notif,
				'error' => $error,
				// 'data' => $data,
				// 'dataProyek' => $dataProyek,
				// 'dataDetail' => $dataDetail,
				// 'dataSkc' => $dataSkc,
			);
			echo json_encode($output);
		}

		/**
		*
		*/
		public function detail($id){
			$id = strtoupper($id);
			if(empty($id) || $id == "") $this->redirect(BASE_URL."proyek/");

			$dataProyek = !empty($this->ProyekModel->getById($id)) ? $this->ProyekModel->getById($id) : false;
			
			if(!$dataProyek) $this->redirect(BASE_URL."proyek/");

			$css = array(
				// 'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css'
			);
			$js = array(
				// 'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				// 'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				// 'app/views/proyek/js/initView.js',
			);

			$config = array(
				'title' => array(
					'main' => 'Data Proyek',
					'sub' => 'Detail Data Proyek',
				),
				'css' => $css,
				'js' => $js,
			);

			// set token
			$_SESSION['token_proyek'] = array(
				'view' => md5($this->auth->getToken()),
			);

			$this->token = array(
				'view' => password_hash($_SESSION['token_proyek']['view'], PASSWORD_BCRYPT),
			);

			$dataProyek = array(
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
				'status' => (strtolower($dataProyek['status']) == "selesai") ? '<span class="label label-success">'.$dataProyek['status'].'</span>' : '<span class="label label-primary">'.$dataProyek['status'].'</span>',
				'progress' => array(
					'style' => 'style="width: 40%"',
					'value' => '40',
					'text' => '40% Success',
				),
			);

			$dataArus = array();

			$data = array(
				'token_view' => $this->token['view'],
				'data_proyek' => $dataProyek,
				'data_arus' => $dataArus,
			);

			$this->layout('proyek/view', $config, $data);
		}

		/**
		*
		*/
		public function delete($id){

		}

		/**
		*
		*/
		public function get_last_id(){
			$token = isset($_POST['token']) ? $_POST['token'] : false;
			$this->auth->cekToken($_SESSION['token_proyek']['add'], $token, 'proyek');

			$data = !empty($this->ProyekModel->getLastID()['id']) ? $this->ProyekModel->getLastID()['id'] : false;

			if(!$data) $id = 'PRY001';
			else{
				// $data = implode('', $data);
				$kode = 'PRY';
				$noUrut = (int)substr($data, 3, 3);
				$noUrut++;

				$id = $kode.sprintf("%03s", $noUrut);
			}

			echo $id;
		}

		/**
		*
		*/
		public function get_skc(){
			$this->model('Sub_kas_kecilModel');

			$data_skc = $this->Sub_kas_kecilModel->getAll();
			$data = array();

			foreach($data_skc as $row){
				$dataRow = array();
				$dataRow['id'] = $row['id'];
				$dataRow['text'] = $row['id'].' - '.$row['nama'];

				$data[] = $dataRow;
			}

			echo json_encode($data);
		}

		/**
		*
		*/
		public function export(){

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

			return $this->validation->run();
		}

		/**
		*
		*/
		public function action_add_detail(){
			$data = isset($_POST) ? $_POST : false;
			
			$status = false;
			$error = "";

			$validasi = $this->set_validation_detail($data);
			$cek = $validasi['cek'];
			$error = $validasi['error'];

			if($cek) $status = true;

			$output = array(
				'status' => $status,
				// 'notif' => $notif,
				'error' => $error,
				'data' => $data,
			);
			echo json_encode($output);
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