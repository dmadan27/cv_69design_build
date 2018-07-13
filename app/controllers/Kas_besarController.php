<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
	
	/**
	*
	*/
	class Kas_besar extends Crud_modalsAbstract{

		private $token;
		private $status = false;

		/**
		*
		*/
		public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
			$this->model('Kas_besarModel');
			$this->helper();
			$this->validation();
		}	

		/**
		*
		*/
		public function index(){
			$this->list();
		}

		/**
		*
		*/
		protected function list(){
			$css = array(
				'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
				'assets/bower_components/dropify/dist/css/dropify.min.css',
			);
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'assets/bower_components/dropify/dist/js/dropify.min.js',
				'app/views/kas_besar/js/initList.js',
				'app/views/kas_besar/js/initForm.js',
			);

			$config = array(
				'title' => array(
					'main' => 'Data Kas Besar',
					'sub' => 'Menampilkan Semua Data Kas Besar',
				),
				'css' => $css,
				'js' => $js,
			);

			$this->layout('kas_besar/list', $config, $data = null);
		}

		/**
		* 
		*/
		public function get_list(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				// config datatable
				$config_dataTable = array(
					'tabel' => 'kas_besar',
					'kolomOrder' => array(null, 'id', 'nama', 'alamat', 'status', null),
					'kolomCari' => array('id', 'nama', 'alamat', 'status'),
					'orderBy' => array('id' => 'desc', 'status' => 'asc'),
					'kondisi' => false,
				);

				$dataKasBesar = $this->Kas_besarModel->getAllDataTable($config_dataTable);

				$data = array();
				$no_urut = $_POST['start'];
				foreach($dataKasBesar as $row){
					$no_urut++;

					$status = (strtolower($row['status']) == "AKTIF") ? '<span class="label label-success">'.$row['status'].'</span>' : '<span class="label label-primary">'.$row['status'].'</span>';

					//button aksi
					$aksiDetail = '<button onclick="getView('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-info btn-flat" title="Lihat Detail"><i class="fa fa-eye"></i></button>';
					$aksiEdit = '<button onclick="getEdit('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-success btn-flat" title="Edit Data"><i class="fa fa-pencil"></i></button>';
					$aksiHapus = '<button onclick="getDelete('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-danger btn-flat" title="Hapus Data"><i class="fa fa-trash"></i></button>';
					
					$aksi = '<div class="btn-group">'.$aksiDetail.$aksiEdit.$aksiHapus.'</div>';
					
					$dataRow = array();
					$dataRow[] = $no_urut;
					$dataRow[] = $row['id'];
					$dataRow[] = $row['nama'];
					$dataRow[] = $row['alamat'];
					$dataRow[] = $row['status'];
					$dataRow[] = $aksi;

					$data[] = $dataRow;
				}

				$output = array(
					'draw' => $_POST['draw'],
					'recordsTotal' => $this->Kas_besarModel->recordTotal(),
					'recordsFiltered' => $this->Kas_besarModel->recordFilter(),
					'data' => $data,
				);

				echo json_encode($output);
			}
			else $this->redirect();
		}	

		/**
		*
		*/
		public function action_add(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$data = isset($_POST) ? $_POST : false;
				$foto = isset($_FILES['foto']) ? $_FILES['foto'] : false;

				$cekFoto = true;
				$error = $notif = array();

				if(!$data){
					$notif = array(
						'type' => 'error',
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
							'status' => $this->validation->validInput($data['status']),
							'password' => password_hash($this->validation->validInput($data['password'], false), PASSWORD_BCRYPT),
								
						);

						if($foto){
							$path = ROOT.DS.'assets'.DS.'images'.DS.$valueFoto;
							if(!move_uploaded_file($foto['tmp_name'], $path)){
								$error['foto'] = "Upload Foto Gagal";
								$this->status = $cekFoto = false;
							}
						}

						if($cekFoto){

							if($this->Kas_besarModel->checkExistEmail($data['email'])){
								if($this->Kas_besarModel->insert($data)) {
									$this->status = true;
									$notif = array(
										'type' => 'success',
										'title' => "Pesan Berhasil",
										'message' => "Tambah Data  Kas Besar Baru Berhasil",
									);
								}
								else {
									$notif = array(
										'type' => 'error',
										'title' => "Pesan Gagal",
										'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
									);
								}
							}
							else {
								$notif = array(
									'type' => 'warning',
									'title' => "Pesan Pemberitahuan",
									'message' => "Silahkan Cek Kembali Form Isian",
								);
								$error['email'] = "Email telah digunakan sebelumnya";
							}
						}
					}
					else{
						$notif = array(
							'type' => 'warning',
							'title' => "Pesan Pemberitahuan",
							'message' => "Silahkan Cek Kembali Form Isian",
						);
					}
				}

				$output = array(
					'status' => $this->status,
					'notif' => $notif,
					'error' => $error,
					'data' => $data,
					'foto' => $foto
				);

				echo json_encode($output);
			}
			else $this->redirect();
				
		}

		/**
		* Function edit
		* method untuk get data edit
		* param $id didapat dari url
		* return berupa json
		*/
		public function edit($id){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$id = strtoupper($id);

				$data = !empty($this->Kas_besarModel->getById($id)) ? $this->Kas_besarModel->getById($id) : false;
				
				echo json_encode($data);
			}
			else $this->redirect();
				
		}

		/**
		* Function action_edit
		* method untuk aksi edit data
		* return berupa json
		* status => status berhasil atau gagal proses edit
		* notif => pesan yang akan ditampilkan disistem
		* error => error apa saja yang ada dari hasil validasi
		*/
		public function action_edit(){
			$data = isset($_POST) ? $_POST : false;
			$this->auth->cekToken($_SESSION['token_kas_besar']['edit'], $data['token'], 'kas-besar');
			
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
						'id' =>  $this->validation->validInput($data['id']),
						'nama' =>  $this->validation->validInput($data['nama']),
						'alamat' =>  $this->validation->validInput($data['alamat']),
						'no_telp' =>  $this->validation->validInput($data['no_telp']),
						'email' =>  $this->validation->validInput($data['email'], false),
						'status' =>  $this->validation->validInput($data['status']),
					);

					// update db

					// transact

					if($this->Kas_besarModel->update($data)) {
						$status = true;
						$notif = array(
							'title' => "Pesan Berhasil",
							'message' => "Edit Data Kas Besar Berhasil",
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
				'data' => $data
			);

			echo json_encode($output);
		}

		/**
		* Function detail
		* method untuk get data detail dan setting layouting detail
		* param $id didapat dari url
		*/
		public function detail($id){
			$id = strtoupper($id);
			if(empty($id) || $id == "") $this->redirect(BASE_URL."kas-besar/");

			$data_detail = !empty($this->Kas_besarModel->getById($id)) ? $this->Kas_besarModel->getById($id) : false;

			if(!$data_detail) $this->redirect(BASE_URL."kas-besar/");

			$css = array(
				'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
				'assets/bower_components/dropify/dist/css/dropify.min.css'
			);
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'assets/bower_components/dropify/dist/js/dropify.min.js',
				'app/views/kas_besar/js/initView.js',
				'app/views/kas_besar/js/initForm.js',
			);

			$config = array(
				'title' => array(
					'main' => 'Data Kas Besar',
					'sub' => 'Detail Data Kas Besar',
				),
				'css' => $css,
				'js' => $js,
			);

			$status = ($data_detail['status'] == "AKTIF") ? '<span class="label label-success">'.$data_detail['status'].'</span>' : '<span class="label label-danger">'.$data_detail['status'].'</span>';
			
			$_SESSION['token_kas_besar']['view'] = md5($this->auth->getToken());
			$_SESSION['token_kas_besar']['edit'] = md5($this->auth->getToken());
			$_SESSION['token_kas_besar']['delete'] = md5($this->auth->getToken());
			
			$this->token = array(
				'view' => password_hash($_SESSION['token_kas_besar']['view'], PASSWORD_BCRYPT),
				'edit' => password_hash($_SESSION['token_kas_besar']['edit'], PASSWORD_BCRYPT),
				'delete' => password_hash($_SESSION['token_kas_besar']['delete'], PASSWORD_BCRYPT)
			);

			if(!empty($data_detail['foto'])){
				// cek foto di storage
				$filename = ROOT.DS.'assets'.DS.'images'.DS.'user'.DS.$data_detail['foto'];
				if(!file_exists($filename)) 
					$foto = BASE_URL.'assets/images/user/default.jpg';
				else
					$foto = BASE_URL.'assets/images/user/'.$data_detail['foto'];
			}
			else $foto = BASE_URL.'assets/images/user/default.jpg';

			$data = array(
				'id' => $data_detail['id'],
				'nama' => $data_detail['nama'],
				'alamat' => $data_detail['alamat'],
				'no_telp' => $data_detail['no_telp'],
				'email' => $data_detail['email'],
				'foto' => $foto,
				'status' => $status,
				'token' => $this->token,
			);

			$this->layout('kas_besar/view', $config, $data);
		}

		/**
		* Function delete
		* method yang berfungsi untuk menghapus data
		* param $id didapat dari url
		* return json
		*/
		public function delete($id){
			$id = strtoupper($id);
			$token = isset($_POST['token_delete']) ? $_POST['token_delete'] : false;
			$this->auth->cekToken($_SESSION['token_kas_besar']['delete'], $token, 'kas_besar');
			
			if($this->Kas_besarModel->delete($id)) $status = true;
			else $status = false;

			echo json_encode($status);
		}

		/**
		*
		*/
		public function export(){

		}

		/**
		*
		*/
		public function get_last_id(){
			$token = isset($_POST['token']) ? $_POST['token'] : false;
			$this->auth->cekToken($_SESSION['token_kas_besar']['add'], $token, 'kas_besar');

			$data = !empty($this->Kas_besarModel->getLastID()['id']) ? $this->Kas_besarModel->getLastID()['id'] : false;

			if(!$data) $id = 'KB001';
			else{
				// $data = implode('', $data);
				$kode = 'KB';
				$noUrut = (int)substr($data, 2, 3);
				$noUrut++;

				$id = $kode.sprintf("%03s", $noUrut);
			}

			echo $id;
		}

			/**
		* Fungsi set_validation
		* method yang berfungsi untuk validasi inputan secara server side
		* param $data didapat dari post yang dilakukan oleh user
		* return berupa array, status hasil pengecekan dan error tiap validasi inputan
		*/
		private function set_validation($data){
			$required = ($data['action'] =="action-edit") ? 'not_required' : 'required';

			// ID
			$this->validation->set_rules($data['id'], 'ID Kas Kecil', 'id', 'string | 1 | 255 | required');
			// nama
			$this->validation->set_rules($data['nama'], 'Nama', 'nama', 'string | 1 | 255 | required');
			// alamat
			$this->validation->set_rules($data['alamat'], 'Alamat Proyek', 'alamat', 'string | 1 | 255 | not_required');
			// no_telp
			$this->validation->set_rules($data['no_telp'], 'Nomor Telepon', 'no_telp', 'angka | 1 | 255 | required');
			// email
			$this->validation->set_rules($data['email'], 'Alamat Email', 'email', 'email | 1 | 255 |', $required);
			// status
			$this->validation->set_rules($data['status'], 'Status', 'status', 'string | 1 | 255 | required');

			return $this->validation->run();
		}



}