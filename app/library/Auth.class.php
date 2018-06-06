<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
	
	/**
	* Class Auth, Pengecekan Authentikasi yg masuk sistem
	*/
	class Auth{
		
		protected $login;
		protected $lockscreen;
		protected $jenis;
		protected $token;

		/**
		* Fungsi cek auth sistem
		* Untuk mengecek status user sudah login atau belum
		* jika belum login maka akan diarahkan ke login
		*/
		public function cekAuth(){
			if(!$this->isLogin()){
				session_unset();
				session_destroy();
				header('Location: '.BASE_URL.'login');
				die();
			}
		}

		/**
		* Fungsi cek auth mobile
		* untuk mengecek status user mobile sudah login atau belum
		* mengecek expired token
		*/
		public function cekAuthMobile(){
			$this->mobileOnly();
			// $this->jenis = isset($_POST['jenis']) ? $_POST['jenis'] : false;
			// if($this->jenis){
			return $status = $this->isLogin() ? true : false;

			// $output = array(
			// 	'status' => $status,
			// );

			// echo json_encode($output);
			// }
		}

		/**
		* pengecekan status login untuk sistem dan mobile
		*/
		public function isLogin(){
			$this->jenis = isset($_POST['jenis']) ? $_POST['jenis'] : false;
			$this->login = isset($_SESSION['sess_login']) ? $_SESSION['sess_login'] : false;
			// $this->lockscreen = isset($_SESSION['sess_locksreen']) ? $_SESSION['sess_locksreen'] : false;

			if($this->jenis){ // untuk mobile
				$user = isset($_POST['username']) ? $_POST['username'] : false;
				$token = isset($_POST['token']) ? $_POST['token'] : false;

				// get token di db
				require_once ROOT.DS.'app'.DS.'models'.DS.'TokenModel.php';
				$tokenModel = new TokenModel();
				$this->token = $tokenModel->getToken($user);

				if($this->token){
					// pengecekan token, dan tgl exp
					if ( ($token == "") 
						|| (!password_verify($token, $this->token['token'])) 
						|| (time() > strtotime($this->token['tgl_exp'])) ) 
						return false;
					else if( ($token != "") 
						&& (password_verify($token, $this->token['token'])) 
						&& (time() <= strtotime($this->token['tgl_exp'])) ) 
						return true;
					else return false;
				}
				else return false;
			}
			else{ // untuk sistem
				if(!$this->login) return false;
				else return true;
			}
		}

		/**
		* Fungsi untuk mencegah sistem mengakses fungsi khusus mobile
		* jika jenis false atau jenis bukan mobile maka akan dilempar ke home
		*/
		public function mobileOnly(){
			$this->jenis = isset($_POST['jenis']) ? $_POST['jenis'] : false;

			if((!$this->jenis) || ($this->jenis != 'mobile')){
				header('Location: '.BASE_URL);
				die();
			}
		}

		/**
		*
		*/
		private function getAkses($user){

		}

		/**
		* Fungsi untuk mendapatkan token yang sudah di generate
		* untuk token mobile, dan token crsf yang akan dipasang disetiap module
		*/
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

		/**
		* Fungsi untuk generate random yang secure
		*/
		private function crypto_rand_secure($min, $max){
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

		/**
		* Fungsi untuk mengecek token yang dikirim ke client apakah sama dengan token yg di session
		*/
		public function cekToken($sess_token, $token, $modul){
			if(!password_verify($sess_token, $token)){
				header('Location: '.BASE_URL.$modul.'/');
				die();
			}
		}
	}