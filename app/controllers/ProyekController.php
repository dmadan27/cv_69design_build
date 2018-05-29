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
			// $token = isset($_POST['token_list']) ? $_POST['token_list'] : false;
			
			// cek token
			// $this->auth->cekToken($_SESSION['token_proyek']['list'], $token, 'proyek');
			
			// config datatable
			$config_dataTable = array(
				'tabel' => 'proyek',
				'kolomOrder' => array(null, 'id', 'pemilik', 'tgl', 'pembangunan', 'kota', 'total', 'status', null),
				'kolomCari' => array('id', 'pemilik', 'tgl', 'pembangunan', 'luas_area', 'status'),
				'orderBy' => array('status' => 'asc', 'id' => 'asc'),
				'kondisi' => false,
			);

			$dataProyek = $this->ProyekModel->getAllDataTable($config_dataTable);

			// set token
			// $_SESSION['token_proyek']['edit'] = md5($this->auth->getToken());
			// $_SESSION['token_proyek']['delete'] = md5($this->auth->getToken());
			
			// $this->token = array(
			// 	'edit' => password_hash($_SESSION['token_proyek']['edit'], PASSWORD_BCRYPT),
			// 	'delete' => password_hash($_SESSION['token_proyek']['delete'], PASSWORD_BCRYPT),	
			// );

			$data = array();
			$no_urut = $_POST['start'];
			foreach($dataProyek as $row){
				$no_urut++;

				$status = (strtolower($row['status']) == "selesai") ? '<span class="label label-success">'.$row['status'].'</span>' : '<span class="label label-primary">'.$row['status'].'</span>';

				// button aksi
				$aksiDetail = '<button onclick="getView('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-info btn-flat" title="Lihat Detail"><i class="fa fa-eye"></i></button>';
				// $aksiEdit = '<button onclick="getEdit('."'".$row["id"]."'".', '."'".$this->token["edit"]."'".')" type="button" class="btn btn-sm btn-success btn-flat" title="Edit Data"><i class="fa fa-pencil"></i></button>';
				// $aksiHapus = '<button onclick="getDelete('."'".$row["id"]."'".', '."'".$this->token["delete"]."'".')" type="button" class="btn btn-sm btn-danger btn-flat" title="Hapus Data"><i class="fa fa-trash"></i></button>';
				
				$aksi = '<div class="btn-group">'.$aksiDetail.'</div>';
				
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
			if($id)	$this->edit($id);
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
				'app/views/proyek/js/initForm.js',
				
			);


			$config = array(
				'title' => array(
					'main' => 'Data Proyek',
					'sub' => '',
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
				'token_add' => $this->token['add'],
				'action' => "action-add",
			);

			$this->layout('proyek/form', $config, $data);
		}

		/**
		* 
		*/
		public function action_add(){
			$data = isset($_POST) ? $_POST : false;
			// $this->auth>cekToken($_SESSION['token_proyek']['add'],$data['token'], 'proyek');
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
				$validasi = $this->set_validation($data);
				$cek = $validasi['cek'];
				$error = $validasi['error'];

				if($cek){
					// validasi input
					$data = array(
						'id' => $this->validation->validInput($data['id']),
						'pemilik' => $this->validation->validInput($data['pemilik']),
						'tgl' => $this->validation->validInput($data['tgl']),
						'pembangunan' => $this->validation->validInput($data['pembangunan']),
						'luas_area' => $this->validation->validInput($data['luas_area']),
						'alamat' => $this->validation->validInput($data['alamat']),
						'kota' => $this->validation->validInput($data['kota']),
						'estimasi' => $this->validation->validInput($data['estimasi']),
						'total' => $this->validation->validInput($data['total']),
						'dp' => $this->validation->validInput($data['dp']),
						'cco' => $this->validation->validInput($data['cco']),
						'status' => $this->validation->validInput($data['status']),	
					);

					// insert db
					// transact

					if($this->ProyekModel->insert($data)){
						$status = true;
						$notif = array(
							'title' => "Pesan Berhasil",
							'message' => "Tambah Data Proyek Baru Berhasil",
						);
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
				'data' => $data,
					
			);
			echo json_encode($output);
		}

		/**
		* 
		*/
		protected function edit($id){
		
		}

		/**
		* 
		*/
		protected function action_edit(){

		}

		/**
		*
		*/
		public function detail($id){
			// // if(empty($id) || $id == "") $this->redirect(BASE_URL."proyek/");
			// $data_detail != empty($this->ProyekModel->getById($id)) ? $this->ProyekModel->getById($id) : false;
			// if(!$data_detail) $this->redirect(BASE_URL."proyek/");

			$css = array(
				
			);
			$js = array(
			
				
			);

			$config = array(
				'title' => array(
					'main' => 'Data Proyek',
					'sub' => 'Detail Data Proyek',
				),
				'css' => $css,
				'js' => $js,
			);

			// $status = ($data_detail['status'] == "AKTIF") ? '<span class="label label-success">'.$data_detail['status'].'</span>' : '<span class="label label-danger">'.$data_detail['status'].'</span>';
			
			// $_SESSION['token_bank']['view'] = md5($this->auth->getToken());
			// $_SESSION['token_bank']['edit'] = md5($this->auth->getToken());
			// $_SESSION['token_bank']['delete'] = md5($this->auth->getToken());
			
			// $this->token = array(
			// 	'view' => password_hash($_SESSION['token_bank']['view'], PASSWORD_BCRYPT),
			// 	'edit' => password_hash($_SESSION['token_bank']['edit'], PASSWORD_BCRYPT),
			// 	'delete' => password_hash($_SESSION['token_bank']['delete'], PASSWORD_BCRYPT)
			// );

			// $data = array(
			// 'id_bank' => $data_detail['id'],
			// 'nama' => $data_detail['nama'],
			// 'saldo' => $this->helper->cetakRupiah($data_detail['saldo']),
			// 'status' => $status,
			// 'token' => $this->token,
			// );

			$this->layout('proyek/view', $config);


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
			$data = array(
				// 'result' => array(
					array(
						'id' => '',
						'text' => '-- Pilih Sub Kas Kecil --',
					),
				// ),
					
			);

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
		private function set_validation($data){
			$required = ($data['action'] =="action-add") ? 'not_required' : 'required';

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