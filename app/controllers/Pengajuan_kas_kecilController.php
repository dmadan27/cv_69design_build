<?php 
Defined("BASE_PATH") or die(ACCESS_DENIED);

/**
 * 
 */
class Pengajuan_kas_kecil extends Crud_modalsAbstract{

	private $success = false;
	private $notif = array();
	private $error = array();
	private $message = NULL;

	/**
	 * load auth, cekAuth
	 * load default model, BankModel
	 * load helper dan validation
	 */
	public function __construct(){
		$this->auth();
		$this->auth->cekAuth();
		$this->model('Pengajuan_kasKecilModel');
		$this->model('DataTableModel');
		$this->model('UserModel');
		$this->helper();
		$this->validation();
		$this->excel();
	}	

	/**
	 * Function index
	 * menjalankan method list
	 */
	public function index(){
		$this->list();
	}

	/**
	 * Function list
	 * setting layouting list utama
	 * generate token list dan add
	 */
	protected function list(){
		// set config untuk layouting
		$saldo_kasKecil = $this->UserModel->getKasKecil($_SESSION['sess_email']);
		$css = array(
			'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
			'assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
			'assets/bower_components/select2/dist/css/select2.min.css',
		);
		$js = array(
			'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
			'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
			'assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
			'assets/plugins/input-mask/jquery.inputmask.bundle.js',
			'assets/bower_components/select2/dist/js/select2.full.min.js',
			'assets/bower_components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.id.min.js',
			'assets/js/library/export.js',
			'app/views/form_export/js/initFormStartEndDate.js',
			'app/views/pengajuan_kas_kecil/js/initList.js',
			'app/views/pengajuan_kas_kecil/js/initForm.js',
		);

		$config = array(
			'title' => 'Menu Pengajuan Kas Kecil',
			'property' => array(
				'main' => 'Data Pengajuan Kas Kecil',
				'sub' => 'List Data Pengajuan Kas Kecil',
			),
			'css' => $css,
			'js' => $js,
		);

		$data = array(
			'saldo' => $this->helper->cetakRupiah($saldo_kasKecil['saldo']),
		);

		$this->layout('pengajuan_kas_kecil/list', $config, $data);
	}	

