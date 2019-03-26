<?php

Defined("BASE_PATH") or die(ACCESS_DENIED);

/**
 * Class Auth
 * Library untuk pengecekan Authentikasi yg masuk sistem
 */
class Auth
{
	
	private $login;
	private $lockscreen;
	private $timeout;

	/**
	 * Method cekAuth
	 * Proses check auth sistem apakah status uses sudah login atau belum
	 * Jika belum login maka akan diarahakan ke login
	 */
	public function cekAuth() {
		if(!$this->isLogin()) {
			$this->lockscreen = isset($_SESSION['sess_lockscreen']) ? $_SESSION['sess_lockscreen'] : false;

			// cek lockscreen
			if($this->lockscreen) {
				$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 
					"https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				header('Location: '.BASE_URL.'login/lockscreen/?callback='.$actual_link);
				die();
			}
			else {
				session_unset();
				session_destroy();
				header('Location: '.BASE_URL.'login');
				die();
			}
		}

		// param khusus untuk notifikasi atau req dari ajax yg tidak reload halaman
		$cekTimeout = isset($_POST['timeout']) ? $_POST['timeout'] : false;

		if(!$cekTimeout) { $_SESSION['sess_timeout'] = date('Y-m-d H:i:s', time()+(60*60)); }
	}

	/**
	 * Method isLogin
	 * Proses pengecekan status login untuk sistem dan mobile
	 * @return result {bool}
	 */
	public function isLogin() {
		$this->login = isset($_SESSION['sess_login']) ? $_SESSION['sess_login'] : false;
		$this->timeout = isset($_SESSION['sess_timeout']) ? strtotime($_SESSION['sess_timeout']) : false;

		if(!$this->login) { return false; }
		
		if($this->login && $this->login === true && (time() > $this->timeout)) {
			$_SESSION['sess_login'] = false;
			$_SESSION['sess_lockscreen'] = true;
			return false;
		}

		return true;
	}

	/**
	 * Method getListMenu
	 * @param level {string}
	 * @return listMenu {array}
	 */
	public function getListMenu($level) {
		$modelName = 'Access_rightModel';
		require_once ROOT.DS.'app'.DS.'models'.DS.ucfirst($modelName).'.php';
		
		$class = ucfirst($modelName);
		$this->$modelName = new $class();
		$listMenu = $this->Access_rightModel->getAll_menuByLevel($level);

		return $listMenu;
	}

	/**
	 * Method getToken
	 * Proses untuk mendapatkan token yang sudah di generate
	 * @return token {string}
	 */
	public function getToken(){
		$token = "";
		$codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
		$codeAlphabet.= "0123456789";
		$max = strlen($codeAlphabet); // edited

		for ($i=0; $i < 50; $i++) {
			$token .= $codeAlphabet[$this->crypto_rand_secure(0, $max-1)];
		}

		return $token;
	}

	/**
	 * Method crypto_rand_secure
	 * Proses generate secure random
	 * @param min {int}
	 * @param max {int}
	 * @return result {int}
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
	 * Method cekToken
	 * Fungsi untuk mengecek token yang dikirim ke client apakah sama dengan token yg di session
	 * 
	 * Masih tahap pengembangan
	 */
	public function cekToken($sess_token, $token, $modul){
		if(!password_verify($sess_token, $token)){
			header('Location: '.BASE_URL.$modul.'/');
			die();
		}
	}
}