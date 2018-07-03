<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	*
	*/
	class Profil extends Controller{

		protected $status = false;

		/**
		*
		*/
		public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
			$this->model('UserModel');
			$this->helper();
			$this->validation();
		}

		/**
		*
		*/
		public function index(){
			$this->detail();
		}

		/**
		*
		*/
		private function detail(){
			$css = array('assets/bower_components/Magnific-Popup-master/dist/magnific-popup.css');
			$js = array(
				'assets/bower_components/Magnific-Popup-master/dist/jquery.magnific-popup.min.js',
				'app/views/profil/js/init.js'
			);

			$config = array(
				'title' => array(
					'main' => 'Profil',
					'sub' => 'Detail Data Profil User',
				),
				'css' => $css,
				'js' => $js,
			);

			$saldo = isset($_SESSION['sess_saldo']) ? $this->helper->cetakRupiah($_SESSION['sess_saldo']) : false;

			$data = array(
				'id' => $_SESSION['sess_id'],
				'nama' => $_SESSION['sess_nama'],
				'alamat' => $_SESSION['sess_alamat'],
				'no_telp' => $_SESSION['sess_telp'],
				'email' => $_SESSION['sess_email'],
				'foto' => $_SESSION['sess_foto'],
				'saldo' => $saldo,
				'status' => $_SESSION['sess_status'],
				'level' => $_SESSION['sess_level']
			);

			$this->layout('profil/view', $config, $data);
		}

		/**
		*
		*/
		public function edit(){
			$username = $_SESSION['sess_email'];

			switch ($_SESSION['sess_level']) {
				case 'KAS BESAR':
					$data = !empty($this->UserModel->getKasBesar($username)) ? $this->UserModel->getKasBesar($username) : false;
					break;

				case 'KAS KECIL':
					$data = !empty($this->UserModel->getKasKecil($username)) ? $this->UserModel->getKasKecil($username) : false;
					break;

				case 'OWNER':
					$data = !empty($this->UserModel->getOwner($username)) ? $this->UserModel->getOwner($username) : false;
					break;
				
				default:
					die();
					break;
			}

			echo json_encode($data);
		}

		/**
		*
		*/
		public function action_edit(){

		}

		/**
		*
		*/
		public function ganti_password(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){

				$username = isset($_SESSION['sess_email']) ? $this->validation->validInput($_SESSION['sess_email'], false) : false;
				$password_lama = isset($_POST['password_lama']) ? $this->validation->validInput($_POST['password_lama'], false) : false;
				$password_baru = isset($_POST['password_baru']) ? $this->validation->validInput($_POST['password_baru'], false) : false;
				$password_konf = isset($_POST['password_konf']) ? $this->validation->validInput($_POST['password_konf'], false) : false;

				$error = $notif = '';

				$validasi = $this->set_validation_ganti_password(
					$data = array(
						'password_lama' => $password_lama,
						'password_baru' => $password_baru, 
						'password_konf' => $password_konf
					)
				);

				$cek = $validasi['cek'];
				$error = $validasi['error'];

				$verify_password = $this->UserModel->getById($username)['password'];

				if(password_verify($password_lama, $verify_password)){

					if($password_baru !== $password_konf){
						$cek = false;
						$error['password_baru'] = $error['password_konf'] = 'Konfirmasi Password dan Password Baru Tidak Sama !';
					}

					if($cek){
						$data = array(
							'username' => $username,
							'password' => password_hash($password_baru, PASSWORD_BCRYPT),
						);

						if($this->UserModel->updatePassword($data)){
							$this->status = true;
							$notif = array(
								'title' => "Pesan Berhasil",
								'message' => "Password Anda Berhasil di Ganti",
							);
						}
						else{
							$notif = array(
								'title' => "Pesan Gagal",
								'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
							);
						}
					}
					else{
						$notif = array(
							'title' => "Pesan Pemberitahuan",
							'message' => "Silahkan Cek Kembali Form Isian",
						);
					}

				}
				else{
					$error['password_lama'] = 'Password Lama Anda Salah';
					$notif = array(
						'title' => "Pesan Pemberitahuan",
						'message' => "Silahkan Cek Kembali Form Isian",
					);
				}

				$output = array(
					'status' => $this->status,
					'notif' => $notif,
					'error' => $error,
					// 'data' => $data
				);

				echo json_encode($output);

			}
			else $this->redirect();

		}

		/**
		*
		*/
		private function set_validation($data){


			return $this->validation->run();
		}

		/**
		*
		*/
		private function set_validation_ganti_password($data){
			// password lama
			$this->validation->set_rules($data['password_lama'], 'Password Lama', 'password_lama', 'string | 5 | 255 | required');
			// password baru
			$this->validation->set_rules($data['password_baru'], 'Password Baru', 'password_baru', 'string | 5 | 255 | required');
			// password konf
			$this->validation->set_rules($data['password_konf'], 'Konfirmasi Password', 'password_konf', 'string | 5 | 255 | required');

			return $this->validation->run();
		}

	}