<?php
Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
class Kas_kecil extends CrudAbstract{

	protected $token;

	public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
			$this->model('Kas_kecilModel');
			$this->helper();	
			$this->validation();
	}	


	public function index(){
			$this->list();
		}


	protected function list(){
			// $this->auth->cekAuth();
			$css = array('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css');
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'app/views/kas_kecil/js/initList.js',
			);

			$config = array(
				'title' => array(
					'main' => 'Data Kas Kecil',
					'sub' => 'Ini adalah halaman Kas Kecil, yang mengandung data Kas Kecil',
				),
				'css' => $css,
				'js' => $js,
			);

			$data = $this->Kas_kecilModel->getAll();
			
			$this->layout('kas_kecil/list', $config, $data);
		}	


		public function form($id){
			$id = isset($_GET['id']) ? $_GET['id'] : false;

			// cek jenis form
			if(!$id) $this->add();
			else $this->edit($id);
		}
		public function get_list(){

		}

		protected function add(){
			$css = array(
  				'assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
				'assets/bower_components/select2/dist/css/select2.min.css',
  				
  			);
			$js = array(
				'assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
				'assets/bower_components/select2/dist/js/select2.full.min.js',
				
				
			);


			$config = array(
				'title' => array(
					'main' => 'Data Kas Kecil',
					'sub' => '',
				),
				'css' => $css,
				'js' => $js,
			);

			// $_SESSION['token_kas_kecil'] = array(
			// 	'add' => md5($this->auth->getToken()),
			// );
			// $this->token = array(
			// 	'add' => password_hash($_SESSION['token_kas_kecil']['add'], PASSWORD_BCRYPT),	
			// );
			$data = array(
				'token_add' => $this->token['add'],
				'action' => "action-add",
			);

			$this->layout('kas_kecil/form', $config);
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
		public function export(){

		}

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

}