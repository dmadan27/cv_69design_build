<?php
	Defined("BASE_PATH") or die(ACCESS_DENIED);

	/**
	 * 
	 */
	class Lupa_password extends Controller{

		protected $password_baru;
		protected $password_konf;
		protected $success = false;

		/**
		 * 
		 */
		public function __construct(){
			$this->auth();
			$this->validation();
			$this->model('UserModel');
			$this->model('TokenModel');
		}

		/**
		 * 
		 */
		public function index(){
			$jenis = isset($_POST['jenis']) ? $this->validation->validInput($_POST['jenis'], false) : false;

			// cek jenis akses
			if($jenis && $jenis == 'sub-kas-kecil') { $this->lupa_password_mobile(); } // jika mobile
			else{ // jika sistem
				if($this->auth->isLogin()) $this->redirect(BASE_URL); // jika sudah login, tidak bisa akses
				else $this->lupa_password();
			}
		}

		/**
		 * 
		 */
		private function lupa_password(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$email = isset($_POST['username']) ? $this->validation->validInput($_POST['username'], false) : false;

				$errorEmail = "";
				$notif = $dataToken = '';

				$dataEmail = $this->UserModel->getUser($email);

				if(!$dataEmail){
					$errorEmail = "Email Tidak Ditemukan";
					$notif = array(
						'title' => "Pesan Pemberitahuan",
						'message' => "Silahkan Cek Kembali Form Isian",
					);
				}
				else{
					$dataToken = $this->getToken($email);

					if(strtolower($dataEmail['level']) == 'kas besar') 
						$dataUser = $this->UserModel->getKasBesar($email);
					else if(strtolower($dataEmail['level']) == 'kas kecil')
						$dataUser = $this->UserModel->getKasKecil($email);
					else if(strtolower($dataEmail['level']) == 'owner')
						$dataUser = $this->UserModel->getOwner($email);

					// kirim email
					$link = BASE_URL.'lupa-password/reset/?user='.$email.'&token='.$dataToken['token_asli'];
					$sendTo = array(
						'email' => $email,
						'name' => $dataUser['nama'],
						'text' => "Hai ".$dataUser['nama'].",\nKlik link berikut untuk mereset password: ".$link."\nHarap lakukan reset password sebelum tanggal ".$dataToken['tgl_exp'],
					);
					
					$sendEmail = $this->sendEmail($sendTo);

					if($sendEmail['status']) {
						// get data token lama dan hapus
						if($this->TokenModel->setToken_lupa_password($dataToken)) {
							$this->status = true;
							$notif = array(
								'title' => "Pesan Berhasil",
								'message' => "Pengajuan Reset Password Berhasil, Silahkan Cek Email Anda Untuk Langkah Selanjutnya",
							);
						}
						else{
							$notif = array(
								'title' => "Pesan Gagal",
								'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
							);
						}
					}
					else {
						$errorEmail = $sendEmail['error'];
						$notif = array(
							'title' => "Pesan Gagal",
							'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
						);
					}
				}

				$output = array(
					'status' => $this->status,
					'error' => array('email' => $errorEmail),
					'notif' => $notif,
					'token' => $dataToken,
				);

				echo json_encode($output);
			}
			else $this->redirect();	
		}

		/**
		 * 
		 */
		private function lupa_password_mobile(){
			$this->auth->mobileOnly();

			$email = isset($_POST['username']) ? $this->validation->validInput($_POST['username'], false) : false;

			$errorEmail = "";
			$notif = $dataToken = '';

			$dataEmail = $this->UserModel->getUser($email);

			if(!$dataEmail || $dataEmail["level"] != "SUB KAS KECIL"){
				$errorEmail = "Email Tidak Ditemukan";
				$notif = array(
					'title' => "Pesan Pemberitahuan",
					'message' => "Silahkan Cek Kembali Form Isian",
				);
			}
			else{
				$dataToken = $this->getToken($email);

				$dataUser = $this->UserModel->getSubKasKecil($email);

				// kirim email
				// tambah &tipe=sub-kas-kecil
				$link = BASE_URL.'lupa-password/reset/?user='.$email.'&token='.$dataToken['token_asli'];
				$sendTo = array(
					'email' => $email,
					'name' => $dataUser['nama'],
					'text' => "Hai ".$dataUser['nama'].",\nKlik link berikut untuk mereset password: ".$link."\nHarap lakukan reset password sebelum tanggal ".$dataToken['tgl_exp'],
				);
				
				$sendEmail = $this->sendEmail($sendTo);

				if($sendEmail['status']) {
					// get data token lama dan hapus
					if($this->TokenModel->setToken_lupa_password($dataToken)) {
						$this->status = true;
						$notif = array(
							'title' => "Pesan Berhasil",
							'message' => "Pengajuan Reset Password Berhasil, Silahkan Cek Email Anda Untuk Langkah Selanjutnya",
						);
					}
					else{
						$notif = array(
							'title' => "Pesan Gagal",
							'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
						);
					}
				}
				else {
					$errorEmail = $sendEmail['error'];
					$notif = array(
						'title' => "Pesan Gagal",
						'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
					);
				}
			}

			$output = array(
				'status' => $this->status,
				'error' => array('email' => $errorEmail),
				'notif' => $notif,
				'token' => $dataToken,
			);

			echo json_encode($output);
		}

		/**
		 * 
		 */
		private function getToken($username){
			$token = $this->auth->getToken();
			$tokenSave = password_hash($token, PASSWORD_BCRYPT);
			$dataToken = array(
				'username' => $username,
				'token_asli' => $token,
				'token' => $tokenSave,
				'tgl_buat' => date('Y-m-d H:i:s'),
				'tgl_exp' => date('Y-m-d H:i:s', time()+(60*60*24*1)),
			);

			return $dataToken;
		}

		/**
		 * 
		 */
		private function cekToken(){
			$token = $this->TokenModel->getToken_lupa_password($this->username);

			if($token){
				if ( ($this->token == "") 
					|| (!password_verify($this->token, $token['token'])) 
					|| (time() > strtotime($token['tgl_exp'])) ) 
					{ return false; }
				else if( ($this->token != "") 
					&& (password_verify($this->token, $token['token'])) 
					&& (time() <= strtotime($token['tgl_exp'])) ) 
					{ return true; }
				else { return false; }
			}
			else { return false; }
		}

		/**
		 * 
		 */
		private function sendEmail($sendTo){
			require_once ROOT.DS.'app'.DS.'library'.DS.'PHPMailer'.DS.'PHPMailerAutoload.php';
			$mail = new PHPMailer;

			$status = false;
			$error = '';

			$username = SEND_EMAIL['email'];
			$password = SEND_EMAIL['password']; 
			$cc = '';
			$nama_pengirim = 'ADMIN 69 DESIGN BUILD';
			$subjek = 'Reset Password';
			
			$mail->CharSet = 'utf-8';
			ini_set('default_charset', 'UTF-8');
			$mail->isSMTP();
			$mail->SMTPDebug = 0;  //untuk tahu semua debug nya
			$mail->Debugoutput = 'html';
			$mail->Host = 'majora.rapidplex.com'; //sesuaikan lagi  - 'smtp.gmail.com' (google)  -  mail.lordraze.com  -  majora.rapidplex.com
			$mail->Port = 465; //sesuaikan lagi  -  587 (google)  -  465 (domain)
			$mail->SMTPSecure = 'ssl'; //sesuaikan lagi
			$mail->SMTPAuth = true;
			$mail->Username = $username; 
			$mail->Password = $password;
			$mail->setFrom($username, $nama_pengirim);
			$mail->addAddress($sendTo['email'], $sendTo['name']);
			$mail->addCC($cc);
			$mail->Subject = $subjek;
			$mail->Body = $sendTo['text'];

			if(!$mail->send()) $error = "Kirim Email Error: ".$mail->ErrorrInfo;
			else $status = true;

			$output = array(
				'success' => $this->success,
				'error' => $error,
			);

			return $output;
		}

		/**
		 * 
		 */
		public function reset(){
			if($this->auth->isLogin()) $this->redirect(BASE_URL);

			$this->token = isset($_GET['token']) ? $this->validation->validInput($_GET['token'], false) : false;
			$this->username = isset($_GET['user']) ? $this->validation->validInput($_GET['user'], false) : false;


			if(!$this->token || !$this->username){
				$this->redirect(BASE_URL);
			}
			else{
				if($this->cekToken()){
					if($_SERVER['REQUEST_METHOD'] == "POST") $this->action_reset($this->username);
					else $this->view('reset_password');
				}
				else $this->redirect(BASE_URL);
			}

		}

		/**
		 * 
		 */
		private function action_reset($username){
			$this->password_baru = isset($_POST['password_baru']) ? $this->validation->validInput($_POST['password_baru'], false) : false;
			$this->password_konf = isset($_POST['password_konf']) ? $this->validation->validInput($_POST['password_konf'], false) : false;

			$error = $notif = '';

			$validasi = $this->set_validation(
				$data = array(
					'password_baru' => $this->password_baru, 
					'password_konf' => $this->password_konf
				)
			);
			$cek = $validasi['cek'];
			$error = $validasi['error'];

			if($this->password_baru !== $this->password_konf){
				$cek = false;
				$error['password_baru'] = $error['password_konf'] = 'Konfirmasi Password dan Password Baru Tidak Sama !';
			}

			if($cek){
				$data = array(
					'username' => $username,
					'password' => password_hash($this->password_baru, PASSWORD_BCRYPT),
				);

				if($this->UserModel->updatePassword($data, true)){
					$this->status = true;
					$notif = array(
						'title' => "Pesan Berhasil",
						'message' => "Password Anda Berhasil di Reset, Silahkan Coba Login Kembali",
					);
				}
				else{
					$notif = array(
						'title' => "Pesan Gagal",
						'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
					);
				}
			}
			else{
				$notif = array(
					'title' => "Pesan Pemberitahuan",
					'message' => "Silahkan Cek Kembali Form Isian",
				);
			}

			$output = array(
				'status' => $this->status,
				'notif' => $notif,
				'error' => $error,
				// 'data' => $data
			);

			echo json_encode($output);
		}

		/**
		 * 
		 */
		private function set_validation($data){
			// password baru
			$this->validation->set_rules($data['password_baru'], 'Password Baru', 'password_baru', 'string | 5 | 255 | required');
			// password konf
			$this->validation->set_rules($data['password_konf'], 'Konfirmasi Password', 'password_konf', 'string | 5 | 255 | required');

			return $this->validation->run();
		}

	}