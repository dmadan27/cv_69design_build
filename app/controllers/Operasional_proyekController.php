<?php

Defined("BASE_PATH") or die(ACCESS_DENIED);

/**
 * 
 */
class Operasional_proyek extends Controller 
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
		$this->model('Operasional_proyekModel');
		$this->model('DataTableModel');
		$this->helper();
		$this->validation();
	}

	/**
	 * 
	 */
	public function index() {
		if($_SESSION['sess_level'] === 'KAS BESAR' 
			|| $_SESSION['sess_level'] === 'OWNER') { $this->list(); } 
		else { $this->helper->requestError(403); }
	}

	/**
	 * 
	 */
	protected function list() {
		$css = array(
			'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
			'assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
			'assets/bower_components/select2/dist/css/select2.min.css',
		);
		$js = array(
			'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
			'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
			'assets/plugins/input-mask/jquery.inputmask.bundle.js',
			'assets/bower_components/select2/dist/js/select2.full.min.js',
			'assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
			'assets/bower_components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.id.min.js',
			'assets/js/library/export.js',
			'app/views/form_export/js/initFormStartEndDate.js',
			'app/views/operasional_proyek/js/initList.js',
		);

		$config = array(
			'title' => 'Menu Operasional Proyek',
			'property' => array(
				'main' => 'Data Operasional Proyek',
				'sub' => 'List Semua Data Operasional Proyek',
			),
			'css' => $css,
			'js' => $js,
		);

		$this->layout('operasional_proyek/list', $config, $data = NULL);
	}

	/**
	 * 
	 */
	public function get_list() {
		if($_SERVER['REQUEST_METHOD'] == "POST" && ($_SESSION['sess_level'] === 'KAS BESAR' 
			|| $_SESSION['sess_level'] === 'OWNER')) {

			$config_dataTable = array(
				'tabel' => 'operasional_proyek',
				'kolomOrder' => array(null, 'id', 'tgl', 'nama', 'id_proyek', 'id_kas_besar', 'id_distributor', 'total', null),
				'kolomCari' => array('id', 'id_proyek', 'id_kas_besar', 'id_distributor', 'tgl', 'nama', 'total'),
				'orderBy' => array('id' => 'asc'),
				'kondisi' => false,
			);

			$dataOperasionalProyek = $this->DataTableModel->getAllDataTable($config_dataTable);

			$data = array();
			$no_urut = $_POST['start'];
			foreach($dataOperasionalProyek as $row){
				$no_urut++;

				// $status = ($row['status'] == "AKTIF") ? '<span class="label label-success">'.$row['status'].'</span>' : '<span class="label label-danger">'.$row['status'].'</span>';

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
				
				$jenis_pembayaran = ($row['status'] == 'TUNAI') ? 
					'<span class="label label-success">'.$row['status'].'</span>' : 
					'<span class="label label-primary">'.$row['status'].'</span>';
				
				$status = ($row['status_lunas'] == 'LUNAS') ? 
					'<span class="label label-success">'.$row['status_lunas'].'</span>' : 
					'<span class="label label-danger">'.$row['status_lunas'].'</span>';

				$dataRow = array();
				$dataRow[] = $no_urut;
				$dataRow[] = $row['id'];
				$dataRow[] = $this->helper->cetakTgl($row['tgl'], 'full');
				$dataRow[] = $row['nama'];
				$dataRow[] = $row['id_proyek'];
				$dataRow[] = $jenis_pembayaran;
				$dataRow[] = $status;
				$dataRow[] = $this->helper->cetakRupiah($row['total']);
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
	 * 
	 */
	public function form($id) {
		if($_SESSION['sess_level'] === 'KAS BESAR') {
			if($id)	{ $this->edit(strtoupper($id)); }
			else { $this->add(); }
		}
		else { $this->helper->requestError(403); }
	}

	/**
	 * 
	 */
	protected function add() {
		$css = array(
			'assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
			'assets/bower_components/select2/dist/css/select2.min.css',
				'assets/plugins/iCheck/all.css',

		);
		$js = array(
			'assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
			'assets/bower_components/select2/dist/js/select2.full.min.js',
			'assets/plugins/iCheck/icheck.min.js',
			'assets/plugins/input-mask/jquery.inputmask.bundle.js',
			'app/views/operasional_proyek/js/initForm.js',	
		);

		$config = array(
			'title' => 'Menu Operasional Proyek - Form Tambah',
			'property' => array(
				'main' => 'Data Operasional Proyek',
				'sub' => 'Form Tambah Data',
			),
			'css' => $css,
			'js' => $js,
		);

		$data = array(
			'action' => 'action-add',
			'id' => '',	
		);

		$this->layout('operasional_proyek/form', $config, $data);
	}

	/**
	 * 
	 */
	public function action_add() {
		if($_SERVER['REQUEST_METHOD'] == "POST" && $_SESSION['sess_level'] === 'KAS BESAR') {
			$data = isset($_POST) ? $_POST : false;
			$dataOperasionalProyek = isset($_POST['dataOperasionalProyek']) ? json_decode($_POST['dataOperasionalProyek'], true) : false;
			$dataDetail = isset($_POST['listDetail']) ? json_decode($_POST['listDetail'], true) : false;
			
			if(!$data){
				$this->notif = array(
					'type' => "error",
					'title' => "Pesan Gagal",
					'message' => "Terjadi kesalahan teknis, silahkan coba kembali",
				);
			}
			else {
				// validasi data
				$validasi = $this->set_validation($dataOperasionalProyek, $data['action']);
				$cek = $validasi['cek'];
				$this->error = $validasi['error'];
				
				if($cek){
					$keterangan = 'OPERASIONAL PROYEK ['.$dataOperasionalProyek['id'].'] - '.strtoupper($dataOperasionalProyek['nama']);

					// validasi input
					$dataOperasionalProyek = array(
						'id' => $this->validation->validInput($dataOperasionalProyek['id']),
						'id_proyek' => $this->validation->validInput($dataOperasionalProyek['id_proyek']),
						'id_bank' => $this->validation->validInput($dataOperasionalProyek['id_bank']),
						'id_kas_besar' => $_SESSION['sess_id'],
						'id_distributor' => $this->validation->validInput($dataOperasionalProyek['id_distributor']),
						'tgl' => $this->validation->validInput($dataOperasionalProyek['tgl']),
						'nama' => $this->validation->validInput($dataOperasionalProyek['nama']),
						'jenis' => $this->validation->validInput($dataOperasionalProyek['jenis']),
						'total' => $this->validation->validInput($dataOperasionalProyek['total']),
						'sisa' => $this->validation->validInput($dataOperasionalProyek['sisa']),
						'status' => $this->validation->validInput($dataOperasionalProyek['status']),
						'status_lunas' => $this->validation->validInput($dataOperasionalProyek['status_lunas']),
						'ket' => $this->validation->validInput($dataOperasionalProyek['ket']),
						'keterangan' => $keterangan,
					);

					$dataInsert = array(
						'dataOperasionalProyek' => $dataOperasionalProyek,
						'listDetail'=>$dataDetail
					);
					
					$res = $this->Operasional_proyekModel->insert($dataInsert);
					if($res['success']) {
						$this->success = true;
						$_SESSION['notif'] = array(
							'type' => "success",
							'title' => "Pesan Berhasil",
							'message' => "Tambah Data Operasional Proyek Baru Berhasil",
						);
						$this->notif['default'] = $_SESSION['notif'];

					} 
					else if($res['invalidtotaldetail'] == "invalidTotal") {

						$this->notif['default'] = array(
							'type' => "warning",
							'title' => "Pesan Pemberitahuan",
							'message' => "Cek Kembali List Detail / Total Detail Anda",
						);

					} 
					else {

						$this->notif['default'] = array(
							'type' => "error",
							'title' => "Pesan Gagal",
							'message' => "Terjadi kesalahan teknis, silahkan coba kembali",
						);

					}
				} 
				else {
					$this->notif['default'] = array(
						'type' => 'warning',
						'title' => "Pesan Pemberitahuan",
						'message' => "Silahkan Cek Kembali Form Isian",
					);
				}
				
			}

			$output = array(
				'status' => $this->success,
				'notif' => $this->notif,
				'error' => $this->error,
				'cek' => array(
					'cek' => $cek,
				),
				'data' => $data,
				'dataOperasionalProyek' => $dataOperasionalProyek,
			);
			echo json_encode($output);
		}
		else { $this->helper->requestError(403, true); }
	}

	/**
	 * 
	 */
	protected function edit($id){
		$id = strtoupper($id);

		$dataOperasionalProyek = !empty($this->Operasional_proyekModel->getById($id)) ? $this->Operasional_proyekModel->getById($id) :false;
		// $id_bank = !empty($this->Operasional_proyekModel->getBankById($id)) ? $this->Operasional_proyekModel->getBankById($id) :false;

		if(empty($id) || $id == "") $this->redirect(BASE_URL."operasional-proyek/");

		$css = array(
			'assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
			'assets/bower_components/select2/dist/css/select2.min.css',
			'assets/plugins/bootstrap-slider/slider.css',
		);
		$js = array(
			'assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
			'assets/bower_components/select2/dist/js/select2.full.min.js',
			'assets/plugins/input-mask/jquery.inputmask.bundle.js',
			'assets/plugins/bootstrap-slider/bootstrap-slider.js',
			'app/views/operasional_proyek/js/initForm.js',	
		);

		$config = array(
			'title' => 'Menu Operasional Proyek - Form Edit',
			'property' => array(
				'main' => 'Data Operasional Proyek',
				'sub' => 'Form Edit Data',
			),
			'css' => $css,
			'js' => $js,
		);

		if($dataOperasionalProyek) {
			$data = array(
				'action' => 'action-edit',
				'id' => $dataOperasionalProyek['id'],
				// 'id_proyek'=> $dataOperasionalProyek['id_proyek'],
				// 'id_bank'=> $id_bank['id_bank'],
				// 'id_kas_besar'=> $dataOperasionalProyek['id_kas_besar'],
				// 'id_distributor'=> $dataOperasionalProyek['id_distributor'],
				// 'tgl'=> $dataOperasionalProyek['tgl'],
				// 'nama'=> $dataOperasionalProyek['nama'],
				// 'jenis'=> $dataOperasionalProyek['jenis'],
				// 'total'=> $dataOperasionalProyek['total'],
				// 'sisa'=> $dataOperasionalProyek['sisa'],
				// 'status'=> $dataOperasionalProyek['status'],
				// 'status_lunas'=> $dataOperasionalProyek['status_lunas'],
				// 'ket'=> $dataOperasionalProyek['ket'],
			);

			$this->layout('operasional_proyek/form', $config, $data);
		}
		else { $this->helper->requestError(404); }
	}

	/**
	 * Method get edit
	 * Request berupa POST dan output berupa JSON
	 * Parameter id => id proyek
	 */
	public function get_edit($id){
		if($_SERVER['REQUEST_METHOD'] == "POST" && $_SESSION['sess_level'] === 'KAS BESAR'){
			$id = strtoupper($id);
			if(empty($id) || $id == "") $this->helper->requestError(403, true);

			$dataOperasionalProyek = $this->Operasional_proyekModel->getById($id);
			$dataDetail = $this->Operasional_proyekModel->getDetailById($id);

			$id_bank = !empty($this->Operasional_proyekModel->getBankById($id)) ? $this->Operasional_proyekModel->getBankById($id) :false;

			$dataOperasionalProyek['id_bank'] = $id_bank['id_bank'];

			$dataDetail_new = array();
			foreach($dataDetail as $row) {
				$temp = $row;
				$temp['tgl_detail_full'] = $this->helper->cetakTgl($row['tgl_detail'], 'full');
				$temp['total_detail_full'] = $this->helper->cetakRupiah($row['total_detail']);

				$dataDetail_new[] = $temp;
			}

			$output = array(
				'dataOperasionalProyek' => $dataOperasionalProyek,
				'dataDetail' => $dataDetail_new
			);

			echo json_encode($output);
		}
		else { $this->helper->requestError(403); }
	}

	/**
	 * 
	 */
	public function action_edit(){
		if($_SERVER['REQUEST_METHOD'] == "POST" && $_SESSION['sess_level'] === 'KAS BESAR') {
			$data = isset($_POST) ? $_POST :false;
			$dataOperasionalProyek = isset($_POST['dataOperasionalProyek']) ? json_decode($_POST['dataOperasionalProyek'], true) : false;
			$dataDetail = isset($_POST['listDetail']) ? json_decode($_POST['listDetail'], true) : false;	
			$dataDetailTambahan = isset($_POST['listDetail_Tambahan']) ? json_decode($_POST['listDetail_Tambahan'], true) : false;
			$toDeleteList = isset($_POST['toDelete']) ? json_decode($_POST['toDelete'], true) : false;
			$toEditList = isset($_POST['toEdit']) ? json_decode($_POST['toEdit'], true) : false;

			$error = $notif = array();
			if(!$data) {
				$notif = array(
					'title' => "Pesan Gagal",
					'message' => "Terjadi kesalahan teknis, silahkan coba kembali",
				);
			}
			else {
				$validasi = $this->set_validation($dataOperasionalProyek, $data['action']);
				$cek = $validasi['cek'];
				$error = $validasi['error'];

				if($cek) {
					$keterangan = 'OPERASIONAL PROYEK ['.$dataOperasionalProyek['id'].'] - '.strtoupper($dataOperasionalProyek['nama']);
					
					$dataOperasionalProyek = array(
						'id' => $this->validation->validInput($dataOperasionalProyek['id']),
						'id_proyek' => $this->validation->validInput($dataOperasionalProyek['id_proyek']),
						'id_bank' => $this->validation->validInput($dataOperasionalProyek['id_bank']),
						'id_kas_besar' => $_SESSION['sess_id'],
						'id_distributor' => $this->validation->validInput($dataOperasionalProyek['id_distributor']),
						'tgl' => $this->validation->validInput($dataOperasionalProyek['tgl']),
						'nama' => $this->validation->validInput($dataOperasionalProyek['nama']),
						'jenis' => $this->validation->validInput($dataOperasionalProyek['jenis']),
						'total' => $this->validation->validInput($dataOperasionalProyek['total']),
						'sisa' => $this->validation->validInput($dataOperasionalProyek['sisa']),
						'status' => $this->validation->validInput($dataOperasionalProyek['status']),
						'status_lunas' => $this->validation->validInput($dataOperasionalProyek['status_lunas']),
						'ket' => $this->validation->validInput($dataOperasionalProyek['ket']),
						'keterangan' => $keterangan,
					);

					$dataUpdate = array(
							'dataOperasionalProyek' => $dataOperasionalProyek,
							'dataDetail' => $dataDetail,
							'dataDetailTambahan' => $dataDetailTambahan,
							'toDelete' => $toDeleteList,
							'toEdit' => $toEditList
					);
					$res = $this->Operasional_proyekModel->update($dataUpdate);
					// update data
					if($res['success']){

						$this->success = true;
							$_SESSION['notif'] = array(
								'type' => "success",
								'title' => "Pesan Berhasil",
								'message' => "Edit Data Proyek Berhasil",
							);
							$notif['default'] = $_SESSION['notif'];

					} 
					else if($res['invalidtotaldetail'] == "invalidTotal") {

						$this->notif['default'] = array(
							'type' => "warning",
							'title' => "Pesan Pemberitahuan",
							'message' => "Cek Kembali List Detail / Total Detail Anda",
						);

					} 
					else {

						$this->notif['default'] = array(
							'type' => "error",
							'title' => "Pesan Gagal",
							'message' => "Terjadi kesalahan teknis, silahkan coba kembali",
						);

					}
				}

			}

			$output = array(
				'status' => $this->success,
				'notif' => $this->notif,
				'error' => $this->error,
				'cek' => array(
					'cek' => $cek,
				),
				'dataOperasionalProyek' => $dataOperasionalProyek,
			);

			echo json_encode($output);			
		}
		else { $this->helper->requestError(403, true); }
	}

	/**
	 * 
	 */
	public function detail($id){
		if($_SESSION['sess_level'] === 'KAS BESAR' || $_SESSION['sess_level'] === 'OWNER') {
			$id = strtoupper($id);
			
			$dataOperasionalProyek = !empty($this->Operasional_proyekModel->getById_fromView($id)) ? $this->Operasional_proyekModel->getById_fromView($id) : false;
			
			if((empty($id) || $id == "") || !$dataOperasionalProyek) { $this->redirect(BASE_URL."operasional-proyek/"); }

			$css = array(
				'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
				'assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
				'assets/bower_components/select2/dist/css/select2.min.css',
			);
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'assets/plugins/input-mask/jquery.inputmask.bundle.js',
				'assets/bower_components/select2/dist/js/select2.full.min.js',
				'assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
				'assets/bower_components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.id.min.js',
				'assets/js/library/export.js',
				'app/views/operasional_proyek/js/initView.js',
			);

			$config = array(
				'title' => 'Menu Operasional Proyek - Detail',
				'property' => array(
					'main' => 'Data Operasional Proyek',
					'sub' => 'Detail Data Operasional Proyek',
				),
				'css' => $css,
				'js' => $js,
			);

			$parsing_dataOperasionalProyek = array(
				'id' => $dataOperasionalProyek['id'],
				'id_proyek' => $dataOperasionalProyek['id_proyek'],
				'nama_pembangunan' => $dataOperasionalProyek['nama_pembangunan'],
				'tgl_operasional' => $this->helper->cetakTgl($dataOperasionalProyek['tgl_operasional'], 'full'),
				'nama_pembangunan' => $dataOperasionalProyek['nama_pembangunan'],
				'id_kas_besar' => $dataOperasionalProyek['id_kas_besar'],
				'nama_kas_besar' => $dataOperasionalProyek['nama_kas_besar'],
				'id_distributor' => $dataOperasionalProyek['id_distributor'],
				'nama_distributor' => $dataOperasionalProyek['nama_distributor'],
				'nama_operasional' => $dataOperasionalProyek['nama_operasional'],
				'jenis_pembayaran' => (strtolower($dataOperasionalProyek['jenis_pembayaran']) == 'tunai') ?
					'<span class="label label-success">'.$dataOperasionalProyek['jenis_pembayaran'].'</span>' :
					'<span class="label label-warning">'.$dataOperasionalProyek['jenis_pembayaran'].'</span>',
				'jenis_operasional' => $dataOperasionalProyek['jenis_operasional'],
				'total' => $this->helper->cetakRupiah($dataOperasionalProyek['total']),
				'sisa_operasional' => $this->helper->cetakRupiah($dataOperasionalProyek['sisa_operasional']),
				'status_lunas' => (strtolower($dataOperasionalProyek['status_lunas']) == 'lunas') ?
					'<span class="label label-success">'.$dataOperasionalProyek['status_lunas'].'</span>' :
					'<span class="label label-danger">'.$dataOperasionalProyek['status_lunas'].'</span>',
				'keterangan' => $dataOperasionalProyek['keterangan']
				
			);
			
			$data = array(
				'data_operasionalProyek' => $parsing_dataOperasionalProyek,
				'id' => $id
			);

			$this->layout('operasional_proyek/view', $config, $data);
		}
		else { $this->helper->requestError(403); }
	}

	/**
	 * 
	 */
	public function get_list_detail($id) {
		if($_SERVER['REQUEST_METHOD'] == "POST" && ($_SESSION['sess_level'] === 'KAS BESAR' 
			|| $_SESSION['sess_level'] === 'OWNER')) {
			$id = strtoupper($id);
			// config datatable
			$config_dataTable = array(
				'tabel' => 'v_detail_operasional_proyek',
				'kolomOrder' => array(null, 'tgl', 'nama', 'nama_bank', 'total'),
				'kolomCari' => array('tgl', 'nama', 'nama_bank', 'total'),
				'orderBy' => array('tgl' => 'desc'),
				'kondisi' => 'WHERE id_operasional_proyek = "'.$id.'"',
			);

			$dataDetail = $this->DataTableModel->getAllDataTable($config_dataTable);

			$data = array();
			$no_urut = $_POST['start'];
			foreach($dataDetail as $row){
				$no_urut++;
				
				$dataRow = array();
				$dataRow[] = $no_urut;
				$dataRow[] = $this->helper->cetakTgl($row['tgl'], 'full');
				$dataRow[] = $row['nama'];
				$dataRow[] = $row['nama_bank'];
				$dataRow[] = $this->helper->cetakRupiah($row['total']);

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
	 * 
	 */
	public function delete($id){
		if($_SERVER['REQUEST_METHOD'] == "POST" && $_SESSION['sess_level'] === 'KAS BESAR') {
			$id = strtoupper($id);
			
			if(empty($id) || $id == "") { $this->redirect(BASE_URL."operasional-proyek/"); }

			$dataOperasionalProyek = $this->Operasional_proyekModel->getById($id);
			$dataDetail = $this->Operasional_proyekModel->getDetailById($id);

			$data = array(
				'dataOperasionalProyek' => $dataOperasionalProyek,
				'dataDetail' => $dataDetail
			);

			$delete = $this->Operasional_proyekModel->delete($data);

			if($delete['success']){
				$this->success = true;
				$this->notif = array(
					'type' => "success",
					'title' => "Pesan Berhasil",
					'message' => "Hapus Data Operasional Proyek Baru Berhasil",
				);
			} else {
				$this->notif = array(
					'type' => "error",
					'title' => "Pesan Gagal",
					'message' => "Terjadi kesalahan teknis, silahkan coba kembali",
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
			$proyek = isset($_POST['id_proyek']) && !empty($_POST['id_proyek']) ? 
				$this->validation->validInput($_POST['id_proyek']) : '-[ID_PROYEK]-';
			$operasionalProyek = isset($_POST['id_operasional_proyek']) && !empty($_POST['id_operasional_proyek']) ? 
				$this->validation->validInput($_POST['id_operasional_proyek']) : false;
			
			$increment_number = '';

			if(!$operasionalProyek) {
				$increment = $this->IncrementModel->get_increment('operasional_proyek');
            
				if($increment['success']) {
					$getMask = explode('-', $increment['mask']);
					$increment_number = $getMask[0].date('Y').$proyek.sprintf("%04s", $increment['increment']);
				}
			}
			else {
				// OPR2019-[ID_PROYEK]-0051
				$temp_id_operasional_proyek = explode('-', $operasionalProyek);
				if(count($temp_id_operasional_proyek) > 1) {
					$increment_number = $temp_id_operasional_proyek[0].$proyek.$temp_id_operasional_proyek[2];
				}
				else {
					$increment_number = substr($operasionalProyek, 0, 7).$proyek.substr($operasionalProyek, 18, 4);
				}
			}

            echo json_encode($increment_number);
		}
		else { $this->helper->requestError(403, true); }
	}

	/**
	 * 
	 */
	public function get_nama_proyek($filterById = false) {
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			$this->model('ProyekModel');
			
			$dataProyek = ($filterById && !empty($filterById)) ? $this->ProyekModel->getById($filterById) : $this->ProyekModel->getAll();
			$data = array();

			if($filterById && !empty($filterById)) {
				$data[] = array(
					'id' => $dataProyek['id'],
					'text' => $dataProyek['id'].' - '.$dataProyek['pembangunan']
				);
			}
			else {
				foreach($dataProyek as $row){
					$dataRow = array();
					$dataRow['id'] = $row['id'];
					$dataRow['text'] = $row['id'].' - '.$row['pembangunan'];

					$data[] = $dataRow;
				}
			}
			

			echo json_encode($data);
		}
		else { $this->helper->requestError(403, true); }
	}

	/**
	 * Method get_bank
	 * Proses get data bank yang aktif untuk keperluan select
	 * @return data {object} array berupa json
	 */
	public function get_nama_bank(){
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			$data_bank = $this->Operasional_proyekModel->get_selectBank();
			$data = array();

			foreach($data_bank as $row){
				$dataRow = array();
				$dataRow['id'] = $row['id'];
				$dataRow['text'] = $row['nama'];

				$data[] = $dataRow;
			}

			echo json_encode($data);
		}
		else { $this->helper->requestError(403, true); }
	}
	

	/**
	 * 
	 */
	public function get_nama_kas_besar($id = false){
		$this->model('Kas_besarModel');
		$data_kas_besar = (!$id) ? $this->Kas_besarModel->getAll() : $this->Kas_besarModel->getById($id);
		$data = array();

		if(!$id){
			foreach ($data_kas_besar as $row) {
				$dataRow = array();
				$dataRow['id'] = $row['id'];
				$dataRow['text'] = $row['id'].' - '.$row['nama'];

				$data[] = $dataRow;
			}
		}
		else{
			$data[] = array(
				'id' => $data_kas_besar['id'],
					'text' => $data_kas_besar['id'].' - '.$data_kas_besar['nama']
				);
		}
			

		echo json_encode($data);
	}
	
	/**
	 * 
	 */
	public function get_nama_distributor(){
		$this->model('DistributorModel');
		$data_distributor = $this->DistributorModel->getAll();
		$data = array();

		foreach ($data_distributor as $row) {
			$dataRow = array();
			$dataRow['id'] = $row['id'];
			$dataRow['text'] = $row['id'].' - '.$row['nama'];

			$data[] = $dataRow;
		}

		echo json_encode($data);
	}

	/**
	 * 
	 */
	public function action_add_detail(){
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$data = isset($_POST) ? $_POST : false;
		
			$validasi = $this->set_validation_detail($data);
			$cek = $validasi['cek'];
			$this->error = $validasi['error'];

			// print_r($data);
			// exit;

			if($cek) {
				$this->success = true;
				$data['index'] = (int)$data['index'];
				$data['delete'] = $data['delete'] === 'true'? true: false;;
				$data['tgl_detail_full'] = $this->helper->cetakTgl($data['tgl_detail'], 'full');
				$data['total_detail_full'] = $this->helper->cetakRupiah($data['total_detail']);
			}

			$output = array(
				'status' => $this->success,
				'error' => $this->error,
				'data' => $data,
			);
			// print_r($output);
			// exit;
			echo json_encode($output);
		}
	}

	/**
	 * 
	 */
	public function get_list_history_pembelian($id) {
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$config_dataTable = array(
				'tabel' => 'v_history_pembelian_operasionalproyek',
				'kolomOrder' => array('id', 'tgl', 'nama', 'total', 'status_lunas', 'ID_DISTRIBUTOR', 'NAMA_DISTRIBUTOR','pemilik'),
				'kolomCari' => array('id', 'tgl', 'nama', 'total', 'status_lunas', 'ID_DISTRIBUTOR', 'NAMA_DISTRIBUTOR','pemilik'),
				'orderBy' => array('id' => 'desc'),
				'kondisi' => 'WHERE id = "'.$id.'"',
			);

			$dataHistoryPembelanjaan = $this->DataTableModel->getAllDataTable($config_dataTable);
			
			$data = array();
			// $no_urut = $_POST['start'];
			foreach($dataHistoryPembelanjaan as $row){					
				
				$dataRow = array();
				// $dataRow[] = $no_urut;
				$dataRow[] = $row['id'];
				$dataRow[] = $this->helper->cetakTgl($row['tgl'], 'full');
				$dataRow[] = $row['nama'];
				$dataRow[] = $this->helper->cetakRupiah($row['total']);
				$dataRow[] = $row['status_lunas'];
				$dataRow[] = $row['ID_DISTRIBUTOR'];
				$dataRow[] = $row['NAMA_DISTRIBUTOR'];
				$dataRow[] = $row['pemilik'];

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
		else{ $this->redirect(); }
	}

	/**
	 * Function validasi form utama
	 */
	private function set_validation($data, $action){
		// $required = ($action =="action-add") ? 'not_required' : 'required';

		if($action == "action-add"){
		
			// id
			$this->validation->set_rules($data['id'], 'ID Operasional Proyek', 'id', 'string | 1 | 255 | required');
			// id_proyek
			$this->validation->set_rules($data['id_proyek'], 'ID proyek', 'id_proyek', 'string | 1 | 255 | required');
			// id_distributor
			// $this->validation->set_rules($data['id_distributor'], 'ID Distributor', 'id_distributor', 'string | 1 | 255 |');
			
			// if($data['status_lunas'] == "LUNAS"){
				//id_bank
				// $this->validation->set_rules($data['id_bank'], 'ID Bank', 'id_bank_form', 'string | 1 | 255 | required');
			// } else {
				//id_bank
				// $this->validation->set_rules($data['id_bank'], 'ID Bank', 'id_bank_form', 'string | 1 | 255 |');
			// }

			// tgl
			$this->validation->set_rules($data['tgl'], 'Tanggal Operasional Proyek', 'tgl', 'string | 1 | 255 | required');
			// nama
			$this->validation->set_rules($data['nama'], 'Nama Pengajuan', 'nama', 'string | 1 | 255 | required');
			// jenis
			$this->validation->set_rules($data['jenis'], 'Jenis Pengajuan', 'jenis', 'string | 1 | 255 | required');
			// total
			$this->validation->set_rules($data['total'], 'Total Pengajuan', 'total', 'nilai | 1 | 99999999999 | required');
			// sisa
			$this->validation->set_rules($data['sisa'], 'Sisa Pengajuan', 'sisa', 'nilai | 0 | 99999999999 | required');
			// status
			$this->validation->set_rules($data['jenis'], 'Status Pengajuan', 'status', 'string | 1 | 255 | required');
			// status lunas
			$this->validation->set_rules($data['status_lunas'], 'Status Lunas Pengajuan', 'status_lunas', 'string | 1 | 255 | required');
			// keterangan
			$this->validation->set_rules($data['ket'], 'Keterangan Pengajuan', 'ket', 'string | 1 | 255 | required');

			return $this->validation->run();
		
		} else if($action == "action-edit"){

			// id
			$this->validation->set_rules($data['id'], 'ID Operasional Proyek', 'id', 'string | 1 | 255 | required');
			// id_proyek
			$this->validation->set_rules($data['id_proyek'], 'ID proyek', 'id_proyek_f', 'string | 1 | 255 | required');
			// id_distributor
			// $this->validation->set_rules($data['id_distributor'], 'ID Distributor', 'id_distributor', 'string | 1 | 255 |');
			
			// if($data['status_lunas'] == "LUNAS"){
				//id_bank
				// $this->validation->set_rules($data['id_bank'], 'ID Bank', 'id_bank_form', 'string | 1 | 255 | required');
			// } else {
				//id_bank
				// $this->validation->set_rules($data['id_bank'], 'ID Bank', 'id_bank_form', 'string | 1 | 255 |');
			// }
			
			// tgl
			$this->validation->set_rules($data['tgl'], 'Tanggal Operasional Proyek', 'tgl', 'string | 1 | 255 | required');
			// nama
			$this->validation->set_rules($data['nama'], 'Nama Pengajuan', 'nama', 'string | 1 | 255 | required');
			// jenis
			$this->validation->set_rules($data['jenis'], 'Jenis Pengajuan', 'jenis', 'string | 1 | 255 | required');
			// total
			$this->validation->set_rules($data['total'], 'Total Pengajuan', 'total', 'nilai | 1 | 99999999999 | required');
			// sisa
			$this->validation->set_rules($data['sisa'], 'Sisa Pengajuan', 'sisa', 'nilai | 0 | 99999999999 | required');
			// status
			$this->validation->set_rules($data['jenis'], 'Status Pengajuan', 'status', 'string | 1 | 255 | required');
			// status lunas
			$this->validation->set_rules($data['status_lunas'], 'Status Lunas Pengajuan', 'status_lunas', 'string | 1 | 255 | required');
			// keterangan
			$this->validation->set_rules($data['ket'], 'Keterangan Pengajuan', 'ket', 'string | 1 | 255 | required');

			return $this->validation->run();

		}

	}

	/**
	 * Function validasi form detail
	 */
	private function set_validation_detail($data){
		//id_bank
		$this->validation->set_rules($data['id_bank'], 'ID Bank', 'id_bank', 'string | 1 | 255 | required');
		// nama
		$this->validation->set_rules($data['nama_detail'], 'Nama Kebutuhan', 'nama_detail', 'string | 1 | 255 | required');
		// tgl
		$this->validation->set_rules($data['tgl_detail'], 'Tanggal Operasional', 'tgl_detail', 'string | 1 | 255 | required');
		// total
		$this->validation->set_rules($data['total_detail'], 'Total', 'total_detail', 'nilai | 1 | 9999999999 | required');
		
		return $this->validation->run();
	}

}