<?php
	// namespace app\controllers;

	class Login extends Controller{

		protected $username = 'ABCD';
		protected $password = 'ABCD';
		protected $token;

		public function __construct(){
			$this->auth();
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
		*/
		private function loginSistem(){
			$user = isset($_POST['user']) ? $_POST['user'] : false;
			$pass = isset($_POST['pass']) ? $_POST['pass'] : false;

			// get username

			// cek username

			// cek jenis

			if(($user === $this->username) && ($pass === $this->password)){
				$_SESSION['sess_login'] = true;
				$_SESSION['sess_locksreen'] = false;

				$this->redirect(BASE_URL);
			}
			else{
				
			}
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
			$user = isset($_POST['user']) ? $_POST['user'] : false;
			$pass = isset($_POST['pass']) ? $_POST['pass'] : false;

			if(($user === $this->username) && ($pass === $this->password)){
				// echo "Berhasil Masuk Mobile(Token Baru)";
				// generate token

				$token = $this->auth->getToken();
				$status = true;
			}
			else{
				$token = null;
				$status = false;	
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
