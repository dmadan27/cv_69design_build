<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	* 
	*/
	class Proyek extends Controller{
		
		/**
		* 
		*/
		public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
			$this->model('ProyekModel');
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
		private function list(){
			$css = array('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css');
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'app/views/proyek/js/initList.js',
			);

			$config = array(
				'title' => array(
					'main' => 'Data Proyek',
					'sub' => '',
				),
				'css' => $css,
				'js' => $js,
			);

			$data = array(
				'tokenCrsf' => password_hash($this->auth->getToken(), PASSWORD_BCRYPT),
			);

			$this->layout('proyek/list', $config, $data);
		}

		/**
		* 
		*/
		public function get_list(){

		}

		public function form(){

  			$css = array(
  				'assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
  				
  			);
			$js = array(
				'assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
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

		public function action_add(){
			$data = isset($_POST) ? $_POST : false;

			if(!$data){
				$notif = array(
					'title' => "Pesan Berhasil",
					'message' => "Tambah Data Proyek Berhasil",
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
					
			);
			echo jscon_encode($output);

		}
	}