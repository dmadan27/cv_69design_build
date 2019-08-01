<?php
Defined("BASE_PATH") or die(ACCESS_DENIED);
	
/**
 * 
 */
class Kas_besar extends Controller 
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
		$this->model('Kas_besarModel');
		$this->model('DataTableModel');
		$this->helper();
		$this->validation();
	}	

	/**
	 * Method __construct
	 * Default load saat pertama kali controller diakses
	 */
	public function index() {
		if($_SESSION['sess_level'] === 'KAS BESAR' 
			|| $_SESSION['sess_level'] === 'OWNER') { $this->list(); }
		else { $this->helper->requestError(403); }
	}

	/**
	 * Method index
	 * Render list kas besar
	 */
	private function list() {
		$css = array(
			'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
			'assets/bower_components/dropify/dist/css/dropify.min.css',
			'assets/bower_components/select2/dist/css/select2.min.css',
		);
		$js = array(
			'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
			'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
			'assets/bower_components/dropify/dist/js/dropify.min.js',
			'assets/plugins/input-mask/jquery.inputmask.bundle.js',
			'assets/bower_components/select2/dist/js/select2.full.min.js',
			'assets/js/library/export.js',
			'app/views/kas_besar/js/initList.js',
			'app/views/kas_besar/js/initForm.js',
		);

		$config = array(
			'title' => 'Menu Kas Besar',
			'property' => array(
				'main' => 'Data Kas Besar',
				'sub' => 'List Semua Data Kas Besar',
			),
			'css' => $css,
			'js' => $js,
		);

		$this->layout('kas_besar/list', $config, $data = null);
	}

	/**
	 * Method get_list
	 * Proses get data untuk list kas besar
	 * Data akan di parsing dalam bentuk dataTable
	 * @return output {object} array berupa json
	 */
	public function get_list() {
		if($_SERVER['REQUEST_METHOD'] == "POST" && ($_SESSION['sess_level'] === 'KAS BESAR' 
			|| $_SESSION['sess_level'] === 'OWNER')) {
			// config datatable
			$config_dataTable = array(
				'tabel' => 'kas_besar',
				'kolomOrder' => array(null, 'id', 'nama', 'no_telp', 'email', 'status', null),
				'kolomCari' => array('id', 'nama', 'alamat', 'no_telp', 'email', 'status'),
				'orderBy' => array('id' => 'desc', 'status' => 'asc'),
				'kondisi' => false,
			);

			$dataKasBesar = $this->DataTableModel->getAllDataTable($config_dataTable);

			$data = array();
			$no_urut = $_POST['start'];
			foreach($dataKasBesar as $row){
				$no_urut++;

				$status = (strtolower($row['status']) == "aktif") ? 
					'<span class="label label-success">'.$row['status'].'</span>' : '<span class="label label-danger">'.$row['status'].'</span>';

				//button aksi
				$aksiDetail = '<button onclick="getView('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-info btn-flat" title="Lihat Detail"><i class="fa fa-eye"></i></button>';
				$aksiEdit = '<button onclick="getEdit('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-success btn-flat" title="Edit Data"><i class="fa fa-pencil"></i></button>';
				$aksiHapus = '<button onclick="getDelete('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-danger btn-flat" title="Hapus Data"><i class="fa fa-trash"></i></button>';


				if($_SESSION['sess_level'] === 'KAS BESAR') {
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
		else { $this->helper->requestError(403, true); }
	}	

	/**
	 * Method action_add
	 * Proses penambahan data kas besar
	 * @return output {object} array berupa json
	 */
	public function action_add() {
		if($_SERVER['REQUEST_METHOD'] == "POST" && $_SESSION['sess_level'] === 'KAS BESAR') {
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
				if(($this->error['password'] == "") && ($data['password'] != $data['password_confirm'])){
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
					$dataInsert = array(
						'id' => $this->validation->validInput($data['id']),
						'nama' => $this->validation->validInput($data['nama']),
						'alamat' => $this->validation->validInput($data['alamat']),
						'no_telp' => $this->validation->validInput($data['no_telp']),
						'email' => $this->validation->validInput($data['email'], false),
						'foto' => $this->validation->validInput($valueFoto, false),
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
						if($this->Kas_besarModel->checkExistEmail($dataInsert['email'])) {
							// insert data
							$insert = $this->Kas_besarModel->insert($dataInsert);
							if($insert['success']) {
								$this->success = true;
								$this->notif = array(
									'type' => 'success',
									'title' => "Pesan Berhasil",
									'message' => "Tambah Data  Kas Besar Baru Berhasil",
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
				// 'status_foto' => $cekFoto,
				'notif' => $this->notif,
				'error' => $this->error,
				'message' => $this->message,
				// 'data' => $data,
				'foto' => $foto
			);

			echo json_encode($output);
		}
		else { $this->helper->requestError(403, true); }
	}

	/**
	 * Function edit
	 * method untuk get data edit
	 * param $id didapat dari url
	 * return berupa json
	 */
	public function edit($id) {
		if($_SERVER['REQUEST_METHOD'] == "POST" && $_SESSION['sess_level'] === 'KAS BESAR') {
			$id = strtoupper($id);
			$data = !empty($this->Kas_besarModel->getById($id)) ? $this->Kas_besarModel->getById($id) : false;

			if((empty($id) || $id == "") || !$data) { $this->redirect(BASE_URL."kas-besar/"); }
			
			echo json_encode($data);
		}
		else { $this->helper->requestError(403, true); }
	}

	/**
	 * Function action_edit
	 * method untuk aksi edit data
	 * return berupa json
	 * status => status berhasil atau gagal proses edit
	 * notif => pesan yang akan ditampilkan disistem
	 * error => error apa saja yang ada dari hasil validasi
	 */
	public function action_edit() {
		if($_SERVER['REQUEST_METHOD'] == "POST" && $_SESSION['sess_level'] === 'KAS BESAR') {
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
					$update = $this->Kas_besarModel->update($data);
					if($update['success']) {
						$this->success = true;
						$this->notif = array(
							'type' => "success",
							'title' => "Pesan Berhasil",
							'message' => "Edit Data Kas Besar Berhasil",
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
				// 'data' => $data
			);

			echo json_encode($output);
		}
		else { $this->helper->requestError(403, true); }
			
	}

	/**
	 * Function detail
	 * method untuk get data detail dan setting layouting detail
	 * param $id didapat dari url
	 */
	public function detail($id) {
		if($_SESSION['sess_level'] === 'KAS BESAR' || $_SESSION['sess_level'] === 'OWNER') {
			$id = strtoupper($id);
			$data_detail = !empty($this->Kas_besarModel->getById($id)) ? $this->Kas_besarModel->getById($id) : false;

			if(!$data_detail || (empty($id) || $id == "")) { $this->redirect(BASE_URL."kas-besar/"); }

			$css = array(
				'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
				'assets/bower_components/dropify/dist/css/dropify.min.css',
				'assets/bower_components/Magnific-Popup-master/dist/magnific-popup.css',
			);
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'assets/bower_components/dropify/dist/js/dropify.min.js',
				'assets/bower_components/Magnific-Popup-master/dist/jquery.magnific-popup.min.js',
				'app/views/kas_besar/js/initView.js',
			);

			$config = array(
				'title' => 'Menu Kas Besar - Detail',
				'property' => array(
					'main' => 'Data Kas Besar',
					'sub' => 'Detail Data Kas Besar',
				),
				'css' => $css,
				'js' => $js,
			);

			$status = ($data_detail['status'] == "AKTIF") ? 
				'<span class="label label-success">'.$data_detail['status'].'</span>' : 
				'<span class="label label-danger">'.$data_detail['status'].'</span>';
			
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
				'status' => $status,
			);

			$this->layout('kas_besar/view', $config, $data);
		}
		else { $this->helper->requestError(403); }
	}

	/**
	 * Function delete
	 * method yang berfungsi untuk menghapus data
	 * param $id didapat dari url
	 * return json
	 */
	public function delete($id) {
		if($_SERVER['REQUEST_METHOD'] == "POST" && $_SESSION['sess_level'] === 'KAS BESAR') {
			$id = strtoupper($id);
			if(empty($id) || $id == "") { $this->redirect(BASE_URL."kas-besar/"); }
			
			$delete_kasBesar = $this->Kas_besarModel->delete($id);
			if($delete_kasBesar['success']) {
				$this->success = true;
				$this->notif = array(
					'type' => 'success',
					'title' => 'Pesan Sukses',
					'message' => 'Data Berhasil Dihapus',
				);
			}
			else {
				$this->message = $delete_kasBesar['error'];
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
		else { $this->helper->requestError(403, true); }
	}

	/**
	 * Method getIncrement
	 */
	public function get_increment() {
		if($_SERVER['REQUEST_METHOD'] == "POST" && $_SESSION['sess_level'] === 'KAS BESAR') {

			$this->model('IncrementModel');
            $increment_number = '';
            $increment = $this->IncrementModel->get_increment('kas_besar');
            
            if($increment['success']) {
                $getMask = explode('-', $increment['mask']);
                $increment_number = $getMask[0].'-'.sprintf("%03s", $increment['increment']);
            }

            echo json_encode($increment_number);
		}
		else { $this->helper->requestError(403, true); }
	}

	/**
	 * Fungsi set_validation
	 * method yang berfungsi untuk validasi inputan secara server side
	 * param $data didapat dari post yang dilakukan oleh user
	 * return berupa array, status hasil pengecekan dan error tiap validasi inputan
	 */
	private function set_validation($data) {
		$required = ($data['action'] == "action-edit") ? 'not_required' : 'required';

		// ID
		$this->validation->set_rules($data['id'], 'ID Kas Kecil', 'id', 'string | 1 | 255 | required');
		// nama
		$this->validation->set_rules($data['nama'], 'Nama', 'nama', 'string | 1 | 255 | required');
		// alamat
		$this->validation->set_rules($data['alamat'], 'Alamat Proyek', 'alamat', 'string | 1 | 255 | not_required');
		// no_telp
		$this->validation->set_rules($data['no_telp'], 'Nomor Telepon', 'no_telp', 'angka | 1 | 255 | required');
		// email
		$this->validation->set_rules($data['email'], 'Alamat Email', 'email', 'email | 1 | 255 | '.$required);
		// status
		$this->validation->set_rules($data['status'], 'Status', 'status', 'string | 1 | 255 | required');
		// password
		$this->validation->set_rules($data['password'], 'Password', 'password', 'string | 5 | 255 | '.$required);
		// password_confirm
		$this->validation->set_rules($data['password_confirm'], 'Password Confirm', 'password_confirm', 'string | 5 | 255 | '.$required);

		return $this->validation->run();
	}

}