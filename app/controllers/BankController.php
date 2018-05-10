<?php 
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	class Bank extends Crud_modalsAbstract{

		/**
		*
		*/
		public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
			$this->model('BankModel');
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
			// set config untuk layouting
			$css = array(
				'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
				'assets/bower_components/toastr/build/toastr.min.css',
			);
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'assets/bower_components/toastr/build/toastr.min.js',
				'app/views/bank/js/initList.js',
				'app/views/bank/js/initForm.js',
			);

			$config = array(
				'title' => array(
					'main' => 'Data Bank',
					'sub' => '',
				),
				'css' => $css,
				'js' => $js,
			);
			
			// set token list
			$token = md5($this->auth->getToken()); // md5
			$_SESSION['token_bank_list'] = $token; // md5 di hash
			$data = array(
				'token_bank_list' => password_hash($token, PASSWORD_BCRYPT),
			);

			$this->layout('bank/list', $config, $data);
		}	

		/**
		*
		*/
		public function get_list(){
			$token = isset($_POST['token_bank_list']) ? $_POST['token_bank_list'] : false;

			// cek token
			if(!password_verify($_SESSION['token_bank_list'], $token)) $this->redirect(BASE_URL.'bank/');
			else{
				// config datatable
				$config_dataTable = array(
					'tabel' => 'bank',
					'kolomOrder' => array(null, 'nama', 'saldo', 'status', null),
					'kolomCari' => array('nama', 'saldo', 'status'),
					'orderBy' => array('id' => 'asc'),
					'kondisi' => false,
				);

				$dataBank = $this->BankModel->getAllDataTable($config_dataTable);

				// set token
				// $token_tambah = md5($this->auth->getToken()); // md5
				// $_SESSION['token_bank_tambah'] = $token_tambah; // md5 di hash

				$token_edit = md5($this->auth->getToken()); // md5
				$_SESSION['token_bank_edit'] = $token_edit; // md5 di hash

				$token_hapus = md5($this->auth->getToken()); // md5
				$_SESSION['token_bank_hapus'] = $token_hapus; // md5 di hash

				$data = array();
				$no_urut = $_POST['start'];
				foreach($dataBank as $row){
					$no_urut++;

					$status = ($row['status'] == "AKTIF") ? '<span class="label label-success">'.$row['status'].'</span>' : '<span class="label label-danger">'.$row['status'].'</span>';

					// button aksi
					$aksiDetail = '<button onclick="getView('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-info btn-flat" title="Lihat Detail"><i class="fa fa-eye"></i></button>';
					$aksiEdit = '<button onclick="getEdit('."'".$row["id"]."'".', '."'".$token_edit."'".')" type="button" class="btn btn-sm btn-success btn-flat" title="Edit Data"><i class="fa fa-pencil"></i></button>';
					$aksiHapus = '<button onclick="getDelete('."'".$row["id"]."'".', '."'".$token_hapus."'".')" type="button" class="btn btn-sm btn-danger btn-flat" title="Hapus Data"><i class="fa fa-trash"></i></button>';
					
					$aksi = '<div class="btn-group">'.$aksiDetail.$aksiEdit.$aksiHapus.'</div>';
					
					$dataRow = array();
					$dataRow[] = $no_urut;
					$dataRow[] = $row['nama'];
					$dataRow[] = $this->helper->cetakRupiah($row['saldo']);
					$dataRow[] = $status;
					$dataRow[] = $aksi;

					$data[] = $dataRow;
				}

				$output = array(
					'draw' => $_POST['draw'],
					'recordsTotal' => $this->BankModel->recordTotal(),
					'recordsFiltered' => $this->BankModel->recordFilter(),
					'data' => $data,
				);

				echo json_encode($output);
			}			
		}

		/**
		*
		*/
		public function action_add(){
			$data = isset($_POST) ? $_POST : false;
			$status = false;

			if(!$data){
				$status = false;
				$error = "";
			}
			else{
				// validasi data
				$validasi = $this->set_validation($data);
				$cek = $validasi['cek'];
				$error = $validasi['error'];

				if($cek){
					// validasi inputan
					$data = array(
						'nama' => $this->validation->validInput($data['nama']),
						'saldo' => $this->validation->validInput($data['saldo']),
					);

					// insert db

					// transact

					if($this->BankModel->insert($data)) {
						$status = true;
						$notif = array(
							'title' => "Pesan Berhasil",
							'message' => "Tambah Data Bank Baru Berhasil",
						);
					}
					else {
						$status = false;
						$notif = array(
							'title' => "Pesan Gagal",
							'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
						);
					}

					// commit
				}
				else {
					$status = false;
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
			);

			echo json_encode($output);
		}

		/**
		*
		*/
		private function set_validation($data){
			$required = ($data['action'] == "edit") ? 'not_required' : 'required';

			// nama bank
			$this->validation->set_rules($data['nama'], 'Nama Bank', 'nama', 'string | 1 | 255 | required');
			// saldo awal
			$this->validation->set_rules($data['saldo'], 'Saldo Awal Bank', 'saldo', 'nilai | 0 | 99999999999 | '.$required);

			return $this->validation->run();
		}

		/**
		*
		*/
		public function edit($id){

		}

		/**
		*
		*/
		public function action_edit(){

		}

		/**
		*
		*/
		public function detail($id){
			echo "Halaman View";
		}

		/**
		*
		*/
		public function detele($id){

		}

		/**
		*
		*/
		public function export(){

		}

	}