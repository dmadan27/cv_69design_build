<?php

Defined("BASE_PATH") or die(ACCESS_DENIED);

/**
 * 
 */
class Operasional_proyek extends CrudAbstract{

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
		$this->model('Operasional_proyekModel');
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
	public function get_list(){
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			// config datatable
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
				
				$aksi = '<div class="btn-group">'.$aksiDetail.$aksiEdit.$aksiHapus.'</div>';
				
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
		else $this->redirect();	
	}

	/**
	 * 
	 */
	public function form($id){
		if($id)	$this->edit(strtoupper($id));
		else $this->add();

	}

	/**
	 * 
	 */
	protected function add(){
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
			'id_proyek' => '',
			'id_bank' => '',
			'id_kas_besar' => '',
			'id_distributor' => '',
			'tgl' => '',
			'nama' => '',
			'jenis' => '',
			'total' => '',
			'sisa' => '',
			'status' => '',
			'status_lunas' => '',
			'ket' => '',	
		);

		$this->layout('operasional_proyek/form', $config, $data);
	}

	/**
	 * 
	 */
	public function action_add(){
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$data = isset($_POST) ? $_POST : false;
			$dataOperasionalProyek = isset($_POST['dataOperasionalProyek']) ? json_decode($_POST['dataOperasionalProyek'], true) : false;
			// print_r($dataOperasionalProyek);
			// exit;
			$dataDetail = isset($_POST['listDetail']) ? json_decode($_POST['listDetail'], true) : false;
			
			if(!$data){
				
				$this->notif = array(
					'type' => "error",
					'title' => "Pesan Gagal",
					'message' => "Terjadi kesalahan teknis, silahkan coba kembali",
				);
			} else {
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
					if($res['success']){
						$this->success = true;
						$_SESSION['notif'] = array(
							'type' => "success",
							'title' => "Pesan Berhasil",
							'message' => "Tambah Data Operasional Proyek Baru Berhasil",
						);
						$this->notif['default'] = $_SESSION['notif'];

					} else if($res['invalidtotaldetail'] == "invalidTotal") {

						$this->notif['default'] = array(
							'type' => "warning",
							'title' => "Pesan Pemberitahuan",
							'message' => "Cek Kembali List Detail / Total Detail Anda",
						);

					} else {

						$this->notif['default'] = array(
							'type' => "error",
							'title' => "Pesan Gagal",
							'message' => "Terjadi kesalahan teknis, silahkan coba kembali",
						);

					}
				} else {
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
		else $this->redirect();
	}

	/**
	 * 
	 */
	protected function edit($id){
		$id = strtoupper($id);

		$dataOperasionalProyek = !empty($this->Operasional_proyekModel->getById($id)) ? $this->Operasional_proyekModel->getById($id) :false;
		$id_bank = !empty($this->Operasional_proyekModel->getBankById($id)) ? $this->Operasional_proyekModel->getBankById($id) :false;

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

		$data = array(
			'action' => 'action-edit',
			'id' => $dataOperasionalProyek['id'],
			'id_proyek'=> $dataOperasionalProyek['id_proyek'],
			'id_bank'=> $id_bank['id_bank'],
			'id_kas_besar'=> $dataOperasionalProyek['id_kas_besar'],
			'id_distributor'=> $dataOperasionalProyek['id_distributor'],
			'tgl'=> $dataOperasionalProyek['tgl'],
			'nama'=> $dataOperasionalProyek['nama'],
			'jenis'=> $dataOperasionalProyek['jenis'],
			'total'=> $dataOperasionalProyek['total'],
			'sisa'=> $dataOperasionalProyek['sisa'],
			'status'=> $dataOperasionalProyek['status'],
			'status_lunas'=> $dataOperasionalProyek['status_lunas'],
			'ket'=> $dataOperasionalProyek['ket'],
		);

		$this->layout('operasional_proyek/form', $config, $data);
	}

	/**
	 * Method get edit
	 * Request berupa POST dan output berupa JSON
	 * Parameter id => id proyek
	 */
	public function get_edit($id){
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$id = strtoupper($id);
			if(empty($id) || $id == "") $this->redirect(BASE_URL."operasional-proyek/");

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
		else $this->redirect();	
	}

	/**
	 * 
	 */
	public function action_edit(){
		if($_SERVER['REQUEST_METHOD'] == "POST") {
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
		} else $this->redirect();
	}

	/**
	 * 
	 */
	public function detail($id){
		$id = strtoupper($id);
		$dataOperasionalProyek = !empty($this->Operasional_proyekModel->getById_fromView($id)) ? $this->Operasional_proyekModel->getById_fromView($id) : false;

		$dataDetailOperasionalProyek = !empty($this->Operasional_proyekModel->getDetailById_fromView($id)) ? $this->Operasional_proyekModel->getDetailById_fromView($id) : false;

		$dataHistoryPembelanjaan = !empty($this->Operasional_proyekModel->getBYid_fromHistoryPembelian($id)) ? $this->Operasional_proyekModel->getBYid_fromHistoryPembelian($id) : false;

		// var_dump($dataHistoryPembelanjaan);

		// if((empty($id) || $id == "") || !$dataOperasionalProyek) $this->redirect(BASE_URL."operasional-proyek/");


		// if((empty($id) || $id == "") || !$dataHistoryPembelanjaan) $this->redirect(BASE_URL."operasional-proyek/");
		

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

		$dataOperasionalProyek = array(

			'id' => $dataOperasionalProyek['id'],
			'id_proyek' =>   $dataOperasionalProyek['id_proyek'],
			'pemilik_proyek' =>   $dataOperasionalProyek['pemilik_proyek'],
			'nama_pembangunan' =>   $dataOperasionalProyek['nama_pembangunan'],
			'id_kas_besar' => $dataOperasionalProyek['id_kas_besar'],
			'nama_kas_besar' => $dataOperasionalProyek['nama_kas_besar'],
			'id_distributor' => $dataOperasionalProyek['id_distributor'],
			'nama_distributor' => $dataOperasionalProyek['nama_distributor'],
			'tgl_pengajuan' => $this->helper->cetakTgl($dataOperasionalProyek['tgl_pengajuan'], 'full'),
			'nama_pengajuan' => $dataOperasionalProyek['nama_pengajuan'],
			'jenis_pengajuan' => $dataOperasionalProyek['jenis_pengajuan'],
			'total_pengajuan' => $this->helper->cetakRupiah($dataOperasionalProyek['total_pengajuan']),
			'sisa_pengajuan' => $this->helper->cetakRupiah($dataOperasionalProyek['sisa_pengajuan']),
			'jenis_pembayaran' => $dataOperasionalProyek['jenis_pembayaran'],
			'status_lunas' => $dataOperasionalProyek['status_lunas'],
			'keterangan' => $dataOperasionalProyek['keterangan'],
			'id_bank' => $dataOperasionalProyek['id_bank'],
			'nama_detail' => $dataOperasionalProyek['nama_detail'],
			'tgl_detail' => $dataOperasionalProyek['tgl_detail'],
			'total_detail' => $this->helper->cetakRupiah($dataOperasionalProyek['total_detail']),
				
		);

		$dataDetailOperasionalProyek = array(
			'id_bank' => $dataOperasionalProyek['id_bank'],
			'nama_detail' => $dataOperasionalProyek['nama_detail'],
			'tgl_detail' => $dataOperasionalProyek['tgl_detail'],
			'total_detail' => $dataOperasionalProyek['total_detail'],
		);

		$dataHistoryPembelanjaan = array(
			'id' => $dataHistoryPembelanjaan['id'],
			'tgl' => $dataHistoryPembelanjaan['tgl'],
			'nama' => $dataHistoryPembelanjaan['nama'],
			'total' => $this->helper->cetakRupiah($dataHistoryPembelanjaan['total']),
			'status_lunas' => $dataHistoryPembelanjaan['status_lunas'],
			'ID_DISTRIBUTOR' => $dataHistoryPembelanjaan['ID_DISTRIBUTOR'],
			'NAMA_DISTRIBUTOR' => $dataHistoryPembelanjaan['NAMA_DISTRIBUTOR'],
			'pemilik' => $dataHistoryPembelanjaan['pemilik'],
				
		);




		// foreach ($this->Operasional_proyekModel->getBYid_fromHistoryPembelian($id) as $row) {
			
		// 	$dataRow = array();
		// 	$dataRow['id'] = $row['id'];
		// 	$dataRow['tgl'] = $row['tgl'];
		// 	$dataRow['nama'] = $row['nama'];
		// 	$dataRow['total'] = $row['total'];
		// 	$dataRow['status_lunas'] = $row['status_lunas'];
		// 	$dataRow['ID_DISTRIBUTOR'] = $row['ID_DISTRIBUTOR'];
		// 	$dataRow['NAMA_DISTRIBUTOR'] = $row['NAMA_DISTRIBUTOR'];
		// 	$dataRow['pemilik'] = $row['pemilik'];
			
		// 	$dataHistoryPembelanjaan[] = $dataRow;
		// }
		
		$data = array(
			'dataOperasionalProyek' => $dataOperasionalProyek,
			'dataDetailOperasionalProyek' => $dataDetailOperasionalProyek,
			'dataHistoryPembelanjaan' => $dataHistoryPembelanjaan,
			'id_operasional_proyek' => $id
		);

		$this->layout('operasional_proyek/view', $config, $data);


	}

	/**
	 * 
	 */
	public function delete($id){
		if($_SERVER['REQUEST_METHOD'] == "POST" && $id != ''){
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
		} else { 
			$this->redirect(); 
		}	
	}

	/**
	 * Export data ke format Excel
	 */
	public function export(){
		$tgl_awal = $_GET['tgl_awal'];
		$tgl_akhir = $_GET['tgl_akhir'];

		$row = $this->Operasional_proyekModel->getExport($tgl_awal, $tgl_akhir);
		$header = array_keys($row[0]); 
	
		$this->excel->setProperty('Laporan Operasional Proyek','Laporan Operasional Proyek','Data Laporan Operasional Proyek');
		$this->excel->setData($header, $row);
		$this->excel->getData('Data Operasional Proyek', 'Data Operasional Proyek', 4, 5 );

		$this->excel->getExcel('Data Operasional Proyek');		
		
	}

	/**
	 * Export data detail ke format Excel
	 */
	public function export_detail(){

		$id = $_GET['id'];
		$tgl_awal = $_GET['tgl_awal'];
		$tgl_akhir = $_GET['tgl_akhir'];

		$row = $this->Operasional_proyekModel->getExportDetail($id, $tgl_awal, $tgl_akhir);
		$header = array_keys($row[0]); 
	
		$this->excel->setProperty('Laporan Detail Operasional Proyek','Laporan Detail Operasional Proyek','Data Laporan Detail Operasional Proyek');
		$this->excel->setData($header, $row);
		$this->excel->getData('Data Detail Operasional Proyek', 'Data Detail Operasional Proyek', 4, 5 );

		$this->excel->getExcel('Data Detail Operasional Proyek');		
		
	}

	/**
	 * Export data detail ke format Excel
	 */
	public function export_history(){

		$id = $_GET['id'];

		$row = $this->Operasional_proyekModel->getExportHistory($id);
		$header = array_keys($row[0]); 
	
		$this->excel->setProperty('Laporan History Pembelian Operasional','Laporan History Pembelian Operasional','Laporan History Pembelian Operasional');
		$this->excel->setData($header, $row);
		$this->excel->getData('Laporan History Pembelian Operasional', 'Laporan History Pembelian Operasional', 4, 5 );

		$this->excel->getExcel('Laporan History Pembelian Operasional');		
		
	}


	/**
	 * 
	 */
	public function get_last_id(){
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$proyek = isset($_POST['get_proyek']) ? $this->validation->validInput($_POST['get_proyek']) : false;

			$id_temp = ($proyek) ? 'OPRY-'.$proyek.'-' : 'OPRY-[ID_PROYEK]-';

			$data = !empty($this->Operasional_proyekModel->getLastID($id_temp)['id']) ? $this->Operasional_proyekModel->getLastID($id_temp)['id'] : false;

			if(!$data) $id = $id_temp.'0001';
			else{
				$noUrut = (int)substr($data, 17, 4);
				$noUrut++;

				$id = $id_temp.sprintf("%04s", $noUrut);
			}

			echo json_encode($id);
		}		

	}

	/**
	 * 
	 */
	public function get_nama_proyek_lama($id = false){
		$this->model('ProyekModel');
		$data_nama_proyek = (!$id) ? $this->ProyekModel->getAll() : $this->ProyekModel->getById($id);
		
		$data = array();

		if(!$id){
			foreach($data_nama_proyek as $row){
				$dataRow = array();
				$dataRow['id'] = $row['id'];
				$dataRow['text'] = $row['id'].' - '.$row['pembangunan'];

				$data[] = $dataRow;
			}
		}
		else{
			$data[] = array(
				'id' => $data_nama_proyek['id'],
					'text' => $data_nama_proyek['id'].' - '.$data_nama_proyek['pembangunan']
				);
		}

		echo json_encode($data);

		// var_dump($data);
	}

	/**
	 * 
	 */
	public function get_nama_proyek(){
		$this->model('ProyekModel');
		$data_nama_proyek = $this->ProyekModel->getAll();
		$data = array();

		foreach($data_nama_proyek as $row){
			$dataRow = array();
			$dataRow['id'] = $row['id'];
			$dataRow['text'] = $row['id'].' - '.$row['pembangunan'];

			$data[] = $dataRow;
		}

		echo json_encode($data);
	}

	/**
	 * Method get_bank
	 * Proses get data bank yang aktif untuk keperluan select
	 * @return data {object} array berupa json
	 */
	public function get_nama_bank(){
		if($_SERVER['REQUEST_METHOD'] == "POST"){
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
		else { $this->redirect(); }
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
	public function get_list_history_pembelian($id){
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
	 * 
	 */
	public function get_detail_operasional_proyek($id){
		// print_r($id);
		// exit;
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$config_dataTable = array(
				'tabel' => 'v_operasional_proyek',
				'kolomOrder' => array(null, 'tgl_detail', 'nama_detail', 'nama_bank','total_detail'),
				'kolomCari' => array('nama_bank', 'nama_detail', 'tgl_detail','total_detail'),
				'orderBy' => array('id_bank' => 'asc'),
				'kondisi' => 'WHERE id = "'.$id.'"',
			);
			
			$dataDetailOperasionalProyek = $this->DataTableModel->getAllDataTable($config_dataTable);
			
			$data = array();
			$no_urut = $_POST['start'];
			foreach($dataDetailOperasionalProyek as $row){
				$no_urut++;
				$dataRow = array();

				if($row['nama_detail'] == ""){
					unset($row['nama_bank']);
					unset($row['nama_detail']);
					unset($row['tgl_detail']);
					unset($row['total_detail']);
				} else {
					$dataRow[] = $no_urut;
					$dataRow[] = $this->helper->cetakTgl($row['tgl_detail'], 'full');
					$dataRow[] = $row['nama_detail'];
					$dataRow[] = $row['nama_bank'];					
					$dataRow[] = $this->helper->cetakRupiah($row['total_detail']);
					$data[] = $dataRow;
				}
				
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
			$this->validation->set_rules($data['total'], 'Total Pengajuan', 'total', 'nilai | 1 | 9999999 | required');
			// sisa
			$this->validation->set_rules($data['sisa'], 'Sisa Pengajuan', 'sisa', 'nilai | 0 | 9999999 | required');
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
			$this->validation->set_rules($data['total'], 'Total Pengajuan', 'total', 'nilai | 1 | 9999999 | required');
			// sisa
			$this->validation->set_rules($data['sisa'], 'Sisa Pengajuan', 'sisa', 'nilai | 0 | 9999999 | required');
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