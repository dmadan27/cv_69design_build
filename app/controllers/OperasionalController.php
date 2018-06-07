<?php 
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	*
	*/
	class Operasional extends Crud_modalsAbstract{

		protected $token;

		/**
		* load auth, cekAuth
		* load default model, BankModel
		* load helper dan validation
		*/
		public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
			$this->model('OperasionalModel');
			$this->helper();
			$this->validation();
		}	

		/**
		* Function index
		* menjalankan method list
		*/
		public function index(){
			$this->list();
		}

		/**
		* Function list
		* setting layouting list utama
		* generate token list dan add
		*/
		protected function list(){
			// set config untuk layouting
			$css = array(
				'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
				'assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
				'assets/bower_components/select2/dist/css/select2.min.css',

			);
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
				'assets/bower_components/select2/dist/js/select2.full.min.js',
				'app/views/operasional/js/initList.js',
				'app/views/operasional/js/initForm.js',
			);

			$config = array(
				'title' => array(
					'main' => 'Data Operasional',
					'sub' => 'List Semua Data Operasional',
				),
				'css' => $css,
				'js' => $js,
			);
			
			// set token
			$_SESSION['token_operasional'] = array(
				'list' => md5($this->auth->getToken()),
				'add' => md5($this->auth->getToken()),
			);

			$this->token = array(
				'list' => password_hash($_SESSION['token_operasional']['list'], PASSWORD_BCRYPT),
				'add' => password_hash($_SESSION['token_operasional']['add'], PASSWORD_BCRYPT),	
			);

			$data = array(
				'token_list' => $this->token['list'],
				'token_add' => $this->token['add'],
			);

			$this->layout('operasional/list', $config, $data);
		}	

		/**
		* Function get_list
		* method khusus untuk datatable
		* generate token edit dan delete
		* return json
		*/
		public function get_list(){
			$token = isset($_POST['token_list']) ? $_POST['token_list'] : false;
			
			// // cek token
			$this->auth->cekToken($_SESSION['token_operasional']['list'], $token, 'operasional');
			
			// // config datatable
			$config_dataTable = array(
				'tabel' => 'v_operasional',
				'kolomOrder' => array(null, 'id', 'nama_bank', 'tgl', 'nama', 'nominal', null),
				'kolomCari' => array('nama', 'tgl', 'nama', 'nama_bank', 'nominal', 'ket'),
				'orderBy' => array('tgl' => 'desc'),
				'kondisi' => false,
			);

			$dataOperasional = $this->OperasionalModel->getAllDataTable($config_dataTable);

			// // set token
			$_SESSION['token_operasional']['edit'] = md5($this->auth->getToken());
			$_SESSION['token_operasional']['delete'] = md5($this->auth->getToken());
			
			$this->token = array(
				'edit' => password_hash($_SESSION['token_operasional']['edit'], PASSWORD_BCRYPT),
				'delete' => password_hash($_SESSION['token_operasional']['delete'], PASSWORD_BCRYPT),	
			);

			$data = array();
			$no_urut = $_POST['start'];
			foreach($dataOperasional as $row){
				$no_urut++;

				// button aksi
				$aksiDetail = '<button onclick="getView('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-info btn-flat" title="Lihat Detail"><i class="fa fa-eye"></i></button>';
				$aksiEdit = '<button onclick="getEdit('."'".$row["id"]."'".', '."'".$this->token["edit"]."'".')" type="button" class="btn btn-sm btn-success btn-flat" title="Edit Data"><i class="fa fa-pencil"></i></button>';
				$aksiHapus = '<button onclick="getDelete('."'".$row["id"]."'".', '."'".$this->token["delete"]."'".')" type="button" class="btn btn-sm btn-danger btn-flat" title="Hapus Data"><i class="fa fa-trash"></i></button>';
				
				$aksi = '<div class="btn-group">'.$aksiDetail.$aksiEdit.$aksiHapus.'</div>';
				
				$dataRow = array();
				$dataRow[] = $no_urut;
				$dataRow[] = $row['id'];
				$dataRow[] = $row['nama_bank'];
				$dataRow[] = $row['tgl'];
				$dataRow[] = $row['nama'];
				$dataRow[] = $row['nominal'];
				$dataRow[] = $aksi;
				
				// $dataRow[] = $row['ket'];
				$data[] = $dataRow;
			}

			$output = array(
				'draw' => $_POST['draw'],
				'recordsTotal' => $this->OperasionalModel->recordTotal(),
				'recordsFiltered' => $this->OperasionalModel->recordFilter(),
				'data' => $data,
			);

			echo json_encode($output);		
		}

		/**
		* Function action_add
		* method untuk aksi tambah data
		* return berupa json
		* status => status berhasil atau gagal proses tambah
		* notif => pesan yang akan ditampilkan disistem
		* error => error apa saja yang ada dari hasil validasi
		*/
		public function action_add(){
			$data = isset($_POST) ? $_POST : false;
			$this->auth->cekToken($_SESSION['token_operasional']['add'], $data['token'], 'operasional');
			
			$status = false;
			$error = "";

			if(!$data){
				$notif = array(
					'title' => "Pesan Gagal",
					'message' => "Terjadi Kesalahan Teknis, Silahkan Coba Kembali",
				);
			}
			else{
				// validasi data
				$validasi = $this->set_validation($data);
				$cek = $validasi['cek'];
				$error = $validasi['error'];

				$this->model('BankModel');
				$getSaldo = $this->BankModel->getById($data['id_bank'])['saldo'];

				if($data['nominal'] > $getSaldo){
					$cek = false;
					$error['nominal'] = "Nominal terlalu besar dan melebihi saldo bank";
				}

				if($cek){
					// validasi inputan
					$data = array(
						'id_bank' => $this->validation->validInput($data['id_bank']),
						'tgl' => $this->validation->validInput($data['tgl']),
						'nama' => $this->validation->validInput($data['nama']),
						'nominal' => $this->validation->validInput($data['nominal']),
						'ket' => $this->validation->validInput($data['ket'])
					);

					if($this->OperasionalModel->insert($data)) {
						$status = true;
						$notif = array(
							'title' => "Pesan Berhasil",
							'message' => "Tambah Data Operasional Baru Berhasil",
						);
					}
					else {
						$notif = array(
							'title' => "Pesan Gagal",
							'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
						);
					}
				}
				else {
					$notif = array(
						'title' => "Pesan Pemberitahuan",
						'message' => "Silahkan Cek Kembali Form Isian",
					);
				}
			}

			$output = array(
				'status' => $status,
				'notif' => $notif,
				'error' => $error,
				'data' => $data
			);

			echo json_encode($output);		
		}

		/**
		* Function edit
		* method untuk get data edit
		* param $id didapat dari url
		* return berupa json
		*/
		public function edit($id){
			$id = strtoupper($id);
			$token = isset($_POST['token_edit']) ? $_POST['token_edit'] : false;
			$this->auth->cekToken($_SESSION['token_operasional']['edit'], $token, 'operasional');

			$data = !empty($this->OperasionalModel->getById($id)) ? $this->OperasionalModel->getById($id) : false;
			echo json_encode($data);
		}

		// /**
		// * Function action_edit
		// * method untuk aksi edit data
		// * return berupa json
		// * status => status berhasil atau gagal proses edit
		// * notif => pesan yang akan ditampilkan disistem
		// * error => error apa saja yang ada dari hasil validasi
		// */
		public function action_edit(){
			$data = isset($_POST) ? $_POST : false;
			$this->auth->cekToken($_SESSION['token_operasional']['edit'], $data['token'], 'operasional');
			
			$status = false;
			$error = "";

			if(!$data){
				$notif = array(
					'title' => "Pesan Gagal",
					'message' => "Terjadi Kesalahan Teknis, Silahkan Coba Kembali",
				);
			}
			else{
				// validasi data
				$validasi = $this->set_validation($data);
				$cek = $validasi['cek'];
				$error = $validasi['error'];

				$getData = $this->OperasionalModel->getById($id);

				$this->model('BankModel');

				// jika bank ada perubahan
				if($data['id_bank'] != $getData['id_bank']){
					$getSaldo = $this->BankModel->getSaldoById($data['id_bank'])['saldo'];

					if($data['nominal'] > $getSaldo){
						$cek = false;
						$error['nominal'] = "Nominal terlalu besar dan melebihi saldo bank";
					}
				}
				else{
					// jika bank sama tapi ada perubahan nominal
					if($getData['nominal'] != $data['nominal']){
						$getSaldo = $this->BankModel->getSaldoById($data['id_bank'])['saldo'];

						if($data['nominal'] > ($getSaldo + $getData['nominal'])){
							$cek = false;
							$error['nominal'] = "Nominal terlalu besar dan melebihi saldo bank";
						}
					}
				}

				if($cek){
					// validasi inputan
					$data = array(
						'id' => $this->validation->validInput($data['id']),
						'tgl' => $this->validation->validInput($data['tgl']),
						'nama' => $this->validation->validInput($data['nama']),
						'nominal' => $this->validation->validInput($data['nominal']),
						'ket' => $this->validation->validInput($data['ket'])
					);

					if($this->OperasionalModel->update($data)) {
						$status = true;
						$notif = array(
							'title' => "Pesan Berhasil",
							'message' => "Edit Data Operasional Berhasil",
						);
					}
					else {
						$notif = array(
							'title' => "Pesan Gagal",
							'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
						);
					}

				}
				else {
					$notif = array(
						'title' => "Pesan Pemberitahuan",
						'message' => "Silahkan Cek Kembali Form Isian",
					);
				}
			}

			$output = array(
				'status' => $status,
				'notif' => $notif,
				'error' => $error,
				// 'data' => $data
			);

			echo json_encode($output);
		}

		/**
		* Function detail
		* method untuk get data detail dan setting layouting detail
		* param $id didapat dari url
		*/
		public function detail($id){
			// $id = strtoupper($id);
			// if(empty($id) || $id == "") $this->redirect(BASE_URL."bank/");

			// $data_detail = !empty($this->BankModel->getById($id)) ? $this->BankModel->getById($id) : false;

			// if(!$data_detail) $this->redirect(BASE_URL."bank/");

			// $css = array(
			// 	'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
			// );
			// $js = array(
			// 	'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
			// 	'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
			// 	'app/views/bank/js/initView.js',
			// 	'app/views/bank/js/initForm.js',
			// );

			// $config = array(
			// 	'title' => array(
			// 		'main' => 'Data Bank',
			// 		'sub' => 'Detail Data Bank',
			// 	),
			// 	'css' => $css,
			// 	'js' => $js,
			// );

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
			// 	'id_bank' => $data_detail['id'],
			// 	'nama' => $data_detail['nama'],
			// 	'saldo' => $this->helper->cetakRupiah($data_detail['saldo']),
			// 	'status' => $status,
			// 	'token' => $this->token,
			// );

			// // echo "<pre>";
			// // print_r($this->token);
			// // echo "</pre>";
			// $this->layout('bank/view', $config, $data);
		}

		/**
		* Function delete
		* method yang berfungsi untuk menghapus data
		* param $id didapat dari url
		* return json
		*/
		public function delete($id){
			$id = strtoupper($id);
			$token = isset($_POST['token_delete']) ? $_POST['token_delete'] : false;
			$this->auth->cekToken($_SESSION['token_operasional']['delete'], $token, 'operasional');
			
			$getNamaOperasional = $this->OperasionalModel->getById($id)['nama'];
			$ket = 'Data Operasional Bank '.$getNamaOperasional. 'telah Dihapus';

			$data= array(
				'id' => $id,
				'tgl' => date('Y-m-d'),
				'ket' => $ket,
					
			);

			if($this->OperasionalModel->delete($data)) $status = true;
			else $status = false;

			echo json_encode($status);
		}

		/**
		* Function get_mutasi
		* method yang berfungsi untuk get data mutasi bank sesuai dengan id
		* dipakai di detail data
		*/
		public function get_mutasi(){
		// 	$data = isset($_POST) ? $_POST : false;
		// 	// cek token
		// 	$this->auth->cekToken($_SESSION['token_bank']['view'], $data['token_view'], 'bank');

		// 	$this->model('Mutasi_bankModel');
			
		// 	// config datatable
		// 	$config_dataTable = array(
		// 		'tabel' => 'mutasi_bank',
		// 		'kolomOrder' => array(null, 'tgl', 'uang_masuk', 'uang_keluar', 'saldo', 'ket'),
		// 		'kolomCari' => array('tgl', 'uang_masuk', 'uang_keluar', 'saldo', 'ket'),
		// 		'orderBy' => array('id' => 'desc'),
		// 		'kondisi' => 'WHERE id = '.$data['id'].' ',
		// 	);

		// 	$dataMutasi = $this->Mutasi_bankModel->getAllDataTable($config_dataTable);

		// 	$data = array();
		// 	$no_urut = $_POST['start'];
		// 	foreach($dataMutasi as $row){
		// 		$no_urut++;
				
		// 		$dataRow = array();
		// 		$dataRow[] = $no_urut;
		// 		$dataRow[] = $row['tgl'];
		// 		$dataRow[] = $this->helper->cetakRupiah($row['uang_masuk']);
		// 		$dataRow[] = $this->helper->cetakRupiah($row['uang_keluar']);
		// 		$dataRow[] = $this->helper->cetakRupiah($row['saldo']);
		// 		$dataRow[] = $row['ket'];

		// 		$data[] = $dataRow;
		// 	}

		// 	$output = array(
		// 		'draw' => $_POST['draw'],
		// 		'recordsTotal' => $this->Mutasi_bankModel->recordTotal(),
		// 		'recordsFiltered' => $this->Mutasi_bankModel->recordFilter(),
		// 		'data' => $data,
		// 	);

		// 	echo json_encode($output);
		}

		/**
		*
		*/
		public function export(){

		}

		/**
		* Fungsi set_validation
		* method yang berfungsi untuk validasi inputan secara server side
		* param $data didapat dari post yang dilakukan oleh user
		* return berupa array, status hasil pengecekan dan error tiap validasi inputan
		*/
		private function set_validation($data){
		
			// id_bank
			$this->validation->set_rules($data['id_bank'], 'id bank', 'id_bank', 'string | 1 | 255 | required');
			// tgl
			$this->validation->set_rules($data['tgl'], 'Tanggal', 'tgl', 'string | 1 | 255 | required ');
			// nama 
			$this->validation->set_rules($data['nama'], 'Nama Kebutuhan', 'nama', 'string | 1 | 255 | required');
			// nominal 
			$this->validation->set_rules($data['nominal'], 'Nominal Uang', 'nominal', 'nilai | 0 | 99999999999 | required');
			// ket 
			$this->validation->set_rules($data['ket'], 'Keterangan', 'ket', 'string | 1 | 255 | required');

			return $this->validation->run();
		}

		/**
		*
		*/
		public function get_bank(){
			$this->model('BankModel');

			$data_bank = $this->BankModel->getAll();
			$data = array();

			foreach($data_bank as $row){
				$dataRow = array();
				$dataRow['id'] = $row['id'];
				$dataRow['text'] = $row['nama'].' - '.$this->helper->cetakRupiah($row['saldo']);

				$data[] = $dataRow;
			}

			echo json_encode($data);
		}

	}