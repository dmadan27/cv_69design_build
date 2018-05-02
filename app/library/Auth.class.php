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

		public function getToken(){
			$token = "";
		    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
		    $codeAlphabet.= "0123456789";
		    $max = strlen($codeAlphabet); // edited

		    for ($i=0; $i < 15; $i++) {
		        $token .= $codeAlphabet[$this->crypto_rand_secure(0, $max-1)];
		    }

		    return $token;
		}

		private function crypto_rand_secure($min, $max)
		{
		    $range = $max - $min;
		    if ($range < 1) return $min; // not so random...
		    $log = ceil(log($range, 2));
		    $bytes = (int) ($log / 8) + 1; // length in bytes
		    $bits = (int) $log + 1; // length in bits
		    $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
		    do {
		        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
		        $rnd = $rnd & $filter; // discard irrelevant bits
		    } while ($rnd > $range);
		    return $min + $rnd;
		}

	}