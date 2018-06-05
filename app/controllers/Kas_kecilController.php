<?php
Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
class Kas_kecil extends Crud_modalsAbstract{

	protected $token;

	public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
			$this->model('Kas_kecilModel');
			$this->helper();	
			$this->validation();
	}	


	public function index(){
			$this->list();
		}


	protected function list(){
			$css = array(
				'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
				'assets/bower_components/dropify/dist/css/dropify.min.css'

		);
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'assets/bower_components/dropify/dist/js/dropify.min.js',
				'app/views/kas_kecil/js/initList.js',
				'app/views/kas_kecil/js/initForm.js',
					
			);

			$config = array(
				'title' => array(
					'main' => 'Data Kas Kecil',
					'sub' => 'List Semua Data Kas Kecil',
				),
				'css' => $css,
				'js' => $js,
			);

			// // set token
			$_SESSION['token_kas_kecil'] = array(
				'list' => md5($this->auth->getToken()),
				'add' => md5($this->auth->getToken())
			);

			$this->token = array(
				'list' => password_hash($_SESSION['token_kas_kecil']['list'], PASSWORD_BCRYPT),
				'add' => password_hash($_SESSION['token_kas_kecil']['add'], PASSWORD_BCRYPT)	
			);

			$data = array(
				'token_list' => $this->token['list'],
				'token_add' => $this->token['add'],
			);

			$this->layout('kas_kecil/list', $config, $data);
		}	


		public function form($id){
		
		}
		public function get_list(){
			$token = isset($_POST['token_list']) ? $_POST['token_list'] : false;
			
			// cek token
			$this->auth->cekToken($_SESSION['token_kas_kecil']['list'], $token, 'kas_kecil');

			// config datatable
			$config_dataTable = array(
				'tabel' => 'kas_kecil',
				'kolomOrder' => array(null, 'id', 'nama', 'alamat', 'no_telp',  'saldo', null),
				'kolomCari' => array('id','nama'),
				'orderBy' => array('id' => 'asc'),
				'kondisi' => false,
			);

			$datakaskecil = $this->Kas_kecilModel->getAllDataTable($config_dataTable);

			// set token
			$_SESSION['token_kas_kecil']['edit'] = md5($this->auth->getToken());
			$_SESSION['token_kas_kecil']['delete'] = md5($this->auth->getToken());
			
			$this->token = array(
				'edit' => password_hash($_SESSION['token_kas_kecil']['edit'], PASSWORD_BCRYPT),
				'delete' => password_hash($_SESSION['token_kas_kecil']['delete'], PASSWORD_BCRYPT),	
			);

			$data = array();
			$no_urut = $_POST['start'];
			foreach($datakaskecil as $row){
				$no_urut++;

				$status = (strtolower($row['status']) == "AKTIF") ? '<span class="label label-success">'.$row['status'].'</span>' : '<span class="label label-primary">'.$row['status'].'</span>';

				//button aksi
				$aksiDetail = '<button onclick="getView('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-info btn-flat" title="Lihat Detail"><i class="fa fa-eye"></i></button>';
				$aksiEdit = '<button onclick="getEdit('."'".$row["id"]."'".', '."'".$this->token["edit"]."'".')" type="button" class="btn btn-sm btn-success btn-flat" title="Edit Data"><i class="fa fa-pencil"></i></button>';
				$aksiHapus = '<button onclick="getDelete('."'".$row["id"]."'".', '."'".$this->token["delete"]."'".')" type="button" class="btn btn-sm btn-danger btn-flat" title="Hapus Data"><i class="fa fa-trash"></i></button>';
				
				$aksi = '<div class="btn-group">'.$aksiDetail.$aksiEdit.$aksiHapus.'</div>';
				
				$dataRow = array();
				$dataRow[] = $no_urut;
				$dataRow[] = $row['id'];
				$dataRow[] = $row['nama'];
				$dataRow[] = $row['alamat'];
				$dataRow[] = $row['no_telp'];
				$dataRow[] = $row['saldo'];
				
				$dataRow[] = $aksi;
				$data[] = $dataRow;
			}

			$output = array(
				'draw' => $_POST['draw'],
				'recordsTotal' => $this->Kas_kecilModel->recordTotal(),
				'recordsFiltered' => $this->Kas_kecilModel->recordFilter(),
				'data' => $data,
			);

			echo json_encode($output);
		}

		

		/**
		* 
		*/
		public function action_add(){
			$data = isset($_POST) ? $_POST : false;
			$foto = isset($_FILES['foto']) ? $_FILES['foto'] : false;

			$this->auth->cekToken($_SESSION['token_kas_kecil']['add'], $data['token'], 'kas-kecil');

			$cekFoto = true;
			$status = false;
			$error = "";

			if(!$data){
				$notif = array(
					'title' => "Pesan Gagal",
					'message' => "Terjadi Kesalahan Teknis, Silahkan Coba Kembali",
				);
			}
			else{
				$validasi = $this->set_validation($data);
				$cek = $validasi['cek'];
				$error = $validasi['error'];


				if($foto){
					$configFoto = array(
						'jenis' => 'gambar',
						'error' => $foto['error'],
						'size' => $foto['size'],
						'name' => $foto['name'],
						'tmp_name' => $foto['tmp_name'],
						'max' => 2*1048576,
					);
					$validasiFoto = $this->validation->validFile($configFoto);
					if(!$validasiFoto['cek']){
						$cek = false;
						$error['foto'] = $validasiFoto['error'];
					}
					else $valueFoto = $validasiFoto['namaFile'];
				}
				else $valueFoto = NULL;

				if($cek){
					// validasi inputan
					$data = array(
						'id' => $this->validation->validInput($data['id']),
						'nama' => $this->validation->validInput($data['nama']),
						'alamat' => $this->validation->validInput($data['alamat']),
						'no_telp' => $this->validation->validInput($data['no_telp']),
						'email' => $this->validation->validInput($data['email'], false),
						'foto' => $this->validation->validInput($valueFoto, false),
						'saldo' => $this->validation->validInput($data['saldo']),
						'status' => $this->validation->validInput($data['status']),
						'password' =>  password_hash($this->validation->validInput($data['password'], false), PASSWORD_BCRYPT),
							
					);

					if($foto){
						$path = ROOT.DS.'assets'.DS.'images'.DS.$valueFoto;
						if(!move_uploaded_file($foto['tmp_name'], $path)){
							$error['foto'] = "Upload Foto Gagal";
							$status = $cekFoto = false;
						}
					}

					if($cekFoto){

						if($this->Kas_kecilModel->checkExistEmail($data['email'])){
							if($this->Kas_kecilModel->insert($data)) {
								$status = true;
								$notif = array(
									'title' => "Pesan Berhasil",
									'message' => "Tambah Data Sub Kas Kecil Baru Berhasil",
								);
							}
							else {
								$notif = array(
									'title' => "Pesan Gagal",
									'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
								);
							}
						}
						else {
							$notif = array(
								'title' => "Pesan Pemberitahuan",
								'message' => "Silahkan Cek Kembali Form Isian",
							);
							$error['email'] = "Email telah digunakan sebelumnya";
						}
					}
				}
				else{
					$notif = array(
						'title' => "Pesan Pemberitahuan",
						'message' => "Silahkan Cek Kembali Form Isian",
					);
				}
			}

			$output = array(
				'status' => $status,
				'notif' => $notif,
				'error' => $error,
				'data' => $data,
				'foto' => $foto
			);

			echo json_encode($output);
	
		}

		/**
		* 
		*/
		protected function edit($id){
			$id = strtoupper($id);
			$token = isset($_POST['token_edit']) ? $_POST['token_edit'] : false;
			$this->auth->cekToken($_SESSION['token_kas_kecil']['edit'], $token, 'kas_kecil');

			$data = !empty($this->Kas_kecilModel->getById($id)) ? $this->Kas_kecilModel->getById($id) : false;
			echo json_encode($data);
		}

		/**
		* 
		*/
		protected function action_edit(){
			$data = isset($_POST) ? $_POST : false;
			$this->auth->cekToken($_SESSION['token_kas_kecil']['edit'], $data['token'], 'kas_kecil');
			$status = false;
			$error = "";

			if(!$data){
				$notif = array(
					'title' => "Pesan Gagal",
					'message' => "Terjadi Kesalahan Teknis, Silahkan Coba Kembali",
				);
			}
			else{
				// validasi data
				$validasi = $this->set_validation($data);
				$cek = $validasi['cek'];
				$error = $validasi['error'];

				if($cek){
					// validasi inputan
					$data = array(
						'id' => $this->validation->validInput($data['id']),
						'nama' => $this->validation->validInput($data['nama']),
						'alamat' => $this->validation->validInput($data['alamat']),
						'no_telp' => $this->validation->validInput($data['no_telp']),
						'email' => $this->validation->validInput($data['email']),
						'foto' => $this->validation->validInput($data['foto']),
						'saldo' => $this->validation->validInput($data['saldo']),
						'status' => $this->validation->validInput($data['status'])
					);

					// update db

					// transact

					if($this->Kas_kecilModel->update($data)) {
						$status = true;
						$notif = array(
							'title' => "Pesan Berhasil",
							'message' => "Edit Data Kas Kecil Berhasil",
						);
					}
					else {
						$notif = array(
							'title' => "Pesan Gagal",
							'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
						);
					}

					// commit
				}
				else {
					$notif = array(
						'title' => "Pesan Pemberitahuan",
						'message' => "Silahkan Cek Kembali Form Isian",
					);
				}
			}

			$output = array(
				'status' => $status,
				'notif' => $notif,
				'error' => $error,
				// 'data' => $data
			);

			echo json_encode($output);

		}

		/**
		*
		*/
		public function detail($id){
		}

		/**
		*
		*/
		public function delete($id){
			$id = strtoupper($id);
			$token = isset($_POST['token_delete']) ? $_POST['token_delete'] : false;
			$this->auth->cekToken($_SESSION['token_kas_kecil']['delete'], $token, 'kas_kecil');
			
			if($this->Kas_kecilModel->delete($id)) $status = true;
			else $status = false;

			echo json_encode($status);

		}

		/**
		*
		*/
		public function get_last_id(){
			$token = isset($_POST['token']) ? $_POST['token'] : false;
			$this->auth->cekToken($_SESSION['token_kas_kecil']['add'], $token, 'kas-kecil');

			$data = !empty($this->Kas_kecilModel->getLastID()['id']) ? $this->Kas_kecilModel->getLastID()['id'] : false;

			if(!$data) $id = 'KK001';
			else{
				// $data = implode('', $data);
				$kode = 'KK';
				$noUrut = (int)substr($data, 2, 3);
				$noUrut++;

				$id = $kode.sprintf("%03s", $noUrut);
			}

			echo $id;
		}

		/**
		*
		*/
		public function export(){

		}

		private function set_validation($data){
			$required = ($data['action'] =="action-add") ? 'not_required' : 'required';

			// ID
			$this->validation->set_rules($data['id'], 'ID Kas Kecil', 'id', 'string | 1 | 255 | required');
			// nama
			$this->validation->set_rules($data['nama'], 'Nama', 'nama', 'string | 1 | 255 | required');
			// alamat
			$this->validation->set_rules($data['alamat'], 'Alamat Proyek', 'alamat', 'string | 1 | 255 | not_required');
			// no_telp
			$this->validation->set_rules($data['no_telp'], 'Nomor Telepon', 'no_telp', 'angka | 1 | 255 | required');
			// email
			$this->validation->set_rules($data['email'], 'Alamat Email', 'email', 'email | 1 | 255 | required');
			// saldo
			$this->validation->set_rules($data['saldo'], 'Saldo Awal', 'saldo', 'nilai | 0 | 99999999999 | required');
			// status
			$this->validation->set_rules($data['status'], 'Status', 'status', 'string | 1 | 255 | required');

			return $this->validation->run();
		}

}