<?php 
Defined("BASE_PATH") or die(ACCESS_DENIED);

/**
 * Class Bank
 * Extend Abstract Crud_modalsAbstract
 */
class Bank extends Controller
{

	private $success = false;
	private $notif = array();
	private $error = array();
	private $message = NULL;

	/**
	 * Method __construct
	 * Default load saat pertama kali controller diakses
	 */
	public function __construct() {
		$this->auth();
		$this->auth->cekAuth();
		$this->model('BankModel');
		$this->model('DataTableModel');
		$this->helper();
		$this->validation();
	}	

	/**
	 * Method index
	 * Render list bank
	 */
	public function index() {
		if($_SESSION['sess_level'] === 'KAS BESAR' 
			|| $_SESSION['sess_level'] === 'OWNER') { $this->list(); } 
		else { $this->helper->requestError(403); }
	}

	/**
	 * Method list
	 * Proses menampilkan list semua data bank
	 */
	private function list() {
		// set config untuk layouting
		$css = array(
			'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
			'assets/bower_components/select2/dist/css/select2.min.css',
		);
		$js = array(
			'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
			'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
			'assets/plugins/input-mask/jquery.inputmask.bundle.js',
			'assets/bower_components/select2/dist/js/select2.full.min.js',
			'assets/js/library/export.js',
			'app/views/bank/js/initList.js',
			'app/views/bank/js/initForm.js',
		);

		$config = array(
			'title' => 'Menu Bank',
			'property' => array(
				'main' => 'Data Bank',
				'sub' => 'List Semua Data Bank',
			),
			'css' => $css,
			'js' => $js,
		);

		$this->layout('bank/list', $config, $data = null);
	}	

