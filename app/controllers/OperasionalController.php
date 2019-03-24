<?php 
	Defined("BASE_PATH") or die(ACCESS_DENIED);

	/**
	 * 
	 */
	class Operasional extends Crud_modalsAbstract
	{

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
			$this->model('OperasionalModel');
			$this->model('DataTableModel');
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
			$css = array(
				'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
				'assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
				'assets/bower_components/select2/dist/css/select2.min.css',

			);
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
				'assets/bower_components/select2/dist/js/select2.full.min.js',
				'assets/plugins/input-mask/jquery.inputmask.bundle.js',
				'app/views/operasional/js/initList.js',
				'app/views/operasional/js/initForm.js',
			);

			$config = array(
				'title' => 'Menu Operasional',
				'property' => array(
					'main' => 'Data Operasional',
					'sub' => 'List Semua Data Operasional',
				),
				'css' => $css,
				'js' => $js,
			);
			
			$this->layout('operasional/list', $config, $data = NULL);
		}	

		/**
		 * Function get_list
		 * method khusus untuk datatable
		 * generate token edit dan delete
		 * return json
		 */
		public function get_list(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				// config datatable
				$config_dataTable = array(
					'tabel' => 'v_operasional',
					'kolomOrder' => array(null, 'id', 'nama_bank', 'tgl', 'nama', 'nominal', null),
					'kolomCari' => array('nama', 'tgl', 'nama', 'nama_bank', 'nominal', 'ket'),
					'orderBy' => array('tgl' => 'desc'),
					'kondisi' => false,
				);

				$dataOperasional = $this->DataTableModel->getAllDataTable($config_dataTable);

				$data = array();
				$no_urut = $_POST['start'];
				foreach($dataOperasional as $row){
					$no_urut++;

					// button aksi
					$aksiDetail = '<button onclick="getView('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-info btn-flat" title="Lihat Detail"><i class="fa fa-eye"></i></button>';
					$aksiEdit = '<button onclick="getEdit('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-success btn-flat" title="Edit Data"><i class="fa fa-pencil"></i></button>';
					$aksiHapus = '<button onclick="getDelete('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-danger btn-flat" title="Hapus Data"><i class="fa fa-trash"></i></button>';
					
					$aksi = '<div class="btn-group">'.$aksiDetail.$aksiEdit.$aksiHapus.'</div>';
					
					if($row['jenis'] == 'UANG MASUK')
						$jenis = '<span class="label label-success">'.$row['jenis'].'</span>';
					else if($row['jenis'] == 'UANG KELUAR')
						$jenis = '<span class="label label-danger">'.$row['jenis'].'</span>';

					$dataRow = array();
					$dataRow[] = $no_urut;
					// $dataRow[] = $row['id'];
					$dataRow[] = $row['nama_bank'];
					$dataRow[] = $this->helper->cetakTgl($row['tgl'] ,'full');
					$dataRow[] = $row['nama'];
					$dataRow[] = $this->helper->cetakRupiah($row['nominal']);
					$dataRow[] = $jenis;
					$dataRow[] = $aksi;
					
					// $dataRow[] = $row['ket'];
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
			else{
				$this->redirect();
			}
						
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
					$notif = array(
						'type' => "error",
						'title' => "Pesan Gagal",
						'message' => "Terjadi Kesalahan Teknis, Silahkan Coba Kembali",
					);
				}
				else{
					// validasi data
					$validasi = $this->set_validation($data);
					$cek = $validasi['cek'];
					$error = $validasi['error'];

					$this->model('BankModel');
					$getSaldo = $this->BankModel->getById($data['id_bank'])['saldo'];

					if($data['nominal'] > $getSaldo){
						$cek = false;
						$error['nominal'] = "Nominal terlalu besar dan melebihi saldo bank";
					}

					if($cek){
						// validasi inputan
						$data = array(
							'id_bank' => $this->validation->validInput($data['id_bank']),
							'id_kas_besar' => $_SESSION['sess_id'],
							'tgl' => $this->validation->validInput($data['tgl']),
							'nama' => $this->validation->validInput($data['nama']),
							'nominal' => $this->validation->validInput($data['nominal']),
							'jenis' => $this->validation->validInput($data['jenis']),
							'ket' => $this->validation->validInput($data['ket']),
							'ket_mutasi' => ''
						);

						// insert
						if($this->OperasionalModel->insert($data)) {
							$this->success = true;
							$notif = array(
								'type' => "success",
								'title' => "Pesan Berhasil",
								'message' => "Tambah Data Operasional Baru Berhasil",
							);
						}
						else {
							$notif = array(
								'type' => "error",
								'title' => "Pesan Gagal",
								'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
							);
						}
					}
					else {
						$notif = array(
							'type' => "warning",
							'title' => "Pesan Pemberitahuan",
							'message' => "Silahkan Cek Kembali Form Isian",
						);
					}
				}

				$output = array(
					'status' => $this->success,
					'notif' => $notif,
					'error' => $error,
					// 'data' => $data
				);

				echo json_encode($output);		
			}
			else{
				$this->redirect();
			}
				
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
				if(empty($id) || $id == "") $this->redirect(BASE_URL. "operasional/");

				$data = !empty($this->OperasionalModel->getById($id)) ? $this->OperasionalModel->getById($id) : false;
				
				echo json_encode($data);
			}
			else{
				$this->redirect();
			}
				
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
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$data = isset($_POST) ? $_POST : false;

				$error = $notif = array();

				if(!$data){
					$notif = array(
						'type' => "error",
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
							'id_bank' =>  $this->validation->validInput($data['id_bank']),
							'tgl' => $this->validation->validInput($data['tgl']),
							'nama' =>  $this->validation->validInput($data['nama']),
							'nominal' =>  $this->validation->validInput($data['nominal']),
							'jenis' =>  $this->validation->validInput($data['jenis']),
							'ket' =>  $this->validation->validInput($data['ket']),
							'ket_mutasi' => ''
						);

						// update db

						// transact

						if($this->OperasionalModel->update($data)) {
							$this->success = true;
							$notif = array(
								'type' => "success",
								'title' => "Pesan Berhasil",
								'message' => "Edit Data Operasional Berhasil",
							);
						}
						else {
							$notif = array(
								'type' => "error",
								'title' => "Pesan Gagal",
								'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
							);
						}

						// commit
					}
					else {
						$notif = array(
							'type' => "warning",
							'title' => "Pesan Pemberitahuan",
							'message' => "Silahkan Cek Kembali Form Isian",
						);
					}
				
				}

				$output = array(
					'status' => $this->success,
					'notif' => $notif,
					'error' => $error,
					'data' => $data
				);

				echo json_encode($output);
			}
			else{
				$this->redirect();
			}
				
		}

		/**
		 * Function detail
		 * method untuk get data detail dan setting layouting detail
		 * param $id didapat dari url
		 */
		public function detail($id){
			$id = strtoupper($id);
			if(empty($id) || $id == "") $this->redirect(BASE_URL."operasional/");

			$data_detail = !empty($this->OperasionalModel->getByid_fromView($id)) ? $this->OperasionalModel->getByid_fromView($id) : false;

			if(!$data_detail) $this->redirect(BASE_URL."operasional/");

			$css = array(
				'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
			);
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'app/views/operasional/js/initView.js',
				// 'app/views/operasional/js/initForm.js',
			);

			$config = array(
				'title' => 'Menu Operasional - Detail',
				'property' => array(
					'main' => 'Data Operasional',
					'sub' => 'Detail Data Operasional',
				),
				'css' => $css,
				'js' => $js,
			);

			$data = array(
				'id' => $data_detail['id'],
				'tgl' => $this->helper->cetakTgl($data_detail['tgl'], 'full'),
				'nama' => $data_detail['nama'],
				'nominal' => $this->helper->cetakRupiah($data_detail['nominal']),
				'jenis' => $data_detail['jenis'],
				'ket' => $data_detail['ket'],
				'id_bank' => $data_detail['id_bank'],
				'nama_bank' => $data_detail['nama_bank'],
				'id_kas_besar' => $data_detail['id_kas_besar'],
				'nama_kas_besar' => $data_detail['nama_kas_besar'],
				'no_telp' => $data_detail['no_telp'],
				'email' => $data_detail['email'],
					
			);

			$this->layout('operasional/view', $config, $data);
		}

		/**
		 * Function delete
		 * method yang berfungsi untuk menghapus data
		 * param $id didapat dari url
		 * return json
		 */
		public function delete($id){
			$id = strtoupper($id);
			
			$dataOperasional = $this->OperasionalModel->getById($id);

			$data = array(
				'id' => $id,
				'jenis' => $dataOperasional['jenis'],
				'nominal' => $dataOperasional['nominal'],
				'tgl' => date('Y-m-d'),
				'ket' => '',	
			);

			if($this->OperasionalModel->delete($data)) $this->success = true;

			echo json_encode($this->success);
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

			$row = $this->OperasionalModel->getExport($tgl_awal, $tgl_akhir);
			$header = array_keys($row[0]); 
			$this->excel->setProperty('Laporan Operasional','Laporan Operasional','Data Laporan Operasional');
			$this->excel->setData($header, $row);
			$this->excel->getData('Data Operasional', 'Data Operasional', 4, 5 );

			$this->excel->getExcel('Data Operasional');	
		}

		/**
		 * 
		 */
		public function get_bank(){
			$this->model('BankModel');

			$data_bank = $this->BankModel->getAll();
			$data = array();

			foreach($data_bank as $row){
				$dataRow = array();
				$dataRow['id'] = $row['id'];
				$dataRow['text'] = $row['nama'].' - '.$this->helper->cetakRupiah($row['saldo']);

				$data[] = $dataRow;
			}

			echo json_encode($data);
		}

		/**
		 * Fungsi set_validation
		 * method yang berfungsi untuk validasi inputan secara server side
		 * param $data didapat dari post yang dilakukan oleh user
		 * return berupa array, status hasil pengecekan dan error tiap validasi inputan
		 */
		private function set_validation($data){
		
			// id_bank
			$this->validation->set_rules($data['id_bank'], 'id bank', 'id_bank', 'string | 1 | 255 | required');
			// tgl
			$this->validation->set_rules($data['tgl'], 'Tanggal', 'tgl', 'string | 1 | 255 | required ');
			// nama 
			$this->validation->set_rules($data['nama'], 'Nama Kebutuhan', 'nama', 'string | 1 | 255 | required');
			// nominal 
			$this->validation->set_rules($data['nominal'], 'Nominal Uang', 'nominal', 'nilai | 0 | 99999999999 | required');
			//jenis
			$this->validation->set_rules($data['jenis'], 'Jenis', 'jenis', 'string | 1 | 255 | required');
			// ket 
			$this->validation->set_rules($data['ket'], 'Keterangan', 'ket', 'string | 1 | 255 | required');

			return $this->validation->run();
		}
	}