<?php
	/**
	* Class Auth, Pengecekan Authentikasi yg masuk sistem
	*/
	class Auth{
		
		protected $login;
		protected $lockscreen;
		protected $jenis;

		public function __construct(){
			// $this->jenis = isset($_POST['jenis']) ? $_POST['jenis'] : false;

			// if(($this->jenis) && (strtolower($this->jenis) === 'mobile')) $this->cekAuthMobile();
			// else $this->cekAuth();
		}

		public function cekAuth(){
			if(!$this->isLogin()){
				session_unset();
				session_destroy();
				header('Location: '.BASE_URL.'login');
				die();
			}
			// else{
			// 	header('Location: '.BASE_URL);
			// 	die();
			// }
		}

		public function isLogin(){
			$this->login = isset($_SESSION['sess_login']) ? $_SESSION['sess_login'] : false;
			// $this->lockscreen = isset($_SESSION['sess_locksreen']) ? $_SESSION['sess_locksreen'] : false;

			if(!$this->login) return false;
			else return true;
		}

		private function cekAuthMobile(){

		}

		private function getAkses($user){

		}

		private function getToken(){

		}
	}