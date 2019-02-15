<?php
	Defined("BASE_PATH") or die(ACCESS_DENIED);

	/**
	 * 
	 */
	class Profil extends Controller
	{

		private $success = false;
		private $notif = array();
		private $error = array();
		private $message = NULL;

		/**
		 * 
		 */
		public function __construct() {
			$this->auth();
			$this->auth->cekAuth();
			$this->model('UserModel');
			$this->helper();
			$this->validation();
		}

		/**
		 * 
		 */
		public function index() {
			$this->detail();
		}

		/**
		 * 
		 */
		private function detail() {
			$css = array(
				'assets/bower_components/Magnific-Popup-master/dist/magnific-popup.css',
				'assets/bower_components/dropify/dist/css/dropify.min.css'
			);
			$js = array(
				'assets/bower_components/Magnific-Popup-master/dist/jquery.magnific-popup.min.js',
				'assets/bower_components/dropify/dist/js/dropify.min.js',
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
		public function edit() {
			$username = $_SESSION['sess_email'];

			switch ($_SESSION['sess_level']) {
				case 'KAS BESAR':
					$data = !empty($this->UserModel->getKasBesar($username)) ? 
						$this->UserModel->getKasBesar($username) : false;
					break;

				case 'KAS KECIL':
					$data = !empty($this->UserModel->getKasKecil($username)) ? 
						$this->UserModel->getKasKecil($username) : false;
					break;

				case 'OWNER':
					$data = !empty($this->UserModel->getOwner($username)) ? 
						$this->UserModel->getOwner($username) : false;
					break;
				
				default:
					die();
					break;
			}

			$data = array(
				'nama' => $data['nama'],
				'alamat' => $data['alamat'],
				'no_telp' => $data['no_telp'],
			);

			echo json_encode($data);
		}

		/**
		 * 
		 */
		public function action_edit() {
			$id = $_SESSION['sess_id'];
			$data = isset($_POST) ? $_POST : false;
			$error = $notif = array();

			switch ($_SESSION['sess_level']) {
				case 'KAS BESAR':
					$this->model('Kas_besarModel');
					$model = $this->Kas_besarModel;

					break;

				case 'KAS KECIL':
					$this->model('Kas_kecilModel');
					$model = $this->Kas_kecilModel;

					break;

				case 'OWNER':
					$this->model('OwnerModel');
					$model = $this->OwnerModel;
		
					break;
				
				default:
					die();
					break;
			}

			if(!$data) {
				$this->notif = array(
					'type' => "error",
					'title' => "Pesan Gagal",
					'message' => "Terjadi Kesalahan Teknis, Silahkan Coba Kembali",
				);
			}
			else{
				// validasi data
				$validasi = $this->set_validation($data);
				$cek = $validasi['cek'];
				$this->error = $validasi['error'];

				if($cek ){
					$data = array(
						'id' => $id,
						'nama' => $this->validation->validInput($data['nama']),
						'alamat' => $this->validation->validInput($data['alamat']),
						'no_telp' => $this->validation->validInput($data['no_telp']),
						'modified_by' => $_SESSION['sess_email']
					);

					// update profil
					$update = $model->updateProfil($data);
					if($update['success']) {
						$this->success = true;
						
						$_SESSION['sess_nama'] = $data['nama'];
						$_SESSION['sess_alamat'] = $data['alamat'];
						$_SESSION['sess_telp'] = $data['no_telp'];

						$this->notif = array(
							'type' => "success",
							'title' => "Pesan Berhasil",
							'message' => "Edit Data Profil Berhasil",
						);
					}
					else {
						$this->message = $update['error'];
						$this->notif = array(
							'type' => "error",
							'title' => "Pesan Gagal",
							'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
						);
					}
				}
				else {
					$this->notif = array(
						'type' => "warning",
						'title' => "Pesan Pemberitahuan",
						'message' => "Silahkan Cek Kembali Form Isian",
					);
				}
			}

			$output = array(
				'status' => $this->success,
				'notif' => $this->notif,
				'error' => $this->error,
				'message' => $this->message
			);

			echo json_encode($output);
		}

		/**
		 * 
		 */
		public function edit_foto() {
			$id = $_SESSION['sess_id'];
			$foto = isset($_FILES['foto']) ? $_FILES['foto'] : false;

			$status_upload = $status_hapus = false;

			switch ($_SESSION['sess_level']) {
				case 'KAS BESAR':
					$this->model('Kas_besarModel');
					$model = $this->Kas_besarModel;
					$fotoLama = (!empty($model->getById($id)['foto']) 
									|| $model->getById($id)['foto'] != '') 
										? ROOT.DS.'assets'.DS.'images'.DS.'user'.DS.$model->getById($id)['foto'] : false;
					break;

				case 'KAS KECIL':
					$this->model('Kas_kecilModel');
					$model = $this->Kas_kecilModel;
					$fotoLama = (!empty($model->getById($id)['foto']) 
									|| $model->getById($id)['foto'] != '') 
										? ROOT.DS.'assets'.DS.'images'.DS.'user'.DS.$model->getById($id)['foto'] : false;
					break;

				case 'OWNER':
					$this->model('OwnerModel');
					$model = $this->OwnerModel;
					$fotoLama = (!empty($model->getById($id)['foto']) 
									|| $model->getById($id)['foto'] != '') 
										? ROOT.DS.'assets'.DS.'images'.DS.'user'.DS.$model->getById($id)['foto'] : false;
					break;
				
				default:
					die();
					break;
			}

			// validasi foto
			if($foto) {
				$configFoto = array(
					'jenis' => 'gambar',
					'error' => $foto['error'],
					'size' => $foto['size'],
					'name' => $foto['name'],
					'tmp_name' => $foto['tmp_name'],
					'max' => 2*1048576,
				);
				$validasiFoto = $this->validation->validFile($configFoto);
				if(!$validasiFoto['cek']){
					$cek = false;
					$this->error['foto'] = $validasiFoto['error'];
					$this->notif = array(
						'type' => "warning",
						'title' => "Pesan Pemberitahuan",
						'message' => "Silahkan Cek Kembali Form Isian",
					);
				}
				else {
					$cek = true;
					$fotoBaru = md5($id).$validasiFoto['namaFile'];
				}
			}
			else{
				$this->error['foto'] = 'Anda Belum Memilih Foto';
				$this->notif = array(
					'type' => "warning",
					'title' => "Pesan Pemberitahuan",
					'message' => "Silahkan Cek Kembali Form Isian",
				);
				$cek = false;
			}

			// cek validasi
			if($cek) {
				// upload foto ke server
				$path = ROOT.DS.'assets'.DS.'images'.DS.'user'.DS.$fotoBaru;
				if(!move_uploaded_file($foto['tmp_name'], $path)) {
					$this->error['foto'] = "Upload Foto Gagal";
				}
				else { $status_upload = true; }

				if($status_upload) {
					// update db
					$update = $model->updateFoto(array(
						'id' => $id, 'foto' => $fotoBaru, 'modified_by' => $_SESSION['sess_email']
					));
					if($update['success']) { $status_hapus = true; }
					else { 
						$this->message = $update['error'];
						$this->helper->rollback_file($path); 
					}
				}

				if($status_hapus){
					if($fotoLama && file_exists($fotoLama)) { unlink($fotoLama); }

					$this->success = true;
					$this->notif = array(
						'type' => "success",
						'title' => "Pesan Berhasil",
						'message' => "Foto Profil Anda Berhasil Diganti",
					);
					$_SESSION['sess_foto'] = BASE_URL.'assets/images/user/'.$fotoBaru; 
				}
			}

			$output = array(
				'status' => $this->success,
				'error' => $this->error,
				'notif' => $this->notif,
				'message' => $this->message,
				'foto' => $foto,
			);

			echo json_encode($output);
		}

		/**
		 * 
		 */
		public function hapus_foto() {
			$id = $_SESSION['sess_id'];
			$notif = array();

			switch ($_SESSION['sess_level']) {
				case 'KAS BESAR':
					$this->model('Kas_besarModel');
					$model = $this->Kas_besarModel;
					$fotoLama = (!empty($this->Kas_besarModel->getById($id)['foto']) 
									|| $this->Kas_besarModel->getById($id)['foto'] != '') 
										? ROOT.DS.'assets'.DS.'images'.DS.'user'.DS.$this->Kas_besarModel->getById($id)['foto'] : false;
					break;

				case 'KAS KECIL':
					$this->model('Kas_kecilModel');
					$model = $this->Kas_kecilModel;
					$fotoLama = (!empty($model->getById($id)['foto']) 
									|| $model->getById($id)['foto'] != '') 
										? ROOT.DS.'assets'.DS.'images'.DS.'user'.DS.$model->getById($id)['foto'] : false;
					break;

				case 'OWNER':
					$this->model('OwnerModel');
					$model = $this->OwnerModel;
					$fotoLama = (!empty($model->getById($id)['foto']) 
									|| $model->getById($id)['foto'] != '') 
										? ROOT.DS.'assets'.DS.'images'.DS.'user'.DS.$model->getById($id)['foto'] : false;
					break;
				
				default:
					die();
					break;
			}

			// update foto jadikan null
			$update = $model->updateFoto(array(
				'id' => $id, 'foto' => null, 'modified_by' => $_SESSION['sess_email']
			));
			if($update['success']) {
				// hapus foto lama
				if($fotoLama) {
					if(file_exists($fotoLama)) {
						if(unlink($fotoLama)) {
							$this->notif = array(
								'title' => "Pesan Berhasil",
								'message' => "Foto Berhasil Dihapus",
								'type' => 'success',
							);
							$this->success = true;
							$_SESSION['sess_foto'] = BASE_URL.'assets/images/user/default.jpg';	
						}
						else {
							$model->updateFoto(array(
								'id' => $id, 'foto' => $fotoLama, 'modified_by' => $_SESSION['sess_email']
							));
							$this->notif = array(
								'title' => "Pesan Gagal",
								'message' => "Foto Gagal Dihapus",
								'type' => 'error',
							);
						}
					}
					else {
						$model->updateFoto(array('id' => $id, 'foto' => null, 'modified_by' => $_SESSION['sess_email']));
						$this->success = true;
						$_SESSION['sess_foto'] = BASE_URL.'assets/images/user/default.jpg';
					}		
				}
				else{
					$this->success = true;
					$this->notif = array(
						'title' => "Pesan Pemberitahuan",
						'message' => "Tidak Ada Foto yang Dihapus",
						'type' => 'warning',
					);
				}
			}

			$output = array(
				'status' => $this->success,
				'notif' => $notif,
				'message' => $this->message
			);

			echo json_encode($output);
		}

		/**
		 * 
		 */
		public function ganti_password() {
			if($_SERVER['REQUEST_METHOD'] == "POST") {

				$username = isset($_SESSION['sess_email']) ? $this->validation->validInput($_SESSION['sess_email'], false) : false;
				$password_lama = isset($_POST['password_lama']) ? $this->validation->validInput($_POST['password_lama'], false) : false;
				$password_baru = isset($_POST['password_baru']) ? $this->validation->validInput($_POST['password_baru'], false) : false;
				$password_konf = isset($_POST['password_konf']) ? $this->validation->validInput($_POST['password_konf'], false) : false;

				$error = $notif = array();

				$validasi = $this->set_validation_ganti_password(
					$data = array(
						'password_lama' => $password_lama,
						'password_baru' => $password_baru, 
						'password_konf' => $password_konf
					)
				);

				$cek = $validasi['cek'];
				$this->error = $validasi['error'];

				$verify_password = $this->UserModel->getById($username)['password'];

				if(password_verify($password_lama, $verify_password)) {

					if($password_baru !== $password_konf) {
						$cek = false;
						$this->error['password_baru'] = $this->error['password_konf'] = 'Konfirmasi Password dan Password Baru Tidak Sama !';
					}

					if($cek) {
						$data = array(
							'username' => $username,
							'password' => password_hash($password_baru, PASSWORD_BCRYPT),
						);

						if($this->UserModel->updatePassword($data)) {
							$this->success = true;
							$this->notif = array(
								'type' => "success",
								'title' => "Pesan Berhasil",
								'message' => "Password Anda Berhasil di Ganti",
							);
						}
						else {
							$this->notif = array(
								'type' => "error",
								'title' => "Pesan Gagal",
								'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
							);
						}
					}
					else {
						$this->notif = array(
							'type' => "warning",
							'title' => "Pesan Pemberitahuan",
							'message' => "Silahkan Cek Kembali Form Isian",
						);
					}

				}
				else{
					$this->error['password_lama'] = 'Password Lama Anda Salah';
					$this->notif = array(
						'type' => "warning",
						'title' => "Pesan Pemberitahuan",
						'message' => "Silahkan Cek Kembali Form Isian",
					);
				}

				$output = array(
					'status' => $this->success,
					'notif' => $this->notif,
					'error' => $this->error,
					// 'data' => $data
				);

				echo json_encode($output);

			}
			else { $this->redirect(); }

		}

		/**
		 * 
		 */
		private function set_validation($data) {
			// nama
			$this->validation->set_rules($data['nama'], 'Nama', 'nama', 'string | 1 | 255 | required');
			// alamat
			$this->validation->set_rules($data['alamat'], 'Alamat', 'alamat', 'string | 1 | 255 | required');
			// no telp
			$this->validation->set_rules($data['no_telp'], 'No. Telepon', 'no_telp', 'string | 1 | 255 | required');

			return $this->validation->run();
		}

		/**
		 * 
		 */
		private function set_validation_ganti_password($data) {
			// password lama
			$this->validation->set_rules($data['password_lama'], 'Password Lama', 'password_lama', 'string | 5 | 255 | required');
			// password baru
			$this->validation->set_rules($data['password_baru'], 'Password Baru', 'password_baru', 'string | 5 | 255 | required');
			// password konf
			$this->validation->set_rules($data['password_konf'], 'Konfirmasi Password', 'password_konf', 'string | 5 | 255 | required');

			return $this->validation->run();
		}

	}