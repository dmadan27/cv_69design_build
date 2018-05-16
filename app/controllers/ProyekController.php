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
			$token = isset($_POST['token_list']) ? $_POST['token_list'] : false;
			
			// cek token
			$this->auth->cekToken($_SESSION['token_proyek']['list'], $token, 'proyek');
			
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
			$_SESSION['token_proyek']['edit'] = md5($this->auth->getToken());
			$_SESSION['token_proyek']['delete'] = md5($this->auth->getToken());
			
			$this->token = array(
				'edit' => password_hash($_SESSION['token_proyek']['edit'], PASSWORD_BCRYPT),
				'delete' => password_hash($_SESSION['token_proyek']['delete'], PASSWORD_BCRYPT),	
			);

			$data = array();
			$no_urut = $_POST['start'];
			foreach($dataProyek as $row){
				$no_urut++;

				$status = ($row['status'] == "SELESAI") ? '<span class="label label-success">'.$row['status'].'</span>' : '<span class="label label-primary">'.$row['status'].'</span>';

				// button aksi
				$aksiDetail = '<button onclick="getView('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-info btn-flat" title="Lihat Detail"><i class="fa fa-eye"></i></button>';
				$aksiEdit = '<button onclick="getEdit('."'".$row["id"]."'".', '."'".$this->token["edit"]."'".')" type="button" class="btn btn-sm btn-success btn-flat" title="Edit Data"><i class="fa fa-pencil"></i></button>';
				$aksiHapus = '<button onclick="getDelete('."'".$row["id"]."'".', '."'".$this->token["delete"]."'".')" type="button" class="btn btn-sm btn-danger btn-flat" title="Hapus Data"><i class="fa fa-trash"></i></button>';
				
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
		public function form(){

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

			$this->layout('proyek/form', $config);
		}

		/**
		* 
		*/
		protected function add(){

		}

		/**
		* 
		*/
		public function action_add(){
			$data = isset($_POST) ? $_POST : false;

			// if(!$data){
			// 	$notif = array(
			// 		'title' => "Pesan Berhasil",
			// 		'message' => "Tambah Data Proyek Berhasil",
			// 	);
			// }
			// else{
			// 	// validasi data
			// 	$validasi = $this->set_validation($data);
			// 	$cek = $validasi['cek'];
			// 	$error = $validasi['error'];

			// 	if($cek){
			// 		// validasi input
			// 		$data = array(
			// 			'pemilik' => $this->validation->validInput($data['pemilik']),
			// 			'tgl' => $this->validation->validInput($data['tgl']),
			// 			'pembangunan' => $this->validation->validInput($data['pembangunan']),
			// 			'luas_area' => $this->validation->validInput($data['luas_area']),
			// 			'alamat' => $this->validation->validInput($data['alamat']),
			// 			'kota' => $this->validation->validInput($data['kota']),
			// 			'estimasi' => $this->validation->validInput($data['estimasi']),
			// 			'total' => $this->validation->validInput($data['total']),
			// 			'dp' => $this->validation->validInput($data['dp']),
			// 			'cco' => $this->validation->validInput($data['cco']),
						
							
			// 		);

			// 		// insert db
			// 		// transact

			// 		if($this->ProyekModel->insert($data)){
			// 			$status = true;
			// 			$notif = array(
			// 				'title' => "Pesan Berhasil",
			// 				'message' => "Tambah Data Proyek Baru Berhasil",
			// 			);
			// 		}
			// 		else{
			// 			$notif = array(
			// 				'title' => "Pesan Gagal",
			// 				'message' => "Terjadi Kesalahan ",
			// 			);
			// 		}

			// 		// commit


			// 	}
			// 	else{
			// 		$notif = array(
			// 				'title' => "Pesan Pemberitahuan",
			// 				'message' => "Silahkan Cek Kembali Form Isian ",
			// 			);
			// 	}
			// }

			$output = array(
				// 'status' => $status,
				// 'notif' => $notif,
				// 'error' => $error,
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
		protected function detail($id){

		}

		/**
		*
		*/
		protected function delete($id){

		}

		/**
		*
		*/
		protected function export(){

		}

	}