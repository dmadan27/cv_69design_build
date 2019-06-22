<?php
	Defined("BASE_PATH") or die(ACCESS_DENIED);
	
	/**
	 * 
	 */
	class Kas_kecil extends Crud_modalsAbstract
	{

		private $success = false;
		private $notif = array();
		private $error = array();
		private $message = NULL;

		/**
		 * 
		 */
		public function __construct() {
			$this->auth();
			$this->auth->cekAuth();
			$this->model('Kas_kecilModel');
			$this->model('DataTableModel');
			$this->helper();	
			$this->validation();
			$this->excel();
		}	

		/**
		 * 
		 */
		public function index() {
			$this->list();
		}

		/**
		 * 
		 */
		protected function list() {
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
				'app/views/kas_kecil/js/initList.js',
				'app/views/kas_kecil/js/initForm.js',
					
			);

			$config = array(
				'title' => 'Menu Kas Kecil',
				'property' => array(
					'main' => 'Data Kas Kecil',
					'sub' => 'List Semua Data Kas Kecil',
				),
				'css' => $css,
				'js' => $js,
			);

			$this->layout('kas_kecil/list', $config, $data = null);
		}	

		/**
		 * 
		 */
		public function get_list() {
			if($_SERVER['REQUEST_METHOD'] == "POST") {
				// config datatable
				$config_dataTable = array(
					'tabel' => 'kas_kecil',
					'kolomOrder' => array(null, 'id', 'nama', 'alamat', 'no_telp',  'saldo', 'status',null),
					'kolomCari' => array('id','nama', 'alamat', 'no_telp',  'saldo', 'status'),
					'orderBy' => array('id' => 'asc'),
					'kondisi' => false,
				);

				$datakaskecil = $this->DataTableModel->getAllDataTable($config_dataTable);

				$data = array();
				$no_urut = $_POST['start'];
				foreach($datakaskecil as $row){
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
					$dataRow[] = $row['no_telp'];
					$dataRow[] = $row['saldo'];
					$dataRow[] = $row['status'];
					
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
			else { $this->redirect(); }
		}

		/**
		 * 
		 */
		public function action_add() {
			if($_SERVER['REQUEST_METHOD'] == "POST") {
				$data = isset($_POST) ? $_POST : false;
				$foto = isset($_FILES['foto']) ? $_FILES['foto'] : false;

				$cekFoto = true;

				if(!$data) {
					$this->notif = array(
						'type' => 'error',
						'title' => "Pesan Gagal",
						'message' => "Terjadi Kesalahan Teknis, Silahkan Coba Kembali",
					);
				}
				else {
					$validasi = $this->set_validation($data);
					$cek = $validasi['cek'];
					$this->error = $validasi['error'];
					
					// cek password dan konf password
					if(($this->error['password'] == "") && ($data['password'] != $data['password_confirm'])) {
						$cek = false;
						$this->error['password'] = $this->error['password_confirm'] = 'Password dan Konfirmasi Password Berbeda';
					}

					// jika upload foto
					if($foto) {
						$configFoto = array(
							'jenis' => 'gambar',
							'error' => $foto['error'],
							'size' => $foto['size'],
							'name' => $foto['name'],
							'tmp_name' => $foto['tmp_name'],
							'max' => 2*1048576,
						);
						$validasiFoto = $this->validation->validFile($configFoto);
						if(!$validasiFoto['cek']) {
							$cek = false;
							$this->error['foto'] = $validasiFoto['error'];
						}
						else { $valueFoto = md5($data['id']).$validasiFoto['namaFile']; }
					}
					else { $valueFoto = NULL; }

					if($cek) {
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
							'password' => password_hash($this->validation->validInput($data['password'], false), PASSWORD_BCRYPT),
							'created_by' => $_SESSION['sess_email']
						);

						// jika upload foto
						if($foto) {
							$path = ROOT.DS.'assets'.DS.'images'.DS.'user'.DS.$valueFoto;
							if(!move_uploaded_file($foto['tmp_name'], $path)) {
								$this->error['foto'] = "Upload Foto Gagal";
								$this->success = $cekFoto = false;
							}
						}

						if($cekFoto) {
							// cek email 
							if($this->Kas_kecilModel->checkExistEmail($data['email'])) {
								// insert data
								$insert = $this->Kas_kecilModel->insert($data);
								if($insert['success']) {
									$this->success = true;
									$this->notif = array(
										'type' => 'success',
										'title' => "Pesan Berhasil",
										'message' => "Tambah Data  Kas Kecil Baru Berhasil",
									);
								}
								else {
									$this->message = $insert['error'];
									$this->notif = array(
										'type' => 'error',
										'title' => "Pesan Gagal",
										'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
									);
									$path = ROOT.DS.'assets'.DS.'images'.DS.'user'.DS.$valueFoto;
									$this->helper->rollback_file($path, false);
								}
							}
							else {
								$this->notif = array(
									'type' => 'warning',
									'title' => "Pesan Pemberitahuan",
									'message' => "Silahkan Cek Kembali Form Isian",
								);
								$this->error['email'] = "Email telah digunakan sebelumnya";
							}
						}
					}
					else {
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
					'message' => $this->message,
					'data' => $data,
					'foto' => $foto
				);

				echo json_encode($output);

			}
			else { $this->redirect(); }
		}

		/**
		 * Function edit
		 * method untuk get data edit
		 * param $id didapat dari url
		 * return berupa json
		 */
		public function edit($id) {
			if($_SERVER['REQUEST_METHOD'] == "POST") {
				$id = strtoupper($id);
				$data = !empty($this->Kas_kecilModel->getById($id)) ? $this->Kas_kecilModel->getById($id) : false;

				if((empty($id) || $id == "") || !$data) { $this->redirect(BASE_URL."kas-kecil/"); }

				echo json_encode($data);
			}
			else { $this->redirect(); }
		}

		/**
		 * 
		 */
		public function action_edit() {
			if($_SERVER['REQUEST_METHOD'] == "POST") {
				$data = isset($_POST) ? $_POST : false;
		
				if(!$data) {
					$this->notif = array(
						'type' => "error",
						'title' => "Pesan Gagal",
						'message' => "Terjadi Kesalahan Teknis, Silahkan Coba Kembali",
					);
				}
				else {
					// validasi data
					$validasi = $this->set_validation($data);
					$cek = $validasi['cek'];
					$this->error = $validasi['error'];

					if($cek) {
						// validasi inputan
						$data = array(
							'id' =>  $this->validation->validInput($data['id']),
							'nama' =>  $this->validation->validInput($data['nama']),
							'alamat' =>  $this->validation->validInput($data['alamat']),
							'no_telp' =>  $this->validation->validInput($data['no_telp']),
							'email' =>  $this->validation->validInput($data['email'], false),
							'status' =>  $this->validation->validInput($data['status']),
							'modified_by' => $_SESSION['sess_email']
						);

						// update db
						$update = $this->Kas_kecilModel->update($data);
						if($update['success']) {
							$this->success = true;
							$this->notif = array(
								'type' => "success",
								'title' => "Pesan Berhasil",
								'message' => "Edit Data Kas Kecil Berhasil",
							);
						}
						else {
							$this->message = $update['error'];
							$this->notif = array(
								'type' => "error",
								'title' => "Pesan Gagal",
								'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
							);
						}
					}
					else {
						$this->notif = array(
							'type' => "warning",
							'title' => "Pesan Pemberitahuan",
							'message' => "Silahkan Cek Kembali Form Isian",
						);
					}
				}

				$output = array(
					'success' => $this->success,
					'notif' => $this->notif,
					'error' => $this->error,
					'message' => $this->message,
					'data' => $data
				);

				echo json_encode($output);
			}
			else { $this->redirect(); }
		}
		
		/**
		 * Function detail
		 * method untuk get data detail dan setting layouting detail
		 * param $id didapat dari url
		 */
		public function detail($id) {
			$id = strtoupper($id);
			$data_detail = !empty($this->Kas_kecilModel->getById($id)) ? $this->Kas_kecilModel->getById($id) : false;

			if((empty($id) || $id == "") || !$data_detail) { $this->redirect(BASE_URL."kas-kecil/"); }

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
				'app/views/kas_kecil/js/initView.js',
			);

			$config = array(
				'title' => 'Menu Kas Kecil - Detail',
				'property' => array(
					'main' => 'Data Kas Kecil',
					'sub' => 'Detail Data Kas Kecil',
				),
				'css' => $css,
				'js' => $js,
			);

			$status = ($data_detail['status'] == "AKTIF") ? '<span class="label label-success">'.$data_detail['status'].'</span>' : '<span class="label label-danger">'.$data_detail['status'].'</span>';

			// validasi foto
			if(!empty($data_detail['foto'])) {
				// cek foto di storage
				$filename = ROOT.DS.'assets'.DS.'images'.DS.'user'.DS.$data_detail['foto'];
				if(!file_exists($filename)) 
					{ $foto = BASE_URL.'assets/images/user/default.jpg'; }
				else
					{ $foto = BASE_URL.'assets/images/user/'.$data_detail['foto']; }
			}
			else { $foto = BASE_URL.'assets/images/user/default.jpg'; }

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

			$this->layout('kas_kecil/view', $config, $data);
		}

		/**
		 * 
		 */
		public function delete($id) {
			if($_SERVER['REQUEST_METHOD'] == "POST" && $id != '') {
				$id = strtoupper($id);
				if(empty($id) || $id == "") $this->redirect(BASE_URL."kas-kecil/");

				$delete_kasKecil = $this->Kas_kecilModel->delete($id);
				if($delete_kasKecil['success']) {
					$this->success = true;
					$this->notif = array(
						'type' => 'success',
						'title' => 'Pesan Sukses',
						'message' => 'Data Berhasil Dihapus',
					);
				}
				else {
					$this->message = $delete_kasKecil['error'];
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
			else { $this->redirect(); }
		}

		/**
		 * 
		 */
		public function get_last_id() {
			if($_SERVER['REQUEST_METHOD'] == "POST") {
				$data = !empty($this->Kas_kecilModel->getLastID()['id']) ? $this->Kas_kecilModel->getLastID()['id'] : false;

				if(!$data) { $id = 'KK001'; }
				else {
					$kode = 'KK';
					$noUrut = (int)substr($data, 2, 3);
					$noUrut++;

					$id = $kode.sprintf("%03s", $noUrut);
				}

				echo json_encode($id);			
			}
			else { $this->redirect(); }
		}

		/**
		 * Export data ke format Excel
		 */
		public function export() {
			$row = $this->Kas_kecilModel->export();
			$header = array_keys($row[0]); 

			$this->excel->setProperty('kas_kecil','kas_kecil','kas_kecil');
			$this->excel->setData($header, $row);
			$this->excel->getData('kas_kecil', 'kas_kecil', 4, 5 );

			$this->excel->getExcel('kas_kecil');
		}

		/**
		 * Function get_mutasi
		 * method yang berfungsi untuk get data mutasi bank sesuai dengan id
		 * dipakai di detail data
		 */
		public function get_mutasi($id) {
			if($_SERVER['REQUEST_METHOD'] == "POST") {
				
				// config datatable
				$config_dataTable = array(
					'tabel' => 'mutasi_saldo_kas_kecil',
					'kolomOrder' => array(null, 'tgl', 'uang_masuk', 'uang_keluar', 'saldo', 'ket'),
					'kolomCari' => array('tgl', 'uang_masuk', 'uang_keluar', 'saldo', 'ket'),
					'orderBy' => array('id' => 'desc'),
					'kondisi' => 'WHERE id_kas_kecil = "'.$id.'"',
				);

				$dataMutasi = $this->DataTableModel->getAllDataTable($config_dataTable);

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
			else $this->redirect();
		}

		public function get_history_pengajuan($id) {
			if($_SERVER['REQUEST_METHOD'] == "POST") {
				
				// config datatable
				$config_dataTable = array(
					'tabel' => 'pengajuan_kas_kecil',
					'kolomOrder' => array(null, 'tgl', 'nama', 'total', 'status'),
					'kolomCari' => array('tgl', 'nama', 'total', 'status'),
					'orderBy' => array('id' => 'desc'),
					'kondisi' => 'WHERE id_kas_kecil = "'.$id.'"',
				);

				$dataMutasi = $this->DataTableModel->getAllDataTable($config_dataTable);
				$data = array();
				$no_urut = $_POST['start'];
				foreach($dataMutasi as $row){
					$no_urut++;

					switch ($row['status']) {
						case '0':
							$status = '<span class="label label-primary">PENDING</span>';
							break;
						
						case '1':
							$status = '<span class="label label-warning">PERBAIKI</span>';
							break;
	
						case '2':
							$status = '<span class="label label-success">DISETUJUI</span>';
							break;
	
						case '3':
							$status = '<span class="label label-danger">DITOLAK</span>';
							break;
							
						default:
							$status = $row['status'];
							break;
					}
					
					$dataRow = array();
					$dataRow[] = $no_urut;
					$dataRow[] = $this->helper->cetakTgl($row['tgl'], 'full');
					$dataRow[] = $row['nama'];
					$dataRow[] = $this->helper->cetakRupiah($row['total']);
					$dataRow[] = $status;

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
			else { $this->redirect(); }
		}

		private function set_validation($data) {
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
			$this->validation->set_rules($data['email'], 'Alamat Email', 'email', 'email | 1 | 255 |'.$required);
			// saldo
			$this->validation->set_rules($data['saldo'], 'Saldo Awal', 'saldo', 'nilai | 0 | 99999999999 | '.$required);
			// status
			$this->validation->set_rules($data['status'], 'Status', 'status', 'string | 1 | 255 | required');
			// password
			$this->validation->set_rules($data['password'], 'Password', 'password', 'string | 5 | 255 | '.$required);
			// password_confirm
			$this->validation->set_rules($data['password_confirm'], 'Password Confirm', 'password_confirm', 'string | 5 | 255 | '.$required);

			return $this->validation->run();
		}

}