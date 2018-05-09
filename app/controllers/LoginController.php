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
			$this->model('UserModel');
		}

		/**
		* fungsi index untuk akses utama controller login
		*/
		public function index(){
			$jenis = isset($_POST['jenis']) ? $_POST['jenis'] : false;

			// cek jenis login
			if($jenis) $this->loginMobile(); // jika mobile
			else{ // jika sistem
				if($this->auth->isLogin()) $this->redirect(BASE_URL); // jika sudah login, tidak bisa akses
				else{ // jika belum login
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
		private function loginSistem(){
			$this->username = isset($_POST['username']) ? $_POST['username'] : false;
			$this->password = isset($_POST['password']) ? $_POST['password'] : false;

			$errorUser = $errorPass = "";

			// get username
			$dataUser = $this->UserModel->getUser($this->username);

			// cek username
			if(!$dataUser){
				$status = false;
				$errorUser = "Username atau Password Anda Salah";
				$errorPass = $errorUser;
			}
			else{
				if(password_verify($this->password, $dataUser['password'])){
					$status = true;
					$_SESSION['sess_login'] = true;
					$_SESSION['sess_locksreen'] = false;
					$_SESSION['sess_level'] = $dataUser['level'];

					// set data profil sesuai dgn jenis user
					if(strtolower($dataUser['level']) == 'kas besar') 
						$dataProfil = $this->UserModel->getKasBesar($this->username);
					else 
						$dataProfil = $this->UserModel->getKasKecil($this->username);

					$_SESSION['sess_id'] = $dataProfil['id'];
					$_SESSION['sess_nama'] = $dataProfil['nama'];
					$_SESSION['sess_alamat'] = $dataProfil['alamat'];
					$_SESSION['sess_email'] = $dataProfil['email'];
					$_SESSION['sess_foto'] = $dataProfil['foto'];
					$_SESSION['sess_status'] = $dataProfil['status'];
				}
				else{
					$status = false;
					$errorUser = "Username atau Password Anda Salah";
					$errorPass = $errorUser;
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
				'error' => $error,
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

			// validasi pengguna
			$user = isset($_POST['username']) ? $_POST['username'] : false;
			$pass = isset($_POST['password']) ? $_POST['password'] : false;

			// get user
			$this->model('sub_kas_kecilModel');
			$dataUser = $this->sub_kas_kecilModel->getUser($user);

			if(!$dataUser){
				$token = null;
				$status = false;
			}
			else{
				// if(password_verify($pass, $dataUser['password'])) {
				if($pass == $dataUser['password']) {
					$status = true;
					
					// generate token
					$token = md5($this->auth->getToken());
					$tokenSave = password_hash($token, PASSWORD_BCRYPT);
					$dataToken = array(
						'id_sub_kas_kecil' => $dataUser['id'],
						'token' => $tokenSave,
						'tgl_buat' => date('Y-m-d H:i:s'),
						'tgl_exp' => date('Y-m-d H:i:s', time()+(60*60*24*30)),
					);

					$this->model('tokenModel');
					
					// get data token lama dan hapus
					$this->tokenModel->delete($dataUser['id']);

					// tambah token baru
					$this->tokenModel->insert($dataToken);	
				}
				else{
					$token = null;
					$status = false;
				}
			}

			$output = array(
				'token' => $token,
				'status' => $status,
			);

			echo json_encode($output);
		}

		/**
		* Fungsi lockscreen
		* set ulang session login dan session lockscreen saja
		*/
		private function lockscreen(){

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
