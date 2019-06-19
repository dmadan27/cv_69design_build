<?php
	Defined("BASE_PATH") or die(ACCESS_DENIED);

	/**
	 * Class Distributor extend ke abstract crud modals
	 */
	class Distributor extends Crud_modalsAbstract{

		private $success = false;
		private $notif = array();
		private $error = array();
		private $message = NULL;

		/**
		 * Default load saat pertama kali controller diakses
		 */
		public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
			$this->model('DistributorModel');
			$this->model('DataTableModel');
			$this->helper();
			$this->validation();
			$this->excel();
		}

		/**
		 * Method pertama kali yang diakses
		 */
		public function index() {
			$this->list();
		}

		/**
		 * Method List
		 * Menampilkan list semua data proyek
		 * Passing data css dan js yang dibutuhkan di list distributor
		 */
		protected function list() {
			// set config untuk layouting
			$css = array(
				'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
			);
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'assets/plugins/input-mask/jquery.inputmask.bundle.js',
				'assets/js/library/export.js',
				'app/views/distributor/js/initList.js',
				'app/views/distributor/js/initForm.js',
			);

			$config = array(
				'title' => 'Menu Dsitributor',
				'property' => array(
					'main' => 'Data Distributor',
					'sub' => 'List Semua Data Distributor',
				),
				'css' => $css,
				'js' => $js,
			);

			$this->layout('distributor/list', $config, $data = null);
		}

		/**
		 * Method get list
		 * Get data semua list distributor yang akan di passing ke dataTable
		 * Request berupa POST dan output berupa JSON
		 */
		public function get_list(){
			if($_SERVER['REQUEST_METHOD'] == "POST") {
				// config datatable
				$config_dataTable = array(
					'tabel' => 'distributor',
					'kolomOrder' => array(null, 'nama', 'alamat', 'pemilik', 'status', null),
					'kolomCari' => array( 'nama','alamat',  'no_telp', 'pemilik', 'status',),
					'orderBy' => array('id' => 'asc'),
					'kondisi' => false,
				);

				$dataDistributor = $this->DataTableModel->getAllDataTable($config_dataTable);

				$data = array();
				$no_urut = $_POST['start'];
				foreach($dataDistributor as $row) {
					$no_urut++;

					$status = ($row['status'] == "AKTIF") ? '<span class="label label-success">'.$row['status'].'</span>' : '<span class="label label-danger">'.$row['status'].'</span>';

					// button aksi
					$aksiDetail = '<button onclick="getView('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-info btn-flat" title="Lihat Detail"><i class="fa fa-eye"></i></button>';
					$aksiEdit = '<button onclick="getEdit('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-success btn-flat" title="Edit Data"><i class="fa fa-pencil"></i></button>';
					$aksiHapus = '<button onclick="getDelete('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-danger btn-flat" title="Hapus Data"><i class="fa fa-trash"></i></button>';
					
					$aksi = '<div class="btn-group">'.$aksiDetail.$aksiEdit.$aksiHapus.'</div>';
					
					$dataRow = array();
					$dataRow[] = $no_urut;
					$dataRow[] = $row['nama'];
					$dataRow[] = $row['alamat'];
					$dataRow[] = $row['pemilik'];
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
		public function action_add() {
			if($_SERVER['REQUEST_METHOD'] == "POST") {
				$data = isset($_POST) ? $_POST : false;

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
							'nama' => $this->validation->validInput($data['nama']),
							'alamat' => $this->validation->validInput($data['alamat']),
							'no_telp' => $this->validation->validInput($data['no_telp']),
							'pemilik' => $this->validation->validInput($data['pemilik']),
							'status' => $this->validation->validInput($data['status']),
							'created_by' => $_SESSION['sess_email']										
						);

						// insert bank
						$insert = $this->DistributorModel->insert($data);
						if($insert['success']) {
							$this->success = true;
							$this->notif = array(
								'type' => 'success',
								'title' => "Pesan Berhasil",
								'message' => "Tambah Data Distributor Baru Berhasil",
							);
						}
						else {
							$this->message = $insert['error'];
							$this->notif = array(
								'type' => 'error',
								'title' => "Pesan Gagal",
								'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
							);
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
					'status' => $this->success,
					'notif' => $this->notif,
					'error' => $this->error,
					'message' => $this->message
					// 'data' => $data
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
		public function edit($id) {
			if($_SERVER['REQUEST_METHOD'] == "POST") {
				$id = strtoupper($id);
				$data = !empty($this->DistributorModel->getById($id)) ? $this->DistributorModel->getById($id) : false;

				echo json_encode($data);
			}
			else { $this->redirect(); }

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
			if($_SERVER['REQUEST_METHOD'] == "POST") {
				$data = isset($_POST) ? $_POST : false;

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
							'nama' => $this->validation->validInput($data['nama']),
							'alamat' => $this->validation->validInput($data['alamat']),
							'no_telp' => $this->validation->validInput($data['no_telp']),
							'pemilik' => $this->validation->validInput($data['pemilik']),
							'status' => $this->validation->validInput($data['status']),
							'modified_by' => $_SESSION['sess_email']
						);

						// update bank
						$update = $this->DistributorModel->update($data);
						if($update['success']) {
							$this->success = true;
							$this->notif = array(
								'type' => 'success',
								'title' => "Pesan Berhasil",
								'message' => "Edit Data Distributor Berhasil",
							);
						}
						else {
							$this->message = $update['error'];
							$this->notif = array(
								'type' => 'error',
								'title' => "Pesan Gagal",
								'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
							);
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
					'status' => $this->success,
					'notif' => $this->notif,
					'error' => $this->error,
					'message' => $this->message
					// 'data' => $data
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

			$data_detail = !empty($this->DistributorModel->getById($id)) ? $this->DistributorModel->getById($id) : false;

			if(!$data_detail || (empty($id) || $id == "")) { $this->redirect(BASE_URL."distributor/"); }

			$css = array(
				'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
				'assets/bower_components/dropify/dist/css/dropify.min.css'
			);
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'assets/bower_components/dropify/dist/js/dropify.min.js',
				'app/views/distributor/js/initView.js',
				'app/views/distributor/js/initForm.js',
			);

			$config = array(
				'title' => 'Menu Distributor - Detail',
				'property' => array(
					'main' => 'Data Distributor',
					'sub' => 'Detail Data Distributor',
				),
				'css' => $css,
				'js' => $js,
			);

			$status = ($data_detail['status'] == "AKTIF") ? 
				'<span class="label label-success">'.$data_detail['status'].'</span>' : 
				'<span class="label label-danger">'.$data_detail['status'].'</span>';
			
			$data = array(
				'id' => strtoupper($data_detail['id']),
				'nama' => $data_detail['nama'],
				'alamat' => $data_detail['alamat'],
				'no_telp' => $data_detail['no_telp'],
				'pemilik' => $data_detail['pemilik'],
				'status' => $status
			);

			$this->layout('distributor/view', $config, $data);
		}

		/**
		 * Function delete
		 * method yang berfungsi untuk menghapus data
		 * param $id didapat dari url
		 * return json
		 */
		public function delete($id) {
			if($_SERVER['REQUEST_METHOD'] == "POST") {
				$id = strtoupper($id);
				if(empty($id) || $id == "") $this->redirect(BASE_URL."distributor/");

				if($this->DistributorModel->delete($id)) $this->status = true;

				echo json_encode($this->status);
			}
			else { $this->redirect(); }
		}

		/**
		 * Fungsi generate id otomatis
		 */
		public function get_last_id() {
			if($_SERVER['REQUEST_METHOD'] == "POST") {
				$data = !empty($this->DistributorModel->getLastID()['id']) ? $this->DistributorModel->getLastID()['id'] : false;
				
				if(!$data) { $id = 'DIS0001'; }
				else {
					$kode = 'DIS';
					$noUrut = (int)substr($data, 3, 4);
					$noUrut++;

					$id = $kode.sprintf("%04s", $noUrut);
					// print_r($data);
					// exit;
				}

				echo json_encode($id);
			}
			else { $this->redirect(); }
		}

		/**
		 * Fungsi history pembelian
		 * di menu Distributor
		 */
		public function get_history_distributor($id) {
			if($_SERVER['REQUEST_METHOD'] == "POST") {
				
				// config datatable
				$config_dataTable = array(
					'tabel' => 'v_history_distributor',
					'kolomOrder' => array('id', 'NAMA_DISTRIBUTOR', 'PEMILIK_DISTRIBUTOR', 'ID_OPERASIONAL_PROYEK','NAMA_KEBUTUHAN'),
					'kolomCari' => array('id', 'NAMA_DISTRIBUTOR', 'PEMILIK_DISTRIBUTOR', 'ID_OPERASIONAL_PROYEK','NAMA_KEBUTUHAN'),
					'orderBy' => array('id' => 'asc'),
					'kondisi' => 'where id = "'.$id.'"',
				);

				$dataHistory = $this->DataTableModel->getAllDataTable($config_dataTable);

				$data = array();
				// $no_urut = $_POST['start'];
				foreach($dataHistory as $row){
					// $no_urut++;
					
					$dataRow = array();
					// $dataRow[] = $no_urut;
					$dataRow[] = $row['id'];
					$dataRow[] = $row['NAMA_DISTRIBUTOR'];
					$dataRow[] = $row['PEMILIK_DISTRIBUTOR'];
					$dataRow[] = $row['ID_OPERASIONAL_PROYEK'];
					$dataRow[] = $row['NAMA_KEBUTUHAN'];
					
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
		 * Fungsi set_validation
		 * method yang berfungsi untuk validasi inputan secara server side
		 * param $data didapat dari post yang dilakukan oleh user
		 * return berupa array, status hasil pengecekan dan error tiap validasi inputan
		 */
		private function set_validation($data) {

			// nama
			$this->validation->set_rules($data['nama'], 'Nama Distributor', 'nama', 'string | 1 | 255 | required');
			// alamat
			$this->validation->set_rules($data['alamat'], 'Alamat Distributor', 'alamat', 'string | 1 | 255 | not_required');
			// no_telp
			$this->validation->set_rules($data['no_telp'], 'No Telepon Distributor', 'no_telp', 'string | 1 | 255 | required');
			// pemilik
			$this->validation->set_rules($data['pemilik'], 'Pemilik Distributor', 'pemilik', 'string | 1 | 255 | required');
			// status
			$this->validation->set_rules($data['status'], 'Status Distributor', 'status', 'string | 1 | 255 | required');

			return $this->validation->run();	
		}

	}