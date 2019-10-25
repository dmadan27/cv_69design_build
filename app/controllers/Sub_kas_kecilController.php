<?php
	Defined("BASE_PATH") or die(ACCESS_DENIED);
	
	/**
	 * 
	 */
	class Sub_kas_kecil extends Crud_modalsAbstract{

		private $success = false;
		private $notif = array();
		private $error = array();
		private $message = NULL;

		/**
		 * 
		 */
		public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
			$this->model('Sub_kas_kecilModel');
			$this->model('DataTableModel');
			$this->helper();
			$this->validation();
			$this->excel();
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
			// set config untuk layouting
			$css = array(
				'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
				'assets/bower_components/dropify/dist/css/dropify.min.css'
			);
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'assets/bower_components/dropify/dist/js/dropify.min.js',
				'assets/plugins/input-mask/jquery.inputmask.bundle.js',
				'assets/js/library/export.js',
				'app/views/sub_kas_kecil/js/initList.js',
				'app/views/sub_kas_kecil/js/initForm.js',
			);

			$config = array(
				'title' => 'Menu Sub Kas Kecil',
				'property' => array(
					'main' => 'Data Sub Kas Kecil',
					'sub' => 'List Semua Sub Kas Kecil (Logistik)',
				),
				'css' => $css,
				'js' => $js,
			);

			$this->layout('sub_kas_kecil/list', $config, $data = NULL);
		}

		/**
		 * 
		 */
		public function get_list(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				// config datatable
				$config_dataTable = array(
					'tabel' => 'sub_kas_kecil',
					'kolomOrder' => array(null, 'id', 'nama', 'no_telp', 'email', 'saldo', 'status', null),
					'kolomCari' => array('id', 'nama', 'alamat', 'no_telp', 'email', 'saldo', 'status'),
					'orderBy' => array('status' => 'asc', 'id' => 'asc'),
					'kondisi' => false,
				);

				$dataBank = $this->DataTableModel->getAllDataTable($config_dataTable);

				$data = array();
				$no_urut = $_POST['start'];
				foreach($dataBank as $row){
					$no_urut++;

					$status = ($row['status'] == "AKTIF") ? '<span class="label label-success">'.$row['status'].'</span>' : '<span class="label label-danger">'.$row['status'].'</span>';

					// button aksi
					$aksiDetail = '<button onclick="getView('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-info btn-flat" title="Lihat Detail"><i class="fa fa-eye"></i></button>';
					$aksiEdit = '<button onclick="getEdit('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-success btn-flat" title="Edit Data"><i class="fa fa-pencil"></i></button>';
					$aksiHapus = '<button onclick="getDelete('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-danger btn-flat" title="Hapus Data"><i class="fa fa-trash"></i></button>';
					
					if($_SESSION['sess_level'] === 'KAS BESAR' || $_SESSION['sess_level'] === 'KAS KECIL') {
						$aksi = '<div class="btn-group">'.$aksiDetail.$aksiEdit.$aksiHapus.'</div>';
					}
					else if($_SESSION['sess_level'] === 'OWNER') {
						$aksi = '<div class="btn-group">'.$aksiDetail.'</div>';
					}
					else { $aksi = ''; }
					
					$dataRow = array();
					$dataRow[] = $no_urut;
					$dataRow[] = $row['id'];
					$dataRow[] = $row['nama'];
					$dataRow[] = $row['no_telp'];
					$dataRow[] = $row['email'];
					$dataRow[] = $this->helper->cetakRupiah($row['saldo']);
					$dataRow[] = $status;
					$dataRow[] = $aksi;

					$data[] = $dataRow;
				}

				$output = array(
					'draw' => $_POST['draw'],
					'recordsTotal' => $this->DataTableModel->recordTotal(),
					'recordsFiltered' => $this->DataTableModel->recordFilter(),
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
			if($_SERVER['REQUEST_METHOD'] == "POST") {
				$data = isset($_POST) ? $_POST : false;
				$foto = isset($_FILES['foto']) ? $_FILES['foto'] : false;

				$cekFoto = true;

				if(!$data){
					$this->notif = array(
						'type' => "error",
						'title' => "Pesan Gagal",
						'message' => "Terjadi Kesalahan Teknis, Silahkan Coba Kembali",
					);
				}
				else{
					$validasi = $this->set_validation($data);
					$cek = $validasi['cek'];
					$this->error = $validasi['error'];

					// cek password dan konf password
					if(($this->error['password'] == "") && ($data['password'] != $data['password_confirm'])){
						$cek = false;
						$this->error['password'] = $this->error['password_confirm'] = 'Password dan Konfirmasi Password Berbeda';
					}

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
							$this->error['foto'] = $validasiFoto['error'];
						}
						else $valueFoto = md5($data['id']).$validasiFoto['namaFile'];
					}
					else $valueFoto = NULL;

					if($cek){
						// validasi inputan
						$data = array(
							'id' =>  $this->validation->validInput($data['id']),
							'nama' =>  $this->validation->validInput($data['nama']),
							'alamat' =>  $this->validation->validInput($data['alamat']),
							'no_telp' =>  $this->validation->validInput($data['no_telp']),
							'foto' =>  $this->validation->validInput($valueFoto, false),
							'email' =>  $this->validation->validInput($data['email'], false),
							'password' =>  password_hash($this->validation->validInput($data['password'], false), PASSWORD_BCRYPT),
							'saldo' =>  $this->validation->validInput($data['saldo']),
							'status' =>  $this->validation->validInput($data['status']),
						);

						if($foto){
							$path = ROOT.DS.'assets'.DS.'images'.DS.'user'.DS.$valueFoto;
							if(!move_uploaded_file($foto['tmp_name'], $path)){
								$this->error['foto'] = "Upload Foto Gagal";
								$this->success = $cekFoto = false;
							}
						}

						if($cekFoto){
							// cek email
							if($this->Sub_kas_kecilModel->checkExistEmail($data['email'])){
								// insert db
								if($this->Sub_kas_kecilModel->insert($data)) {
									$this->success = true;
									$this->notif = array(
										'type' => 'success',
										'title' => "Pesan Berhasil",
										'message' => "Tambah Data Sub Kas Kecil Baru Berhasil",
									);
								}
								else {
									$this->notif = array(
										'type' => 'error',
										'title' => "Pesan Gagal",
										'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
									);
									$path = ROOT.DS.'assets'.DS.'images'.DS.'user'.DS.$valueFoto;
									$this->helper->rollback_file($path, false);
								}
							}
							else{
								$this->notif = array(
									'type' => 'warning',
									'title' => "Pesan Pemberitahuan",
									'message' => "Silahkan Cek Kembali Form Isian",
								);
								$this->error['email'] = "Email telah digunakan sebelumnya";
							}

						}
					}
					else{
						$this->notif = array(
							'type' => 'warning',
							'title' => "Pesan Pemberitahuan",
							'message' => "Silahkan Cek Kembali Form Isian",
						);
					}
				}

				$output = array(
					'success' => $this->success,
					'status_foto' => $cekFoto,
					'notif' => $this->notif,
					'error' => $this->error,
					'data' => $data,
					'foto' => $foto
				);

				echo json_encode($output);
			}
			else $this->redirect();
				
		}

		/**
		 * 
		 */
		public function edit($id){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$id = strtoupper($id);

				$data = !empty($this->Sub_kas_kecilModel->getById(strtoupper($id))) ? $this->Sub_kas_kecilModel->getById(strtoupper($id)) : false;
				echo json_encode($data);
			}
			else $this->redirect();

				
		}

		/**
		 * 
		 */
		public function action_edit(){
			$data = isset($_POST) ? $_POST : false;

			if(!$data){
				$this->notif = array(
					'type' => "error",
					'title' => "Pesan Gagal",
					'message' => "Terjadi Kesalahan Teknis, Silahkan Coba Kembali",
				);
			}
			else{
				$validasi = $this->set_validation($data);
				$cek = $validasi['cek'];
				$this->error = $validasi['error'];

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
					if($this->Sub_kas_kecilModel->update($data)) {
						$this->success = true;
						$this->notif = array(
							'title' => "Pesan Berhasil",
							'message' => "Edit Data Sub Kas Kecil Berhasil",
						);
					}
					else {
						$this->notif = array(
							'title' => "Pesan Gagal",
							'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
						);
					}
					
				}
				else{
					$this->notif = array(
						'title' => "Pesan Pemberitahuan",
						'message' => "Silahkan Cek Kembali Form Isian",
					);
				}
			}

			$output = array(
				'success' => $this->success,
				'notif' => $this->notif,
				'error' => $this->error,
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
			if(empty($id) || $id == "") $this->redirect(BASE_URL."sub-kas-kecil/");

			$data_detail = !empty($this->Sub_kas_kecilModel->getById($id)) ? $this->Sub_kas_kecilModel->getById($id) : false;

			if(!$data_detail) $this->redirect(BASE_URL."sub-kas-kecil/");

			$css = array(
				'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
				'assets/bower_components/dropify/dist/css/dropify.min.css',
				'assets/bower_components/Magnific-Popup-master/dist/magnific-popup.css',
				'assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
			);
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'assets/bower_components/dropify/dist/js/dropify.min.js',
				'assets/bower_components/Magnific-Popup-master/dist/jquery.magnific-popup.min.js',
				'assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
				'assets/bower_components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.id.min.js',
				'assets/js/library/export.js',
				'app/views/form_export/js/initFormStartEndDate.js',
				'app/views/form_export/js/initFormMonthsYear.js',
				'app/views/sub_kas_kecil/js/initView.js',
			);

			$config = array(
				'title' => 'Menu Sub Kas Kecil - Detail',
				'property' => array(
					'main' => 'Data Sub Kas Kecil',
					'sub' => 'Detail Data Sub Kas Kecil',
				),
				'css' => $css,
				'js' => $js,
			);

			$status = ($data_detail['status'] == "AKTIF") ? '<span class="label label-success">'.$data_detail['status'].'</span>' : '<span class="label label-danger">'.$data_detail['status'].'</span>';

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
				'saldo' => $this->helper->cetakRupiah($data_detail['saldo']),
				'status' => $status,
			);

			$this->layout('sub_kas_kecil/view', $config, $data);
		}

		/**
		 * 
		 */
		public function delete($id){
			if($_SERVER['REQUEST_METHOD'] == "POST" && $id != ''){
				$id = strtoupper($id);
				if(empty($id) || $id == "") $this->redirect(BASE_URL."sub-kas-kecil/");

				$delete_skk = $this->Sub_kas_kecilModel->delete($id);
				if($delete_skk['success']) {
					$this->success = true;
					$this->notif = array(
						'type' => 'success',
						'title' => 'Pesan Sukses',
						'message' => 'Data Berhasil Dihapus',
					);
				}
				else {
					$this->message = $delete_skk['error'];
					$this->notif = array(
						'type' => 'error',
						'title' => 'Pesan Error',
						'message' => 'Terjadi Kesalahan Teknis, Silahkan Coba Kembali',
					);
				}

				echo json_encode(array(
					'success' => $this->success,
					'message' => $this->message,
					'notif' => $this->notif
				));
			}
			else $this->redirect();	
		}

		/**
		 * 
		 */
		public function get_last_id(){
			$data = !empty($this->Sub_kas_kecilModel->getLastID()['id']) ? $this->Sub_kas_kecilModel->getLastID()['id'] : false;

			if(!$data) $id = 'LOG001';
			else{
				$kode = 'LOG';
				$noUrut = (int)substr($data, 3, 3);
				$noUrut++;

				$id = $kode.sprintf("%03s", $noUrut);
			}

			echo $id;
		}

		/**
		 * Aksi Export data sub kas kecil
		 */
		public function export() {}

		/**
		 * 
		 */
		public function get_mutasi($id){
			$id = strtoupper($id);
			
			// config datatable
			$config_dataTable = array(
				'tabel' => 'mutasi_saldo_sub_kas_kecil',
				'kolomOrder' => array(null, 'tgl', 'uang_masuk', 'uang_keluar', 'saldo', 'ket'),
				'kolomCari' => array('tgl', 'uang_masuk', 'uang_keluar', 'saldo', 'ket'),
				'orderBy' => array('id' => 'desc'),
				'kondisi' => 'WHERE id_sub_kas_kecil = "'.$id.'"',
				// 'kondisi' => false,
			);

			$dataMutasi = $this->DataTableModel->getAllDataTable($config_dataTable);
			// var_dump($dataMutasi);
			$data = array();
			$no_urut = $_POST['start'];
			foreach($dataMutasi as $row){
				$no_urut++;
				
				$dataRow = array();
				$dataRow[] = $no_urut;
				$dataRow[] = $this->helper->cetakTgl($row['tgl'], 'full');
				$dataRow[] = $this->helper->cetakRupiah($row['uang_masuk']);
				$dataRow[] = $this->helper->cetakRupiah($row['uang_keluar']);
				$dataRow[] = $this->helper->cetakRupiah($row['saldo']);
				$dataRow[] = $row['ket'];

				$data[] = $dataRow;
			}

			$output = array(
				'draw' => $_POST['draw'],
				'recordsTotal' => $this->DataTableModel->recordTotal(),
				'recordsFiltered' => $this->DataTableModel->recordFilter(),
				'data' => $data,
			);

			echo json_encode($output);
		}

		/**
		 * 
		 */
		public function get_history_pengajuan($id){
			$id = strtoupper($id);
			
			// config datatable
			$config_dataTable = array(
				'tabel' => 'v_pengajuan_sub_kas_kecil_v2',
				'kolomOrder' => array(null, 'tgl', 'total', 'dana_disetujui', 'status'),
				'kolomCari' => array('tgl', 'total', 'dana_disetujui', 'status'),
				'orderBy' => array('id' => 'desc'),
				'kondisi' => 'WHERE id_sub_kas_kecil = "'.$id.'"',
			);

			$dataMutasi = $this->DataTableModel->getAllDataTable($config_dataTable);
			// var_dump($dataMutasi);
			$data = array();
			$no_urut = $_POST['start'];
			foreach($dataMutasi as $row){
				$no_urut++;

				switch ($row['status']) {
					case 'PENDING':
						$status = '<span class="label label-primary">PENDING</span>';
						break;
					
					case 'PERBAIKI':
						$status = '<span class="label label-warning">PERBAIKI</span>';
						break;

					case 'DISETUJUI':
						$status = '<span class="label label-success">DISETUJUI</span>';
						break;
					
					case 'LANGSUNG':
						$status = '<span class="label label-success">LANGSUNG</span>';
						break;	

					case 'DITOLAK':
						$status = '<span class="label label-danger">DITOLAK</span>';
						break;

					default:
						$status = $row['status'];
						break;
				}

				// button aksi
				$aksiDetail = '<button onclick="getView('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-info btn-flat" title="Lihat Detail"><i class="fa fa-eye"></i></button>';
				$aksi = '<div class="btn-group">'.$aksiDetail.'</div>';
				
				$dataRow = array();
				$dataRow[] = $no_urut;
				$dataRow[] = $this->helper->cetakTgl($row['tgl'], 'full');
				$dataRow[] = $this->helper->cetakRupiah($row['total']);
				$dataRow[] = $this->helper->cetakRupiah($row['dana_disetujui']);
				$dataRow[] = $status;
				$dataRow[] = $aksi;

				$data[] = $dataRow;
			}

			$output = array(
				'draw' => $_POST['draw'],
				'recordsTotal' => $this->DataTableModel->recordTotal(),
				'recordsFiltered' => $this->DataTableModel->recordFilter(),
				'data' => $data,
			);

			echo json_encode($output);
		}

		/**
		 * Fungsi set_validation
		 * method yang berfungsi untuk validasi inputan secara server side
		 * param $data didapat dari post yang dilakukan oleh user
		 * return berupa array, status hasil pengecekan dan error tiap validasi inputan
		 */
		private function set_validation($data){
			$required = ($data['action'] == "action-edit") ? 'not_required' : 'required';

			// ID
			$this->validation->set_rules($data['id'], 'ID Sub Kas Kecil', 'id', 'string | 1 | 255 | required');
			// nama
			$this->validation->set_rules($data['nama'], 'Nama Sub Kas Kecil', 'nama', 'string | 1 | 255 | required');
			// alamat
			$this->validation->set_rules($data['alamat'], 'Alamat', 'alamat', 'string | 1 | 255 | not_required');
			// no_telp
			$this->validation->set_rules($data['no_telp'], 'No. Telepon', 'no_telp', 'angka | 1 | 20 | required');
			// email
			$this->validation->set_rules($data['email'], 'Email', 'email', 'email | 1 | 255 | required');
			// saldo awal
			$this->validation->set_rules($data['saldo'], 'Saldo Awal', 'saldo', 'nilai | 0 | 99999999999 | not_required');
			// status
			$this->validation->set_rules($data['status'], 'Status Sub Kas Kecil', 'status', 'string | 1 | 255 | required');
			// password
			$this->validation->set_rules($data['password'], 'Password', 'password', 'string | 5 | 255 | '.$required);
			// password_confirm
			$this->validation->set_rules($data['password_confirm'], 'Password Confirm', 'password_confirm', 'string | 5 | 255 | '.$required);

			return $this->validation->run();
		}
	}