<?php 
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	*
	*/
	class Operasional extends Crud_modalsAbstract{

		private $token;
		private $status = false;

		/**
		* load auth, cekAuth
		* load default model, BankModel
		* load helper dan validation
		*/
		public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
			$this->model('OperasionalModel');
			$this->helper();
			$this->validation();
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
				'title' => array(
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
			// config datatable
			$config_dataTable = array(
				'tabel' => 'v_operasional',
				'kolomOrder' => array(null, 'id', 'nama_bank', 'tgl', 'nama', 'nominal', null),
				'kolomCari' => array('nama', 'tgl', 'nama', 'nama_bank', 'nominal', 'ket'),
				'orderBy' => array('tgl' => 'desc'),
				'kondisi' => false,
			);

			$dataOperasional = $this->OperasionalModel->getAllDataTable($config_dataTable);

			$data = array();
			$no_urut = $_POST['start'];
			foreach($dataOperasional as $row){
				$no_urut++;

				// button aksi
				$aksiDetail = '<button onclick="getView('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-info btn-flat" title="Lihat Detail"><i class="fa fa-eye"></i></button>';
				$aksiEdit = '<button onclick="getEdit('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-success btn-flat" title="Edit Data"><i class="fa fa-pencil"></i></button>';
				$aksiHapus = '<button onclick="getDelete('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-danger btn-flat" title="Hapus Data"><i class="fa fa-trash"></i></button>';
				
				$aksi = '<div class="btn-group">'.$aksiDetail.$aksiEdit.$aksiHapus.'</div>';
				
				$dataRow = array();
				$dataRow[] = $no_urut;
				// $dataRow[] = $row['id'];
				$dataRow[] = $row['nama_bank'];
				$dataRow[] = $row['tgl'];
				$dataRow[] = $row['nama'];
				$dataRow[] = $row['nominal'];
				$dataRow[] = $aksi;
				
				// $dataRow[] = $row['ket'];
				$data[] = $dataRow;
			}

			$output = array(
				'draw' => $_POST['draw'],
				'recordsTotal' => $this->OperasionalModel->recordTotal(),
				'recordsFiltered' => $this->OperasionalModel->recordFilter(),
				'data' => $data,
			);

			echo json_encode($output);		
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
						'ket' => $this->validation->validInput($data['ket'])
					);

					// insert
					if($this->OperasionalModel->insert($data)) {
						$this->status = true;
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
				'status' => $status,
				'notif' => $notif,
				'error' => $error,
				'data' => $data
			);

			echo json_encode($output);		
		}

		/**
		* Function edit
		* method untuk get data edit
		* param $id didapat dari url
		* return berupa json
		*/
		public function edit($id){
			$id = strtoupper($id);
			$data = !empty($this->OperasionalModel->getById($id)) ? $this->OperasionalModel->getById($id) : false;
			
			echo json_encode($data);
		}

		// /**
		// * Function action_edit
		// * method untuk aksi edit data
		// * return berupa json
		// * status => status berhasil atau gagal proses edit
		// * notif => pesan yang akan ditampilkan disistem
		// * error => error apa saja yang ada dari hasil validasi
		// */
		public function action_edit(){
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

				// $getDataBank = $this->BankModel->getById($id);
				// $this->model('BankModel');

				if($cek){
					// validasi inputan
					$data = array(
						'id' =>  $this->validation->validInput($data['id']),
						'id_bank' =>  $this->validation->validInput($data['id_bank']),
						'nama' =>  $this->validation->validInput($data['nama']),
						'nominal' =>  $this->validation->validInput($data['nominal']),
						'ket' =>  $this->validation->validInput($data['ket']),
						

					);

					// update db

					// transact

					if($this->OperasionalModel->update($data)) {
						$this->status = true;
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
			if(empty($id) || $id == "") $this->redirect(BASE_URL."operasional/");

			$data_detail = !empty($this->OperasionalModel->getById($id)) ? $this->OperasionalModel->getById($id) : false;

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
				'title' => array(
					'main' => 'Data Operasional',
					'sub' => 'Detail Data Operasional',
				),
				'css' => $css,
				'js' => $js,
			);

			$data = array(
				'id' => $data_detail['id'],
				'id_bank' => $data_detail['id_bank'],
				'id_kas_besar' => $data_detail['id_kas_besar'],
				'tgl' => $data_detail['tgl'],
				'nama' => $data_detail['nama'],
				'nominal' => $this->helper->cetakRupiah($data_detail['nominal']),
				'ket' => $data_detail['ket'],
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
			
			$getNamaOperasional = $this->OperasionalModel->getById($id)['nama'];
			$ket = 'Data Operasional Bank '.$getNamaOperasional. 'telah Dihapus';

			$data = array(
				'id' => $id,
				'tgl' => date('Y-m-d'),
				'ket' => $ket,	
			);

			if($this->OperasionalModel->delete($data)) $this->status = true;

			echo json_encode($this->status);
		}

		/**
		* Function get_mutasi
		* method yang berfungsi untuk get data mutasi bank sesuai dengan id
		* dipakai di detail data
		*/
		public function get_mutasi(){
		
		}

		/**
		*
		*/
		public function export(){

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
			// ket 
			$this->validation->set_rules($data['ket'], 'Keterangan', 'ket', 'string | 1 | 255 | required');

			return $this->validation->run();
		}
	}