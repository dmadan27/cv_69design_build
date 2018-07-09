<?php
Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

class Operasional_Proyek extends CrudAbstract{

	protected $token;

	public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
			$this->model('Operasional_ProyekModel');
			$this->helper();
			$this->validation();
	}

	public function index(){
		$this->list();
	}

	protected function list(){
		// set config untuk layouting
			$css = array(
				'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
			);
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'app/views/operasional_proyek/js/initList.js',

			);

			$config = array(
				'title' => array(
					'main' => 'Data Operasional Proyek',
					'sub' => 'List Semua Data Operasional Proyek',
				),
				'css' => $css,
				'js' => $js,
			);
			
			// set token
			$_SESSION['token_operasional_proyek'] = array(
				'list' => md5($this->auth->getToken()),
				'add' => md5($this->auth->getToken()),
			);

			$this->token = array(
				'list' => password_hash($_SESSION['token_operasional_proyek']['list'], PASSWORD_BCRYPT),
				'add' => password_hash($_SESSION['token_operasional_proyek']['add'], PASSWORD_BCRYPT),	
			);

			$data = array(
				'token_list' => $this->token['list'],
				'token_add' => $this->token['add'],
			);

			$this->layout('operasional_proyek/list', $config, $data);
	}

	public function get_list(){
		$token = isset($_POST['token_list']) ? $_POST['token_list'] : false;
			
			// cek token
			$this->auth->cekToken($_SESSION['token_operasional_proyek']['list'], $token, 'operasional_proyek');
			
			// config datatable
			$config_dataTable = array(
				'tabel' => 'operasional_proyek',
				'kolomOrder' => array(null, 'id', 'id_proyek', 'tgl', 'nama', 'total', null),
				'kolomCari' => array('id','id_proyek', 'tgl', 'nama'),
				'orderBy' => array('id' => 'asc'),
				'kondisi' => false,
			);

			$dataOperasionalProyek = $this->Operasional_ProyekModel->getAllDataTable($config_dataTable);

			// // set token
			$_SESSION['token_operasional_proyek']['edit'] = md5($this->auth->getToken());
			$_SESSION['token_operasional_proyek']['delete'] = md5($this->auth->getToken());
			
			$this->token = array(
				'edit' => password_hash($_SESSION['token_operasional_proyek']['edit'], PASSWORD_BCRYPT),
				'delete' => password_hash($_SESSION['token_operasional_proyek']['delete'], PASSWORD_BCRYPT),	
			);
			$data = array();
			$no_urut = $_POST['start'];
			foreach($dataOperasionalProyek as $row){
				$no_urut++;

				// $status = ($row['status'] == "AKTIF") ? '<span class="label label-success">'.$row['status'].'</span>' : '<span class="label label-danger">'.$row['status'].'</span>';

				//button aksi
				$aksiDetail = '<button onclick="getView('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-info btn-flat" title="Lihat Detail"><i class="fa fa-eye"></i></button>';
				$aksiEdit = '<button onclick="getEdit('."'".$row["id"]."'".', '."'".$this->token["edit"]."'".')" type="button" class="btn btn-sm btn-success btn-flat" title="Edit Data"><i class="fa fa-pencil"></i></button>';
				$aksiHapus = '<button onclick="getDelete('."'".$row["id"]."'".', '."'".$this->token["delete"]."'".')" type="button" class="btn btn-sm btn-danger btn-flat" title="Hapus Data"><i class="fa fa-trash"></i></button>';
				
				$aksi = '<div class="btn-group">'.$aksiDetail.$aksiEdit.$aksiHapus.'</div>';
				
				$dataRow = array();
				$dataRow[] = $no_urut;
				$dataRow[] = $row['id'];
				$dataRow[] = $row['id_proyek'];
				$dataRow[] = $this->helper->cetakTgl($row['tgl'], 'full');
				$dataRow[] = $row['nama'];
				$dataRow[] = $row['total'];
				$dataRow[] = $aksi;

				$data[] = $dataRow;
			}

			$output = array(
				'draw' => $_POST['draw'],
				'recordsTotal' => $this->Operasional_ProyekModel->recordTotal(),
				'recordsFiltered' => $this->Operasional_ProyekModel->recordFilter(),
				'data' => $data,
			);

			echo json_encode($output);	
	}

	public function form($id){
		if($id)	$this->edit(strtoupper($id));
		else $this->add();

	}

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
				'title' => array(
					'main' => 'Data Operasional Proyek',
					'sub' => 'Form Tambah Data',
				),
				'css' => $css,
				'js' => $js,
			);

			$_SESSION['token_operasional_proyek'] = array(
				'add' => md5($this->auth->getToken()),
			);
			$this->token = array(
				'add' => password_hash($_SESSION['token_operasional_proyek']['add'], PASSWORD_BCRYPT),	
			);
			$data = array(
				'token_form' => $this->token['add'],
				'action' => 'action-add',
				'id' => '',
				'id_proyek' => '',
				'tgl' => '',
				'nama' => '',
				'total' => '',
			);

			$this->layout('operasional_proyek/form', $config, $data);
		}

		public function action_add(){
			$data = isset($_POST) ? $_POST : false;
			// $dataOperasionalProyek = isset($_POST['dataOperasionalProyek']) ? json_decode($_POST['dataOperasionalProyek'], true) : false;
			// $dataDetail = isset($_POST['dataDetail']) ? json_decode($_POST['dataDetail'], true) : false;
			$this->auth->cekToken($_SESSION['token_operasional_proyek']['add'], $data['token'], 'operasional-proyek');
			
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

				// if(empty($dataDetail)) $cek = false;

				if($cek){
					// validasi inputan
					$data = array(
						'id' => $this->validation->validInput($data['id']),
						'id_proyek' => $this->validation->validInput($data['id_proyek']),
						'id_bank' => $this->validation->validInput($data['id_bank']),
						'tgl' => $this->validation->validInput($data['tgl']),
						'nama' => $this->validation->validInput($data['nama']),
						'total' => $this->validation->validInput($data['total']),
					);

					// $dataInsert = array(
					// 	'dataOperasionalProyek' => $dataProyek,
					// 	'dataDetail' => $dataDetail,
					// );

					if($this->Operasional_ProyekModel->insert($data)) {
						$status = true;
						$notif = array(
							'title' => "Pesan Berhasil",
							'message' => "Tambah Data Operasional Baru Berhasil",
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
				}
			}

			$output = array(
				'status' => $status,
				'notif' => $notif,
				'error' => $error,
				'data' => $data,
			);

			echo json_encode($output);		
		}

	

	



	protected function edit($id){
			if(empty($id) || $id == "") $this->redirect(BASE_URL."operasional-proyek/");

			// // // get data proyek
			// // $dataProyek = !empty($this->ProyekModel->getById($id)) ? $this->ProyekModel->getById($id) : false;

			// // if(!$dataProyek) $this->redirect(BASE_URL."proyek/");

			// // $css = array(
  	// // 			'assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
			// // 	'assets/bower_components/select2/dist/css/select2.min.css',
  	// // 		);
			// // $js = array(
			// // 	'assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
			// // 	'assets/bower_components/select2/dist/js/select2.full.min.js',
			// // 	'assets/plugins/input-mask/jquery.inputmask.bundle.js',
			// // 	'app/views/proyek/js/initForm.js',	
			// // );

			// // $config = array(
			// // 	'title' => array(
			// // 		'main' => 'Data Proyek',
			// // 		'sub' => 'Form Edit Data',
			// // 	),
			// // 	'css' => $css,
			// // 	'js' => $js,
			// // );

			// // $_SESSION['token_proyek'] = array(
			// // 	'edit' => md5($this->auth->getToken()),
			// // );
			// // $this->token = array(
			// // 	'edit' => password_hash($_SESSION['token_proyek']['edit'], PASSWORD_BCRYPT),
			// // );

			// // $data = array(
			// // 	'token_form' => $this->token['edit'],
			// // 	'action' => 'action-edit',
			// // 	'id' => $dataProyek['id'],
			// // 	'pemilik' => $dataProyek['pemilik'],
			// // 	'tgl' => $dataProyek['tgl'],
			// // 	'pembangunan' => $dataProyek['pembangunan'],
			// // 	'luas_area' => $dataProyek['luas_area'],
			// // 	'alamat' => $dataProyek['alamat'],
			// // 	'kota' => $dataProyek['kota'],
			// // 	'estimasi' => $dataProyek['estimasi'],
			// // 	'total' => $dataProyek['total'],
			// // 	'dp' => $dataProyek['dp'],
			// // 	'cco' => $dataProyek['cco'],
			// // 	'status' => $dataProyek['status'],
			// // );

			// $this->layout('proyek/form', $config, $data);
		}

	public function action_edit(){

	}

	public function detail($id){

	}

	public function delete($id){

	}

	public function export(){

	}

	/**
		* Function validasi form utama
		*/
		private function set_validation($data, $action){
			
			// id
			$this->validation->set_rules($data['id'], 'ID Operasional Proyek', 'id', 'string | 1 | 255 | required');
			// id_proyek
			$this->validation->set_rules($data['id_proyek'], 'ID proyek', 'id_proyek', 'string | 1 | 255 | required');
			// id_bank
			$this->validation->set_rules($data['id_bank'], 'ID Bank', 'id_bank', 'string | 1 | 255 | required');
			// tgl
			$this->validation->set_rules($data['tgl'], 'Tanggal Operasional Proyek', 'tgl', 'string | 1 | 255 | required');
			// nama
			$this->validation->set_rules($data['nama'], 'Nama Pengajuan', 'nama', 'string | 1 | 255 | required');
			// total
			$this->validation->set_rules($data['total'], 'Total Pengajuan', 'total', 'nilai | 1 | 99999 | required');
			
			return $this->validation->run();
		}


		/**
		*
		*/
		public function get_last_id(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$proyek = isset($_POST['get_proyek']) ? $this->validation->validInput($_POST['get_proyek']) : false;

				$id_temp = ($proyek) ? 'OPRY-'.$proyek.'-' : 'OPRY-[ID_PROYEK]-';

				$data = !empty($this->Operasional_ProyekModel->getLastID($id_temp)['id']) ? $this->Operasional_ProyekModel->getLastID($id_temp)['id'] : false;

				if(!$data) $id = $id_temp.'0001';
				else{
					$noUrut = (int)substr($data, 17, 4);
					$noUrut++;

					$id = $id_temp.sprintf("%04s", $noUrut);
				}

				// if(!$data) $id = 'PRY0001';
				// else{
				// 	// $data = implode('', $data);
				// 	$kode = 'PRY';
				// 	$noUrut = (int)substr($data, 3, 4);
				// 	$noUrut++;

				// 	$id = $kode.sprintf("%04s", $noUrut);
				// }

				echo json_encode($id);
			}		

		}

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

	public function action_add_detail(){
		$data = isset($_POST) ? $_POST : false;
		
		$status = false;
		$error = "";

		$validasi = $this->set_validation_detail($data);
		$cek = $validasi['cek'];
		$error = $validasi['error'];

		if($cek) $status = true;

		$output = array(
			'status' => $status,
			// 'notif' => $notif,
			'error' => $error,
			'data' => $data,
		);
		echo json_encode($output);

	}

	/**
		* Function validasi form detail
		*/
		private function set_validation_detail($data){
			// nama
			$this->validation->set_rules($data['nama_detail'], 'Nama Kebutuhan', 'nama_detail', 'string | 1 | 255 | required');
			// jenis
			$this->validation->set_rules($data['jenis_detail'], 'Jenis Kebutuhan', 'jenis_detail', 'string | 1 | 255 | required');
			// satuan
			$this->validation->set_rules($data['satuan_detail'], 'Satuan', 'satuan_detail', 'string | 1 | 255 | required');
			// kuantiti
			$this->validation->set_rules($data['qty_detail'], 'Kuantiti', 'qty_detail', 'angka | 1 | 255 | required');
			// harga
			$this->validation->set_rules($data['harga_detail'], 'Harga Kebutuhan', 'harga_detail', 'nilai | 1 | 9999999999 | required');
			// sub_total
			$this->validation->set_rules($data['sub_total_detail'], 'Sub Total', 'sub_total_detail', 'nilai | 1 | 9999999999 | required');
			// status
			$this->validation->set_rules($data['status_detail'], 'Status', 'status_detail', 'string | 1 | 255 | required');
			// harga asli
			$this->validation->set_rules($data['harga_asli_detail'], 'Harga Asli', 'harga_asli_detail', 'nilai | 1 | 9999999999 | required');
			// sisa
			$this->validation->set_rules($data['sisa_detail'], 'Sisa', 'sisa_detail', 'nilai | 1 | 9999999999 | required');
			// status lunas
			$this->validation->set_rules($data['status_lunas_detail'], 'Status Lunas', 'status_lunas_detail', 'string | 1 | 255 | required');

			return $this->validation->run();
		}



	

}