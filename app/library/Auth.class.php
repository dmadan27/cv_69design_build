<?php
	/**
	* Class Auth, Pengecekan Authentikasi yg masuk sistem
	*/
	class Auth{
		
		protected $login;
		protected $lockscreen;
		protected $jenis;
		protected $token = 'ABCD';

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
			$this->jenis = isset($_POST['jenis']) ? $_POST['jenis'] : false;
			$this->login = isset($_SESSION['sess_login']) ? $_SESSION['sess_login'] : false;
			// $this->lockscreen = isset($_SESSION['sess_locksreen']) ? $_SESSION['sess_locksreen'] : false;

			if($this->jenis){
				$token = isset($_POST['token']) ? $_POST['token'] : false;

				// get token di db

				if (($token == "") || ($token !== $this->token)) return false;
				else if(($token != "") && ($token === $this->token)) return true;
			}
			else{
				if(!$this->login) return false;
				else return true;
			}
				
		}

		public function cekAuthMobile(){
			$this->jenis = isset($_POST['jenis']) ? $_POST['jenis'] : false;
			if($this->jenis){
				$status = $this->isLogin() ? true : false;

				$output = array(
					'status' => $status,
				);

				echo json_encode($output);
			}
			else die();

		}

		private function getAkses($user){

		}

		private function getToken(){

		}
	}