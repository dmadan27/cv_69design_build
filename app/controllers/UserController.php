<?php 
	Defined("BASE_PATH") or die(ACCESS_DENIED);

	/**
	 * 
	 */
	class User extends Controller {

		protected $token;

		/**
		 * load auth, cekAuth
		 * load default model, BankModel
		 * load helper dan validation
		 */
		public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
			$this->model('UserModel');
			$this->model('DataTableModel');
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
			);
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'assets/js/library/export.js',
				'app/views/user/js/initList.js',
				'app/views/user/js/initForm.js',
			);

			$config = array(
				'title' => 'Menu User',
				'property' => array(
					'main' => 'Data User',
					'sub' => 'List Semua Data User',
				),
				'css' => $css,
				'js' => $js,
			);

			$this->layout('user/list', $config, $data = null);
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
				'tabel' => 'v_all_user',
				'kolomOrder' => array(null, 'username', 'nama','status', 'level', null),
				'kolomCari' => array('username'),
				'orderBy' => array('username' => 'asc'),
				'kondisi' => false,
			);

			$dataUser = $this->DataTableModel->getAllDataTable($config_dataTable);

			$data = array();
			$no_urut = $_POST['start'];
			foreach($dataUser as $row){
				$no_urut++;

				$status = ($row['status'] == "AKTIF") ? '<span class="label label-success">'.$row['status'].'</span>' : '<span class="label label-danger">'.$row['status'].'</span>';

				// button aksi
				$aksiDetail = '<button onclick="getView('."'".$row["username"]."','".$row["level"]."'".')" type="button" class="btn btn-sm btn-info btn-flat" title="Lihat Detail"><i class="fa fa-eye"></i></button>';
				$aksiEdit = '<button onclick="getEdit('."'".$row["username"]."'".')" type="button" class="btn btn-sm btn-success btn-flat" title="Edit Data"><i class="fa fa-pencil"></i></button>';
				$aksiHapus = '<button onclick="getDelete('."'".$row["username"]."'".')" type="button" class="btn btn-sm btn-danger btn-flat" title="Hapus Data"><i class="fa fa-trash"></i></button>';
				
				$aksi = '<div class="btn-group">'.$aksiDetail.$aksiEdit.$aksiHapus.'</div>';
				
				$dataRow = array();
				$dataRow[] = $no_urut;
				$dataRow[] = $row['username'];
				$dataRow[] = $row['nama'];
				$dataRow[] = $status;
				$dataRow[] = $row['level'];
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

		/**
		 * Mendapatkan link detail data user.
		 */
		public function get_link_detail() {
			if (($_SERVER['REQUEST_METHOD'] == 'POST') && ($_SESSION['sess_level'] === 'OWNER')) {

				$username = $_POST['username'] ?? false;
				$level = $_POST['level'] ?? false;

				$response = array(
					'success' => false,
					'message' => 'Tidak ada detail yang dapat dilihat dari user ini.',
				);

				switch ($level) {
					case 'KAS BESAR':
						$this->model("Kas_besarModel");

						$kas_besar = $this->Kas_besarModel->getByEmail($username) ?? false;
						if ($kas_besar) {
							$response['success'] = true;
							$response['link'] = BASE_URL.'kas-besar/detail/'.$kas_besar["id"];
							unset($response['message']);
						}
						break;
					
					case 'KAS KECIL':
						$this->model("Kas_kecilModel");

						$kas_kecil = $this->Kas_kecilModel->getByEmail($username) ?? false;
						if ($kas_kecil) {
							$response['success'] = true;
							$response['link'] = BASE_URL.'kas-kecil/detail/'.$kas_kecil["id"];
							unset($response['message']);
						}
						break;
					
					case 'SUB KAS KECIL':
						$this->model("Sub_kas_kecilModel");

						$skk = $this->Sub_kas_kecilModel->getByEmail($username) ?? false;
						if ($kas_kecil) {
							$response['success'] = true;
							$response['link'] = BASE_URL.'sub-kas-kecil/detail/'.$skk["id"];
							unset($response['message']);
						}
						break;
					
					default:
						break;
				}
				echo json_encode($response);

			} else { die(ACCESS_DENIED); }
		}

		/**
		 * Function get_mutasi
		 * method yang berfungsi untuk get data mutasi bank sesuai dengan id
		 * dipakai di detail data
		 */
		public function get_mutasi(){
			$data = isset($_POST) ? $_POST : false;
			// cek token
			$this->auth->cekToken($_SESSION['token_bank']['view'], $data['token_view'], 'bank');
			
			// config datatable
			$config_dataTable = array(
				'tabel' => 'mutasi_bank',
				'kolomOrder' => array(null, 'tgl', 'uang_masuk', 'uang_keluar', 'saldo', 'ket'),
				'kolomCari' => array('tgl', 'uang_masuk', 'uang_keluar', 'saldo', 'ket'),
				'orderBy' => array('id' => 'desc'),
				'kondisi' => 'WHERE id = '.$data['id'].' ',
			);

			$dataMutasi = $this->DataTableModel->getAllDataTable($config_dataTable);

			$data = array();
			$no_urut = $_POST['start'];
			foreach($dataMutasi as $row){
				$no_urut++;
				
				$dataRow = array();
				$dataRow[] = $no_urut;
				$dataRow[] = $row['tgl'];
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

		/**
		 * 
		 */
		public function export(){

		}

		/**
		 * Fungsi set_validation
		 * method yang berfungsi untuk validasi inputan secara server side
		 * param $data didapat dari post yang dilakukan oleh user
		 * return berupa array, status hasil pengecekan dan error tiap validasi inputan
		 */
		private function set_validation($data){
			// $required = ($data['action'] == "action-edit") ? 'not_required' : 'required';

			// // nama bank
			// $this->validation->set_rules($data['nama'], 'Nama Bank', 'nama', 'string | 1 | 255 | required');
			// // saldo awal
			// $this->validation->set_rules($data['saldo'], 'Saldo Awal Bank', 'saldo', 'nilai | 0 | 99999999999 | '.$required);
			// // status
			// $this->validation->set_rules($data['status'], 'Status Bank', 'status', 'string | 1 | 255 | required');

			// return $this->validation->run();
		}

	}