	/**
	 * Function get_list
	 * method khusus untuk datatable
	 * generate token edit dan delete
	 * return json
	 */
	public function get_list(){
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			
			$level = $_SESSION['sess_level'];
			if($level == "KAS BESAR"){
				$kondisi = false;
			} else if($level == "KAS KECIL") {
				$kondisi = 'where id_kas_kecil = "'.$_SESSION['sess_id'].'"';
			}
			// config datatable
			$config_dataTable = array(
				'tabel' => 'pengajuan_kas_kecil',
				'kolomOrder' => array(null, 'id','id_kas_kecil', 'tgl', 'nama',  'total', 'status',null),
				'kolomCari' => array('id','id_kas_kecil','nama',  'status'),
				'orderBy' => array('id' => 'asc'),
				'kondisi' => $kondisi
			);

			$status_pengajuan = null;

			$dataPengajuanKasKecil = $this->DataTableModel->getAllDataTable($config_dataTable);

			$data = array();
			$no_urut = $_POST['start'];
			foreach($dataPengajuanKasKecil as $row){
				$no_urut++;

				if($row['status'] == '0'){
					$status = '<span class="label label-primary">PENDING</span>';
				} 
				else if($row['status'] == '1'){
					$status = '<span class="label label-warning">PERBAIKI</span>';
				} 
				else if($row['status'] == '2'){
					$status = '<span class="label label-success">DISETUJUI</span>';
				} 
				else if($row['status'] == '3'){
					$status = '<span class="label label-danger">DITOLAK</span>';	
				} 

				// // button aksi
				$aksi = '<div class="btn-group">';
				$aksiEdit = '<button onclick="getEdit('."'".$row["id"]."'".')" type="button"  class="btn btn-sm btn-success btn-flat" title="Edit Data"><i class="fa fa-pencil"></i></button>';
				$aksiDetail = '<button onclick="getView('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-info btn-flat" title="Lihat Detail"><i class="fa fa-eye"></i></button>';
				$aksiHapus = '<button onclick="getDelete('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-danger btn-flat" title="Hapus Data"><i class="fa fa-trash"></i></button>';
				
				if($level == 'KAS BESAR') {
					// pending dan perbaiki
					if($row['status'] == "0" || $row['status'] == "1") {
						$aksi .= $aksiDetail.$aksiEdit.$aksiHapus;
					}
					// disetujui dan ditolak
					else if($row['status'] == "2" || $row['status'] == "3") {
						$aksi .= $aksiDetail;
					}
				}
				else if($level == 'KAS KECIL') {
					// pending dan perbaiki
					if($row['status'] == "0" || $row['status'] == "1") {
						$aksi .= $aksiDetail.$aksiEdit.$aksiHapus;
					}
					// disetujui dan ditolak
					else if($row['status'] == "2") {
						$aksi .= $aksiDetail;
					}
					else if($row['status'] == "3") {
						$aksi .= $aksiDetail.$aksiHapus;
					}
				}
				else if($level == 'OWNER') {
					$aksi .= $aksiDetail;
				}

				$aksi .= '</div>';

				$dataRow = array();
				$dataRow[] = $no_urut;
				$dataRow[] = $row['id'];
				$dataRow[] = $row['id_kas_kecil'];
				$dataRow[] = $this->helper->cetakTgl($row['tgl'], 'full');
				$dataRow[] = $row['nama'];
				$dataRow[] = $this->helper->cetakRupiah($row['total']);
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
	 * Function action_add
	 * method untuk aksi tambah data
	 * return berupa json
	 * status => status berhasil atau gagal proses tambah
	 * notif => pesan yang akan ditampilkan disistem
	 * error => error apa saja yang ada dari hasil validasi
	 */
	public function action_add(){
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$data = isset($_POST) ? $_POST : false;
					
			$error = $notif = array();

			if(!$data){
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
					$data = array(
						'id' => $this->validation->validInput($data['id']),
						'id_kas_kecil' =>$_SESSION['sess_id'],
						'tgl' => $this->validation->validInput($data['tgl']),
						'nama' => $this->validation->validInput($data['nama']),
						'total' => $this->validation->validInput($data['total']),
						'status' => '0',								
					);
					$res = $this->Pengajuan_kasKecilModel->insert($data);
					// insert pengajuan kas kecil
					if($res['success']) {
						$this->success = true;
						$this->notif = array(
							'type' => 'success',
							'title' => "Pesan Berhasil",
							'message' => "Tambah Data Pengajuan Kas Kecil Berhasil",
						);
					} else if($res['tolakdana']) {
						$this->notif = array(
							'type' => "warning",
							'title' => "Pesan Pemberitahuan",
							'message' => "Saldo anda masih mencukupi",
						);
					} else {
						$this->notif = array(
							'type' => "error",
							'title' => "Pesan Gagal",
							'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
						);
					}
				} else {
					$this->notif = array(
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
				'data' => $data
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
		$data = !empty($this->Pengajuan_kasKecilModel->getById($id)) ? $this->Pengajuan_kasKecilModel->getById($id) : false;
		$saldo = $this->Pengajuan_kasKecilModel->getSaldoKK($data['id_kas_kecil']);
		$data['tgl_full'] = $this->helper->cetakTgl($data['tgl'], 'full');
		$data['saldo'] = $saldo;
		echo json_encode($data);
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
		$level = $_SESSION['sess_level'];

		$this->error = $this->notif = array();
		if(!$data){
			$this->notif = array(
				'type' => "error",
				'title' => "Pesan Pemberitahuan",
				'message' => "Silahkan Cek Kembali Form Isian",
			);
		}
		else{
			// validasi data
			$validasi = $this->set_validation($data);
			$cek = $validasi['cek'];
			$this->error = $validasi['error'];

			if($cek){

				if($level == "KAS BESAR"){
					
					// validasi inputan
					$data = array(
						'id' => $this->validation->validInput($data['id']),
						'id_kas_kecil' => $this->validation->validInput($data['id_kas_kecil']),
						'tgl' => $this->validation->validInput($data['tgl']),
						'id_bank' => $this->validation->validInput($data['id_bank']),
						'total_disetujui' => $this->validation->validInput($data['total_disetujui']),
						'status' => $this->validation->validInput($data['status'])
					);

				} else if($level == "KAS KECIL") {

					// validasi inputan
					$data = array(
						'id' => $this->validation->validInput($data['id']),
						'nama' => $this->validation->validInput($data['nama']),
						'tgl' => $this->validation->validInput($data['tgl']),
						'total' => $this->validation->validInput($data['total']),
						'status' => '0'
					);
					
				}

				$res = $this->Pengajuan_kasKecilModel->update($data);
				// To Model
				if($res['success']) {
					$this->success = true;
					$this->notif = array(
						'type' => "success",
						'title' => "Pesan Berhasil",
						'message' => "Edit Data Pengajuan Kas Kecil Berhasil",
					);
				} else if($res['tolakdana']) {
					$this->notif = array(
						'type' => "warning",
						'title' => "Pesan Pemberitahuan",
						'message' => "Saldo anda masih mencukupi",
					);
				} else {
					$this->notif = array(
						'type' => "error",
						'title' => "Pesan Gagal",
						'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
					);
				}
			} else {
				$this->notif = array(
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
		$data_detail = !empty($this->Pengajuan_kasKecilModel->getById($id)) ? $this->Pengajuan_kasKecilModel->getById($id) : false;

		if((empty($id) || $id == "") || !$data_detail) $this->redirect(BASE_URL."pengajuan-kas-kecil/");

		$css = array(
			'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
			'assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
		);
		$js = array(
			'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
			'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
			'assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',	
			'assets/plugins/input-mask/jquery.inputmask.bundle.js',
			'app/views/pengajuan_kas_kecil/js/initView.js'
				
		);

		$config = array(
			'title' => 'Menu Pengajuan Kas Kecil - Detail',
			'property' => array(
				'main' => 'Data Pengajuan Kas Kecil',
				'sub' => 'Detail Data Pengajuan Kas Kecil',
			),
			'css' => $css,
			'js' => $js,
		);

		// $status = ($data_detail['status'] == "AKTIF") ? 
		// 	'<span class="label label-success">'.$data_detail['status'].'</span>' : 
		// 	'<span class="label label-danger">'.$data_detail['status'].'</span>';

		if($data_detail['status'] == '0'){
			$data_detail['status'] = "PENDING";
		} else if($data_detail['status'] == '1'){
			$data_detail['status'] = "PERBAIKI";
		} else if($data_detail['status'] == '2'){
			$data_detail['status'] = "DISETUJUI";
		} else if($data_detail['status'] == '3'){
			$data_detail['status'] = "DITOLAK";	
		} 

		$data = array(
			'id' => $data_detail['id'],
			'id_kas_kecil' => $data_detail['id_kas_kecil'],
			'kas_kecil' => $data_detail['kas_kecil'],
			'tgl' => $this->helper->cetakTgl($data_detail['tgl'], 'full'),
			'nama' => $data_detail['nama'],
			'total' => $this->helper->cetakRupiah($data_detail['total']),
			'total_disetujui' => $this->helper->cetakRupiah($data_detail['total_disetujui']),
			'status' => $data_detail['status']
		);

		// $this->layout('pengajuan_kas_kecil/view', $config, $data);
		echo json_encode($data);
		
	}

	/**
	 * Function delete
	 * method yang berfungsi untuk menghapus data\
	 * param $id didapat dari url
	 * return json
	 */
	public function delete($id){			
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$id = strtoupper($id);
			if(empty($id) || $id == "") $this->redirect(BASE_URL."pengajuan-kas-kecil/");

			if($this->Pengajuan_kasKecilModel->delete($id)) $this->status = true;

			echo json_encode($this->status);
		}
		else $this->redirect();	
	}

	/**
	 * Function get_mutasi
	 * method yang berfungsi untuk get data mutasi bank sesuai dengan id
	 * dipakai di detail data
	 */
	public function get_mutasi(){
		
	}

	/**
	 * Export data ke format Excel
	 */
	public function export(){
		
		$tgl_awal = $_GET['tgl_awal'];
		$tgl_akhir = $_GET['tgl_akhir'];

		$row = $this->Pengajuan_kasKecilModel->getExport($tgl_awal, $tgl_akhir);
		$header = array_keys($row[0]); 
		$header[6] = 'ID KAS KECIL';
		$this->excel->setProperty('Laporan Pengajuan Kas Kecil','Laporan Pengajuan Kas Kecil','Data Laporan Pengajuan Kas Kecil');
		$this->excel->setData($header, $row);
		$this->excel->getData('Data Pengajuan Kas Kecil', 'Data Pengajuan Kas Kecil', 4, 5 );

		$this->excel->getExcel('Data Pengajuan Kas Kecil');		

	}

	/**
	 * Fungsi set_validation
	 * method yang berfungsi untuk validasi inputan secara server side
	 * param $data didapat dari post yang dilakukan oleh user
	 * return berupa array, status hasil pengecekan dan error tiap validasi inputan
	 */
	private function set_validation($data){
		// $required = ($action =="action-add") ? 'not_required' : 'required';
	
		if($data['action'] == 'action-add'){
			// id
			$this->validation->set_rules($data['id'], 'ID Pengajuan Kas Kecil', 'id', 'string | 1 | 255 | required');
			// tgl
			$this->validation->set_rules($data['tgl'], 'Tanggal Pengajuan Kas Kecil', 'tgl', 'string | 1 | 255 | required');
			// nama pengajuan kas kecil
			$this->validation->set_rules($data['nama'], 'Nama Pengajuan', 'nama', 'string | 1 | 255 | required');
			// total
			$this->validation->set_rules($data['total'], 'Total Pengajuan', 'total', 'nilai | 1 | 99999999 | required');
				
		} else if($data['action'] == 'action-edit'){

			if($_SESSION['sess_level'] == "KAS KECIL"){
				//status
				$this->validation->set_rules($data['status'], 'Status Pengajuan', 'status', 'string | 1 | 255 | ');	
			} else {
				//status
				$this->validation->set_rules($data['status'], 'Status Pengajuan', 'status', 'string | 1 | 255 | required');	
			}

			
		}
			
		
		return $this->validation->run();
		
	}

	/**
	 * 
	 */
	public function get_notif(){
		$notif = $this->Pengajuan_kasKecilModel->getAll_pending();
		$jumlah = $this->Pengajuan_kasKecilModel->getTotal_pending();

		$data_notif = '';
		foreach($notif as $value){
			$data_notif .= '<li><a href="'.BASE_URL.'pengajuan-kas-kecil/detail/'.strtolower($value['id']).'">';
			$data_notif .= '<strong>'.$value['id'].' - '.$value['nama_kas_kecil'].'</strong>';
			$data_notif .= '</br>Total: '.$this->helper->cetakRupiah($value['total']); 
			$data_notif .= '</a></li>';
		}

		$output = array(
			'notif' => $notif,
			'jumlah' => $jumlah,
			'text' => 'Anda memiliki '.$jumlah.' pengajuan yang masih Pending',
			'data' => $data_notif,
			'view_all' => BASE_URL.'pengajuan-kas-kecil/',
		);

		echo json_encode($output);
	}
	
	/**
	 * 
	 */
	public function get_nama_kas_kecil(){
		$this->model('Kas_kecilModel');
		$data_kas_kecil =  $this->Kas_kecilModel->getAll();
		$data = array();


			foreach ($data_kas_kecil as $row) {
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
	public function get_nama_bank(){
		$this->model('BankModel');
		$data_nama_bank = $this->BankModel->getAll();
		$data = array();

		foreach($data_nama_bank as $row){
			$dataRow = array();
			$dataRow['id'] = $row['id'];
			$dataRow['text'] = $row['nama']. ' - '.$row['saldo'];

			$data[] = $dataRow;
		}

		echo json_encode($data);
	}

	/**
	 * 
	 */
	public function get_id_pengajuan(){
		$this->model('Pengajuan_sub_kas_kecilModel');

		$data_SKK = $this->Pengajuan_sub_kas_kecilModel->getAll();
		$data = array();

		foreach($data_SKK as $row){
			$dataRow = array();
			$dataRow['id'] = $row['id'];
			$dataRow['text'] = $row['id'];
			$data[] = $dataRow;
		}

		echo json_encode($data);
	}

	/**
	 * 
	 */
	public function get_last_id(){
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$data = !empty($this->Pengajuan_kasKecilModel->getLastID()['id']) ? $this->Pengajuan_kasKecilModel->getLastID()['id'] : false;

			if(!$data) $id = 'PKK0001';
			else{
				$kode = 'PKK';
				$noUrut = (int)substr($data, 3, 4);
				$noUrut++;

				$id = $kode.sprintf("%04s", $noUrut);
			}

			echo json_encode($id);
		}
		else $this->redirect();
	}

	/**
	 * 
	 */
	public function count_pengajuan_kas_kecil_disetujui(){
		$this->model('Pengajuan_kasKecilModel');
		$data_pkk_disetujui = $this->Pengajuan_kasKecilModel->getTotal_setujui();

		// foreach($data_pkk_disetujui as $row){
		// 	$data[] = $row;
		// }
		
		echo json_encode($data_pkk_disetujui);

	}

	/**
	 * 
	 */
	public function get_pengajuan_sub_kas_kecil(){
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			
			// config datatable
			$config_dataTable = array(
				'tabel' => 'v_pengajuan_sub_kas_kecil_full',
				'kolomOrder' => array(null, 'id_pengajuan', 'id_sub_kas_kecil', 'id_proyek', 'status_laporan', 'pemilik', 'pembangunan', 'kota'),
				'kolomCari' => array('id_pengajuan', 'id_sub_kas_kecil', 'id_proyek', 'status_laporan', 'pemilik', 'pembangunan', 'kota'),
				'orderBy' => array('id_pengajuan' => 'desc'),
				'kondisi' => false,
			);

			$dataPengajuanFull = $this->DataTableModel->getAllDataTable($config_dataTable);

			$data = array();
			$no_urut = $_POST['start'];
			foreach($dataPengajuanFull as $row){
				$no_urut++;
				
				$dataRow = array();
				$dataRow[] = $no_urut;
				$dataRow[] = $row['id_pengajuan'];
				$dataRow[] = $row['id_sub_kas_kecil'];
				$dataRow[] = $row['id_proyek'];
				$dataRow[] = $row['status_laporan'];
				$dataRow[] = $row['pemilik'];
				$dataRow[] = $row['pembangunan'];
				$dataRow[] = $row['kota'];

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


}