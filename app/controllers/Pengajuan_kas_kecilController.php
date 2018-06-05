<?php 
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	*
	*/
	class Pengajuan_kas_kecil extends Crud_modalsAbstract{

		protected $token;

		/**
		* load auth, cekAuth
		* load default model, BankModel
		* load helper dan validation
		*/
		public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
			$this->model('Pengajuan_kasKecilModel');
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
			);
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'app/views/pengajuan_kas_kecil/js/initList.js',
				'app/views/pengajuan_kas_kecil/js/initForm.js',
			);

			$config = array(
				'title' => array(
					'main' => 'Data Pengajuan Kas Kecil',
					'sub' => 'List Data Pengajuan Kas Kecil',
				),
				'css' => $css,
				'js' => $js,
			);
			
			//set token
			$_SESSION['token_pengajuan_kas_kecil'] = array(
				'list' => md5($this->auth->getToken())
				// 'add' => md5($this->auth->getToken()),
			);

			$this->token = array(
				'list' => password_hash($_SESSION['token_pengajuan_kas_kecil']['list'], PASSWORD_BCRYPT)
			// 	'add' => password_hash($_SESSION['token_bank']['add'], PASSWORD_BCRYPT),	
			);

			$data = array(
				'token_list' => $this->token['list']
			// 	'token_add' => $this->token['add'],
			);

			$this->layout('pengajuan_kas_kecil/list', $config, $data);
		}	

		/**
		* Function get_list
		* method khusus untuk datatable
		* generate token edit dan delete
		* return json
		*/
		public function get_list(){
			$token = isset($_POST['token_list']) ? $_POST['token_list'] : false;
			
			// cek token
			$this->auth->cekToken($_SESSION['token_pengajuan_kas_kecil']['list'], $token, 'pengajuan_kas_kecil');
			
			// config datatable
			$config_dataTable = array(
				'tabel' => 'pengajuan_kas_kecil',
				'kolomOrder' => array(null, 'id', 'tgl', 'nama',  'total', 'status',null),
				'kolomCari' => array('id','nama',  'status'),
				'orderBy' => array('id' => 'asc'),
				'kondisi' => false,
			);

			$dataPengajuanKasKecil = $this->Pengajuan_kasKecilModel->getAllDataTable($config_dataTable);

			// set token
			$_SESSION['token_pengajuan_kas_kecil']['edit'] = md5($this->auth->getToken());
			$_SESSION['token_pengajuan_kas_kecil']['delete'] = md5($this->auth->getToken());
			
			$this->token = array(
				'edit' => password_hash($_SESSION['token_pengajuan_kas_kecil']['edit'], PASSWORD_BCRYPT),
				'delete' => password_hash($_SESSION['token_pengajuan_kas_kecil']['delete'], PASSWORD_BCRYPT),	
			);

			$data = array();
			$no_urut = $_POST['start'];
			foreach($dataPengajuanKasKecil as $row){
				$no_urut++;

				$status = ($row['status'] == "PENDING") ? '<span class="label label-warning">'.$row['status'].'</span>' : '<span class="label label-danger">'.$row['status'].'</span>';

				// // button aksi
				$aksiDetail = '<button onclick="getView('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-info btn-flat" title="Lihat Detail"><i class="fa fa-eye"></i></button>';
				$aksiEdit = '<button onclick="getEdit('."'".$row["id"]."'".', '."'".$this->token["edit"]."'".')" type="button" class="btn btn-sm btn-success btn-flat" title="Edit Data"><i class="fa fa-pencil"></i></button>';
				$aksiHapus = '<button onclick="getDelete('."'".$row["id"]."'".', '."'".$this->token["delete"]."'".')" type="button" class="btn btn-sm btn-danger btn-flat" title="Hapus Data"><i class="fa fa-trash"></i></button>';
				
				$aksi = '<div class="btn-group">'.$aksiDetail.$aksiEdit.$aksiHapus.'</div>';
				
				$dataRow = array();
				$dataRow[] = $no_urut;
				$dataRow[] = $row['id'];
				$dataRow[] = $row['tgl'];
				$dataRow[] = $row['nama'];
				$dataRow[] = $row['total'];
				$dataRow[] = $row['status'];		
				$dataRow[] = $aksi;

				$data[] = $dataRow;
			}

			$output = array(
				'draw' => $_POST['draw'],
				'recordsTotal' => $this->Pengajuan_kasKecilModel->recordTotal(),
				'recordsFiltered' => $this->Pengajuan_kasKecilModel->recordFilter(),
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
			// $data = isset($_POST) ? $_POST : false;
			// $this->auth->cekToken($_SESSION['token_bank']['add'], $data['token'], 'bank');
			
			// $status = false;
			// $error = "";

			// if(!$data){
			// 	$notif = array(
			// 		'title' => "Pesan Gagal",
			// 		'message' => "Terjadi Kesalahan Teknis, Silahkan Coba Kembali",
			// 	);
			// }
			// else{
			// 	// validasi data
			// 	$validasi = $this->set_validation($data);
			// 	$cek = $validasi['cek'];
			// 	$error = $validasi['error'];

			// 	if($cek){
			// 		// validasi inputan
			// 		$data = array(
			// 			'nama' => $this->validation->validInput($data['nama']),
			// 			'saldo' => $this->validation->validInput($data['saldo']),
			// 			'status' => $this->validation->validInput($data['status']),
			// 		);

			// 		// insert db

			// 		// transact

			// 		if($this->BankModel->insert($data)) {
			// 			$status = true;
			// 			$notif = array(
			// 				'title' => "Pesan Berhasil",
			// 				'message' => "Tambah Data Bank Baru Berhasil",
			// 			);
			// 		}
			// 		else {
			// 			$notif = array(
			// 				'title' => "Pesan Gagal",
			// 				'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
			// 			);
			// 		}

			// 		// commit
			// 	}
			// 	else {
			// 		$notif = array(
			// 			'title' => "Pesan Pemberitahuan",
			// 			'message' => "Silahkan Cek Kembali Form Isian",
			// 		);
			// 	}
			// }

			// $output = array(
			// 	'status' => $status,
			// 	'notif' => $notif,
			// 	'error' => $error,
			// 	// 'data' => $data
			// );

			// echo json_encode($output);		
		}

		/**
		* Function edit
		* method untuk get data edit
		* param $id didapat dari url
		* return berupa json
		*/
		public function edit($id){
			$token = isset($_POST['token_edit']) ? $_POST['token_edit'] : false;
			$this->auth->cekToken($_SESSION['token_pengajuan_kas_kecil']['edit'], $token, 'pengajuan_kas_kecil');

			$data = !empty($this->Pengajuan_kasKecilModel->getById($id)) ? $this->Pengajuan_kasKecilModel->getById($id) : false;
			echo json_encode($data);
		}

		/**
		* Function action_edit
		* method untuk aksi edit data
		* return berupa json
		* status => status berhasil atau gagal proses edit
		* notif => pesan yang akan ditampilkan disistem
		* error => error apa saja yang ada dari hasil validasi
		*/
		public function action_edit(){
			$data = isset($_POST) ? $_POST : false;
			$this->auth->cekToken($_SESSION['token_pengajuan_kas_kecil']['edit'], $data['token'], 'pengajuan_kas_kecil');
			
			$status = false;
			$error = "";
			if(!$data){
				$notif = array(
					'title' => "Pesan Pemberitahuan",
					'message' => "Silahkan Cek Kembali Form Isian",
				);
			}
			else{
				// validasi data
			$validasi = $this->set_validation($data);
			$cek = $validasi['cek'];
			$error = $validasi['error'];

			if($cek){
				// validasi inputan
				$data = array(
					'id' => $this->validation->validInput($data['id']),
					// 'tgl' => $this->validation->validInput($data['tgl']),
					// 'nama' => $this->validation->validInput($data['nama']),
					// 'total' => $this->validation->validInput($data['total']),
					'status' => $this->validation->validInput($data['status'])
						
				);

				// update db

				// transact

				if($this->Pengajuan_kasKecilModel->update($data)) {
					$status = true;
					$notif = array(
						'title' => "Pesan Berhasil",
						'message' => "Edit Data Pengajuan Kas Kecil Berhasil",
					);
				}
				else {
					$notif = array(
						'title' => "Pesan Gagal",
						'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
					);
				}

				// commit
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
			$token = isset($_POST['token_pengajuan_kas_kecil']) ? $_POST['token_delete'] : false;
			$this->auth->cekToken($_SESSION['token_pengajuan_kas_kecil']['delete'], $token, 'pengajuan_kas_kecil');
			
			if($this->Pengajuan_kasKecilModel->delete($id)) $status = '';
			else $status = 'gagal';

			echo json_encode($status);
		}

		/**
		* Function get_mutasi
		* method yang berfungsi untuk get data mutasi bank sesuai dengan id
		* dipakai di detail data
		*/
		public function get_mutasi(){
			// $data = isset($_POST) ? $_POST : false;
			// // cek token
			// $this->auth->cekToken($_SESSION['token_bank']['view'], $data['token_view'], 'bank');

			// $this->model('Mutasi_bankModel');
			
			// // config datatable
			// $config_dataTable = array(
			// 	'tabel' => 'mutasi_bank',
			// 	'kolomOrder' => array(null, 'tgl', 'uang_masuk', 'uang_keluar', 'saldo', 'ket'),
			// 	'kolomCari' => array('tgl', 'uang_masuk', 'uang_keluar', 'saldo', 'ket'),
			// 	'orderBy' => array('id' => 'desc'),
			// 	'kondisi' => 'WHERE id = '.$data['id'].' ',
			// );

			// $dataMutasi = $this->Mutasi_bankModel->getAllDataTable($config_dataTable);

			// $data = array();
			// $no_urut = $_POST['start'];
			// foreach($dataMutasi as $row){
			// 	$no_urut++;
				
			// 	$dataRow = array();
			// 	$dataRow[] = $no_urut;
			// 	$dataRow[] = $row['tgl'];
			// 	$dataRow[] = $this->helper->cetakRupiah($row['uang_masuk']);
			// 	$dataRow[] = $this->helper->cetakRupiah($row['uang_keluar']);
			// 	$dataRow[] = $this->helper->cetakRupiah($row['saldo']);
			// 	$dataRow[] = $row['ket'];

			// 	$data[] = $dataRow;
			// }

			// $output = array(
			// 	'draw' => $_POST['draw'],
			// 	'recordsTotal' => $this->Mutasi_bankModel->recordTotal(),
			// 	'recordsFiltered' => $this->Mutasi_bankModel->recordFilter(),
			// 	'data' => $data,
			// );

			// echo json_encode($output);
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
			// $required = ($data['action'] == "action-edit") ? 'not_required' : 'required';

			// // nama bank
			// $this->validation->set_rules($data['nama'], 'Nama Bank', 'nama', 'string | 1 | 255 | required');
			// // saldo awal
			// $this->validation->set_rules($data['saldo'], 'Saldo Awal Bank', 'saldo', 'nilai | 0 | 99999999999 | '.$required);
			// // status
			// $this->validation->set_rules($data['status'], 'Status Bank', 'status', 'string | 1 | 255 | required');

			// return $this->validation->run();
		}

		/**
		*
		*/
		public function get_notif(){
			$notif = $this->Pengajuan_kasKecilModel->getAll_pending();
			$jumlah = $this->Pengajuan_kasKecilModel->getTotal_pending();

			$data_notif = '';
			foreach($notif as $value){
		        $data_notif .= '<li><a href="'.BASE_URL.'pengajuan-kas-kecil/detail/'.strtolower($value['id']).'">';
		        $data_notif .= '<strong>'.$value['id'].' - '.$value['nama_kas_kecil'].'</strong>';
		        $data_notif .= '</br>Total: '.$this->helper->cetakRupiah($value['total']); 
		        $data_notif .= '</a></li>';
			}

			$output = array(
				'notif' => $notif,
				'jumlah' => $jumlah,
				'text' => 'Anda memiliki '.$jumlah.' pengajuan yang masih Pending',
				'data' => $data_notif,
				'view_all' => BASE_URL.'pengajuan-kas-kecil/',
			);

			// echo "<pre>";
			// echo json_encode(print_r($output));
			echo json_encode($output);
		}
	}