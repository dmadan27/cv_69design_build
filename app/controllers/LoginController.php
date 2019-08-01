<?php
Defined("BASE_PATH") or die(ACCESS_DENIED);

/**
 * Class login
 * Untuk melakukan login ke sistem, lockscreen dan logout
 */
class Login extends Controller
{

	protected $username;
	protected $password;
	protected $token;

	/**
	 * Construct
	 * Load class Auth
	 */
	public function __construct() {
		$this->auth();
		$this->validation();
		$this->helper();
		$this->model('UserModel');
	}

	/**
	 * Method index
	 * Default controller
	 * Proses pengecekan sudah login atau belum
	 */
	public function index() {
		if($this->auth->isLogin()) { $this->redirect(BASE_URL); } // jika sudah login, tidak bisa akses
		else { // jika belum login
			$_SESSION['sess_lockscreen'] = false;
			if($_SERVER['REQUEST_METHOD'] == "POST") { $this->loginSistem(); } // jika request post login
			else { $this->view('auth/login'); } // jika bukan, atau hanya menampilkan halaman login
		}
	}

	/**
	 * Method loginSistem
	 * Proses login untuk sistem (web)
	 * Pengecekan user dan password berdasarkan jenis user
	 * Pemberian hak akses berdasarkan level
	 * Set session
	 * @param callback {string} default bool false - callback url saat lockscreen
	 * @return output {object} array berupa json
	 */
	private function loginSistem($callback = false) {
		// $this->username = isset($_POST['username']) ? $this->validation->validInput($_POST['username'], false) : false;
		// $this->password = isset($_POST['password']) ? $this->validation->validInput($_POST['password'], false) : false;

		$this->username = isset($_POST['username']) ? $this->validation->validInput($_POST['username'], false) : false;
		$this->password = isset($_POST['password']) ? $_POST['password'] : false;

		$errorUser = $errorPass = $notif = '';

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

		// pesan error
		$error = array(
			'username' => $errorUser,
			'password' => $errorPass,
		);

		$result = array(
			'success' => $status,
			'callback' => $callback,
			'error' => $error,
			'notif' => $notif,
		);

		header('Content-Type: application/json');
		http_response_code(200);
		echo json_encode($result);
	}

	/**
	 * Method lockscreen
	 * Proses set ulang session login dan session lockscreen
	 */
	public function lockscreen() {
		$lockscreen = isset($_SESSION['sess_lockscreen']) ? $_SESSION['sess_lockscreen'] : false;
		$callback = isset($_GET['callback']) ? $_GET['callback'] : false;

		if(!$lockscreen) { $this->redirect(BASE_URL); }
		else{
			if($_SERVER['REQUEST_METHOD'] == "POST") $this->loginSistem($callback); // jika request post login
			else $this->view('auth/lockscreen'); // jika bukan, atau hanya menampilkan halaman login
		}
	}

	/**
	 * Method setSession
	 * Proses set session user sistem sesuai dengan level
	 * @param level {string}
	 */
	private function setSession($level) {
		// set data profil sesuai dgn jenis user
		if(strtolower($level) == 'kas besar') { $dataProfil = $this->UserModel->getKasBesar($this->username); }
		else if(strtolower($level) == 'kas kecil') {
			$dataProfil = $this->UserModel->getKasKecil($this->username);
			$_SESSION['sess_saldo_full'] = $this->helper->cetakRupiah($dataProfil['saldo']);
			$_SESSION['sess_saldo'] = $dataProfil['saldo'];
		}
		else if(strtolower($level) == 'owner') { $dataProfil = $this->UserModel->getOwner($this->username); }

		// cek kondisi foto
		if(!empty($dataProfil['foto'])){
			// cek foto di storage
			$filename = ROOT.DS.'assets'.DS.'images'.DS.'user'.DS.$dataProfil['foto'];
			if(!file_exists($filename)){ $foto = BASE_URL.'assets/images/user/default.jpg'; }
			else{ $foto = BASE_URL.'assets/images/user/'.$dataProfil['foto']; }
		}
		else { $foto = BASE_URL.'assets/images/user/default.jpg'; }

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
		$_SESSION['sess_menu'] = $this->auth->getListMenu($level);
	}

	/**
	 * Method setAkses
	 */
	private function setAkses($level) {

	}

	/**
	 * Method logout
	 * Proses logout dari sistem dan penghapusan semua session
	 */
	public function logout() {
		session_unset();
		session_destroy();

		$this->redirect(BASE_URL);
	}
}