<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	* Class Distributor extend ke abstract crud modals
	*/
	class Distributor extends Crud_modalsAbstract{
		private $token;
		private $status = false;

		/**
		*	Default load saat pertama kali controller diakses
		*/

		public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
			$this->model('DistributorModel');
			$this->helper();
			$this->validation();
			$this->excel();

		}

		/**
		* Method pertama kali yang diakses
		*/
		public function index(){
			$this->list();
		}

		/**
		* Method List
		* Menampilkan list semua data proyek
		* Passing data css dan js yang dibutuhkan di list distributor
		*/
		protected function list(){
			// set config untuk layouting
			$css = array(
				'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
			);
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'assets/plugins/input-mask/jquery.inputmask.bundle.js',
				'app/views/distributor/js/initList.js',
				'app/views/distributor/js/initForm.js',
			);

			$config = array(
				'title' => array(
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
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				// config datatable
				$config_dataTable = array(
					'tabel' => 'distributor',
					'kolomOrder' => array(null, 'nama', 'alamat', 'pemilik', 'status', null),
					'kolomCari' => array( 'nama','alamat', 'jenis', 'no_telp', 'pemilik', 'status',),
					'orderBy' => array('id' => 'asc'),
					'kondisi' => false,
				);

				$dataDistributor = $this->DistributorModel->getAllDataTable($config_dataTable);

				$data = array();
				$no_urut = $_POST['start'];
				foreach($dataDistributor as $row){
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
					'recordsTotal' => $this->DistributorModel->recordTotal(),
					'recordsFiltered' => $this->DistributorModel->recordFilter(),
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
					$notif = array(
						'type' => 'error',
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
							'id' => $this->validation->validInput($data['id']),
							'nama' => $this->validation->validInput($data['nama']),
							'alamat' => $this->validation->validInput($data['alamat']),
							'jenis' => $this->validation->validInput($data['jenis']),
							'jenis' => $this->validation->validInput($data['jenis']),
							'no_telp' => $this->validation->validInput($data['no_telp']),
							'pemilik' => $this->validation->validInput($data['pemilik']),		
						);

						// insert bank
						if($this->DistributorModel->insert($data)) {
							$this->status = true;
							$notif = array(
								'type' => 'success',
								'title' => "Pesan Berhasil",
								'message' => "Tambah Data Distributor Baru Berhasil",
							);
						}
						else {
							$notif = array(
								'type' => 'error',
								'title' => "Pesan Gagal",
								'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
							);
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
					'status' => $this->status,
					'notif' => $notif,
					'error' => $error,
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
		public function edit($id){

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

		}

		/**
		* Function detail
		* method untuk get data detail dan setting layouting detail
		* param $id didapat dari url
		*/
		public function detail($id){

		}

		/**
		* Function delete
		* method yang berfungsi untuk menghapus data
		* param $id didapat dari url
		* return json
		*/
		public function delete($id){

		}

		/**
		*	Export data ke format Excel
		*/
		public function export(){

		}

		/**
		* Fungsi generate id otomatis
		*/
		public function get_last_id(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$data = !empty($this->DistributorModel->getLastID()['id']) ? $this->DistributorModel->getLastID()['id'] : false;

				if(!$data) $id = 'DIS001';
				else{
					$kode = 'DIS';
					$noUrut = (int)substr($data, 3, 4);
					$noUrut++;

					$id = $kode.sprintf("%04s", $noUrut);
				}

				echo json_encode($id);
			}
			else $this->redirect();
		}

		/**
		* Fungsi set_validation
		* method yang berfungsi untuk validasi inputan secara server side
		* param $data didapat dari post yang dilakukan oleh user
		* return berupa array, status hasil pengecekan dan error tiap validasi inputan
		*/
		private function set_validation($data){

		}


















	}