	/**
	 * Method get_list
	 * Proses get data untuk list bank
	 * Data akan di parsing dalam bentuk dataTable
	 * @return output {object} array berupa json
	 */
	public function get_list() {
		if($_SERVER['REQUEST_METHOD'] == "POST" && ($_SESSION['sess_level'] === 'KAS BESAR' 
			|| $_SESSION['sess_level'] === 'OWNER')) {
			// config datatable
			$config_dataTable = array(
				'tabel' => 'bank',
				'kolomOrder' => array(null, 'nama', 'saldo', 'status', null),
				'kolomCari' => array('nama', 'saldo', 'status'),
				'orderBy' => array('id' => 'asc'),
				'kondisi' => false,
			);

			$dataBank = $this->DataTableModel->getAllDataTable($config_dataTable);

			$data = array();
			$no_urut = $_POST['start'];
			foreach($dataBank as $row){
				$no_urut++;

				$status = ($row['status'] == "AKTIF") ? '<span class="label label-success">'.$row['status'].'</span>' : 
														'<span class="label label-danger">'.$row['status'].'</span>';

				// button aksi
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
				$dataRow[] = $row['nama'];
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
		else { $this->helper->requestError(403, true); }				
	}

	/**
	 * Method action_add
	 * Proses penambahan data bank
	 * @return output {object} array berupa json
	 */
	public function action_add() {
		if($_SERVER['REQUEST_METHOD'] == "POST" && $_SESSION['sess_level'] === 'KAS BESAR') {
			$data = isset($_POST) ? $_POST : false;

			if(!$data) {
				$this->notif = array(
					'type' => 'error',
					'title' => "Pesan Gagal",
					'message' => "Terjadi Kesalahan Teknis, Silahkan Coba Kembali",
				);
			}
			else{
				// validasi data
				$validasi = $this->set_validation($data);
				$cek = $validasi['cek'];
				$this->error = $validasi['error'];

				if($cek){
					// validasi inputan
					$data_insert = array(
						'nama' => $this->validation->validInput($data['nama']),
						'saldo' => $this->validation->validInput($data['saldo']),
						'status' => $this->validation->validInput($data['status']),
						'created_by' => $_SESSION['sess_email']
					);

					// insert bank
					$insert_bank = $this->BankModel->insert($data_insert);
					if($insert_bank['success']) {
						$this->success = true;
						$this->notif = array(
							'type' => 'success',
							'title' => "Pesan Berhasil",
							'message' => "Tambah Data Bank Baru Berhasil",
						);
					}
					else {
						$this->notif = array(
							'type' => 'error',
							'title' => "Pesan Gagal",
							'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
						);
						$this->message = $insert_bank['error'];
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
				'notif' => $this->notif,
				'error' => $this->error,
				'message' => $this->message
				// 'data' => $data
			);

			echo json_encode($output);	
		}
		else { $this->helper->requestError(403, true); }
	}

	/**
	 * Method edit
	 * Proses get data bank untuk di edit
	 * @param id {string}
	 * @return data {object} array berupa json
	 */
	public function edit($id) {
		if($_SERVER['REQUEST_METHOD'] == "POST" && $_SESSION['sess_level'] === 'KAS BESAR') {
			$id = strtoupper($id);
			$data = !empty($this->BankModel->getById($id)) ? $this->BankModel->getById($id) : false;
			
			echo json_encode($data);
		}
		else { $this->helper->requestError(403, true); }
	}

	/**
	 * Method action_edit
	 * Proses pengeditan data bank
	 * @return output {object} array berupa json
	 */
	public function action_edit() {
		if($_SERVER['REQUEST_METHOD'] == "POST" && $_SESSION['sess_level'] === 'KAS BESAR') {
			$data = isset($_POST) ? $_POST : false;

			if(!$data) {
				$notif = array(
					'type' => 'error',
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
						'id' => $this->validation->validInput($data['id']),
						'nama' => $this->validation->validInput($data['nama']),
						'status' => $this->validation->validInput($data['status']),
						'modified_by' => $_SESSION['sess_email']
					);

					// update bank
					$update_bank = $this->BankModel->update($data);
					if($update_bank['success']) {
						$this->success = true;
						$this->notif = array(
							'type' => 'success',
							'title' => "Pesan Berhasil",
							'message' => "Edit Data Bank Berhasil",
						);
					}
					else {
						$this->notif = array(
							'type' => 'error',
							'title' => "Pesan Gagal",
							'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
						);
						$this->message = $update_bank['error'];
					}
				}
				else {
					$notif = array(
						'type' => 'warning',
						'title' => "Pesan Pemberitahuan",
						'message' => "Silahkan Cek Kembali Form Isian",
					);
				}
			}

			$output = array(
				'success' => $this->success,
				'notif' => $this->notif,
				'error' => $this->error,
				'message' => $this->message
				// 'data' => $data
			);

			echo json_encode($output);
		}
		else { $this->helper->requestError(403, true); }
	}

	/**
	 * Method detail
	 * Proses get data detail bank dan di render langsung ke view
	 * @param id {string}
	 */
	public function detail($id) {
		if($_SESSION['sess_level'] === 'KAS BESAR' || $_SESSION['sess_level'] === 'OWNER') {
			$id = strtoupper($id);
			$data_detail = !empty($this->BankModel->getById($id)) ? $this->BankModel->getById($id) : false;

			if((empty($id) || $id == "") || !$data_detail) { $this->redirect(BASE_URL."bank/"); }

			$css = array(
				'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
				'assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
				'assets/bower_components/select2/dist/css/select2.min.css',
			);
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'assets/bower_components/select2/dist/js/select2.full.min.js',
				'assets/plugins/input-mask/jquery.inputmask.bundle.js',
				'assets/js/library/export.js',
				'app/views/form_export/js/initFormStartEndDate.js',
				'app/views/bank/js/initView.js'
			);

			$config = array(
				'title' => 'Menu Bank - Detail',
				'property' => array(
					'main' => 'Data Bank',
					'sub' => 'Detail Data Bank',
				),
				'css' => $css,
				'js' => $js,
			);

			$status = ($data_detail['status'] == "AKTIF") ? 
				'<span class="label label-success">'.$data_detail['status'].'</span>' : 
				'<span class="label label-danger">'.$data_detail['status'].'</span>';

			$data = array(
				'id_bank' => $data_detail['id'],
				'nama' => $data_detail['nama'],
				'saldo' => $this->helper->cetakRupiah($data_detail['saldo']),
				'status' => $status,
			);

			$this->layout('bank/view', $config, $data);
		}
		else { $this->helper->requestError(403); }
	}

	/**
	 * Method delete
	 * Proses hapus data bank
	 * @param id {string}
	 * @return result 
	 */
	public function delete($id) {
		if($_SERVER['REQUEST_METHOD'] == "POST" && $_SESSION['sess_level'] === 'KAS BESAR') {
			$id = strtoupper($id);
			if(empty($id) || $id == "") { $this->redirect(BASE_URL."bank/"); }

			$delete_bank = $this->BankModel->delete($id);
			if($delete_bank['success']){ 
				$this->success = true;
				$this->notif = array(
					'type' => 'success',
					'title' => 'Pesan Sukses',
					'message' => 'Data Berhasil Dihapus',
				);
			}
			else{
				$this->message = $delete_bank['error'];
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
	 * Method get_mutasi
	 * Proses get data mutasi bank sesuai dengan id bank
	 * Data akan di parsing dalam bentuk dataTable
	 * @param id {string}
	 * @return result {object} array berupa json
	 */
	public function get_mutasi($id){
		if($_SERVER['REQUEST_METHOD'] == "POST" && ($_SESSION['sess_level'] === 'KAS BESAR' 
			|| $_SESSION['sess_level'] === 'OWNER')) {
			
			// config datatable
			$config_dataTable = array(
				'tabel' => 'mutasi_bank',
				'kolomOrder' => array(null, 'tgl', 'uang_masuk', 'uang_keluar', 'saldo', 'ket'),
				'kolomCari' => array('tgl', 'uang_masuk', 'uang_keluar', 'saldo', 'ket'),
				'orderBy' => array('id' => 'desc'),
				'kondisi' => 'WHERE id_bank = '.$id.' ',
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
		else { $this->helper->requestError(403, true); }
	}

	/**
	 * Method set_validation
	 * Proses validasi inputan
	 * @param data {array}
	 * @return result {array}
	 */
	private function set_validation($data) {
		$required = ($data['action'] == "action-edit") ? 'not_required' : 'required';

		// nama bank
		$this->validation->set_rules($data['nama'], 'Nama Bank', 'nama', 'string | 1 | 255 | required');
		// saldo awal
		$this->validation->set_rules($data['saldo'], 'Saldo Awal Bank', 'saldo', 'nilai | 0 | 99999999999 | '.$required);
		// status
		$this->validation->set_rules($data['status'], 'Status Bank', 'status', 'string | 1 | 255 | required');

		return $this->validation->run();
	}

}