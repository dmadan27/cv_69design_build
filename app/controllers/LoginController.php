<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
	// namespace app\controllers;

	/**
	* Class login. Untuk melakukan login ke sistem, lockscreen dan logout
	*/
	class Login extends Controller{

		protected $username;
		protected $password;
		protected $token;

		/**
		* Construct. Load class Auth
		*/
		public function __construct(){
			$this->auth();
			$this->validation();
			$this->model('UserModel');
		}

		/**
		* fungsi index untuk akses utama controller login
		*/
		public function index(){
			$jenis = isset($_POST['jenis']) ? $this->validation->validInput($_POST['jenis'], false) : false;

			// cek jenis login
			if($jenis && $jenis == 'sub-kas-kecil') $this->loginMobile(); // jika mobile
			else{ // jika sistem
				if($this->auth->isLogin()) $this->redirect(BASE_URL); // jika sudah login, tidak bisa akses
				else{ // jika belum login
					$_SESSION['sess_lockscreen'] = false;

					if($_SERVER['REQUEST_METHOD'] == "POST") $this->loginSistem(); // jika request post login
					else $this->view('login'); // jika bukan, atau hanya menampilkan halaman login
				}
			}
		}

		/**
		* fungsi login untuk sistem
		* pengecekan user dan password berdasarkan jenis user
		* pemberian hak akses berdasarkan level
		* set session default
		* return berupa json
		*/
		private function loginSistem($callback = false){
			$this->username = isset($_POST['username']) ? $this->validation->validInput($_POST['username'], false) : false;
			$this->password = isset($_POST['password']) ? $this->validation->validInput($_POST['password'], false) : false;

			$errorUser = $errorPass = "";
			$notif = '';

			// get username
			$dataUser = $this->UserModel->getUser($this->username);

			// cek username
			if(!$dataUser || $dataUser['level'] == 'SUB KAS KECIL' || $dataUser['status'] != 'AKTIF'){
				$status = false;
				$errorUser = "Username atau Password Anda Salah";
				$errorPass = $errorUser;
				$notif = array(
					'title' => "Pesan Pemberitahuan",
					'message' => "Silahkan Cek Kembali Form Isian",
				);
			}
			else{
				if(password_verify($this->password, $dataUser['password'])){
					$status = true;
					$this->setSession($dataUser['level']);
				}
				else{
					$status = false;
					$errorUser = "Username atau Password Anda Salah";
					$errorPass = $errorUser;
					$notif = array(
						'title' => "Pesan Pemberitahuan",
						'message' => "Silahkan Cek Kembali Form Isian",
					);
				}
			}

			// cek jenis

			// pesan error
			$error = array(
				'username' => $errorUser,
				'password' => $errorPass,
			);

			$output = array(
				'status' => $status,
				'callback' => $callback,
				'error' => $error,
				'notif' => $notif,
			);

			echo json_encode($output);
		}

		/**
		* Fungsi login untuk mobile
		* jika login baru, maka akan generate token baru dan disimpan ke db
		* token baru yg sudah digenerate akan di hash md5 dan bycrpt ke db
		* sedangkan yg dikirim ke mobile adalah hash md5
		* return fungsi berupa json
		*/
		private function loginMobile(){
			$this->auth->mobileOnly();
			$this->model('Sub_kas_kecilModel');

			$status = false;
			$token = null;

			// validasi pengguna
			$user = isset($_POST['username']) ? $this->validation->validInput($_POST['username'], false) : false;
			$pass = isset($_POST['password']) ? $this->validation->validInput($_POST['password'], false) : false;

			$dataUser = $this->UserModel->getUser($user);
			$id = null;

			// if(!$dataUser || $dataUser['level'] != 'SUB KAS KECIL'){
			// 	$token = null;
			// 	$status = false;
			// }
			if($dataUser && $dataUser['level'] == 'SUB KAS KECIL' && $dataUser['status'] == 'AKTIF'){
				$id = $this->UserModel->getSubKasKecil($user)['id'];
				if(password_verify($pass, $dataUser['password'])) {
					// generate token
					$token = md5($this->auth->getToken());
					$tokenSave = password_hash($token, PASSWORD_BCRYPT);
					$dataToken = array(
						'username' => $dataUser['username'],
						'token' => $tokenSave,
						'tgl_buat' => date('Y-m-d H:i:s'),
						'tgl_exp' => date('Y-m-d H:i:s', time()+(60*60*24*30)),
					);

					$this->model('TokenModel');

					if($this->TokenModel->setToken_mobile($dataToken)) $status = true;
					else $token = null;

					// // get data token lama dan hapus
					// $this->tokenModel->delete_mobile($dataUser['id']);

					// // tambah token baru
					// $this->tokenModel->insert_mobile($dataToken);
				}
			}

			// $output = array(
			// 	'id' => $id,
			// 	'token' => $token,
			// 	'status' => $status,
			// );

			$output = array(
				'status' => $status,
				'profil' => null,
			);
			if ($status) $output['profil'] = $this->Sub_kas_kecilModel->getByIdFromV($id);

			echo json_encode($output, JSON_PRETTY_PRINT);
		}

		/**
		* Fungsi lockscreen
		* set ulang session login dan session lockscreen saja
		*/
		public function lockscreen(){
			$lockscreen = isset($_SESSION['sess_lockscreen']) ? $_SESSION['sess_lockscreen'] : false;
			$callback = isset($_GET['callback']) ? $_GET['callback'] : false;

			if(!$lockscreen) $this->redirect(BASE_URL);
			else{
				if($_SERVER['REQUEST_METHOD'] == "POST") $this->loginSistem($callback); // jika request post login
				else $this->view('lockscreen'); // jika bukan, atau hanya menampilkan halaman login
			}
		}

		/**
		*
		*/
		private function setSession($level){
			// set data profil sesuai dgn jenis user
			if(strtolower($level) == 'kas besar')
				$dataProfil = $this->UserModel->getKasBesar($this->username);
			else if(strtolower($level) == 'kas kecil') {
				$dataProfil = $this->UserModel->getKasKecil($this->username);
				$_SESSION['sess_saldo'] = $dataProfil['saldo'];
			}
			else if(strtolower($level) == 'owner')
				$dataProfil = $this->UserModel->getOwner($this->username);

			// cek kondisi foto
			if(!empty($dataProfil['foto'])){
				// cek foto di storage
				$filename = ROOT.DS.'assets'.DS.'images'.DS.'user'.DS.$dataProfil['foto'];
				if(!file_exists($filename))
					$foto = BASE_URL.'assets/images/user/default.jpg';
				else
					$foto = BASE_URL.'assets/images/user/'.$dataProfil['foto'];
			}
			else $foto = BASE_URL.'assets/images/user/default.jpg';

			$_SESSION['sess_login'] = true;
			$_SESSION['sess_lockscreen'] = false;
			$_SESSION['sess_level'] = $level;
			$_SESSION['sess_id'] = $dataProfil['id'];
			$_SESSION['sess_nama'] = $dataProfil['nama'];
			$_SESSION['sess_alamat'] = $dataProfil['alamat'];
			$_SESSION['sess_telp'] = $dataProfil['no_telp'];
			$_SESSION['sess_email'] = $dataProfil['email'];
			$_SESSION['sess_foto'] = $foto;
			$_SESSION['sess_status'] = $dataProfil['status'];
			$_SESSION['sess_welcome'] = true;
			$_SESSION['sess_timeout'] = date('Y-m-d H:i:s', time()+(60*60)); // 1 jam idle
			// $_SESSION['sess_akses'] = '';
		}

		/**
		*
		*/
		private function setAkses($level){

		}

		/**
		* Fungsi logout
		* menghapus semua session yang ada
		*/
		public function logout(){
			session_unset();
			session_destroy();

			$this->redirect(BASE_URL);
		}
	}
