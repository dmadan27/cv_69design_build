<?php
	// namespace app\controllers;

	class Login extends Controller{

		protected $username = 'ABCD';
		protected $password = 'ABCD';
		protected $token;
		// protected $logout = "ABCD";

		public function __construct(){
			$this->auth();
			if($this->auth->isLogin()){
				$this->redirect(BASE_URL);
			}
		}

		public function index(){
			$jenis = isset($_POST['jenis']) ? $_POST['jenis'] : false;
			$token = isset($_POST['token']) ? $_POST['token'] : false;

			// cek jenis login
			if($jenis) $this->login_mobile($token);
			else{
				if($_SERVER['REQUEST_METHOD'] == "POST") $this->loginSistem();
				else $this->view('login');
			}
		}

		private function loginMobile($token){
			// get token di db

			// cek token
			if (($token == "") || ($token !== $this->token)) {
				// validasi pengguna
				$user = isset($_POST['user']) ? $_POST['user'] : false;
				$pass = isset($_POST['pass']) ? $_POST['pass'] : false;

				if(($user === $this->username) && ($pass === $this->password)){
					echo "Berhasil Masuk Mobile(Token Baru)";
				}
				else{
					echo "Gagal Masuk Mobile";
				}

			}
			else {
				echo "Berhasil Masuk";
			}
		}

		private function loginSistem(){
			$user = isset($_POST['user']) ? $_POST['user'] : false;
			$pass = isset($_POST['pass']) ? $_POST['pass'] : false;

			if(($user === $this->username) && ($pass === $this->password)){
				// set session
				echo "Berhasil Masuk Sistem";

				$_SESSION['sess_login'] = true;
				$_SESSION['sess_locksreen'] = false;
			}
			else{
				echo "Gagal Masuk Sistem";
			}
		}

		public function logout(){
			session_start();
			session_unset();
			session_destroy();

			$this->redirect(BASE_URL);
		}
	}
