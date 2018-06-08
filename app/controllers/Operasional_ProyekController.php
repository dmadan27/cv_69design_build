<?php
Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

class Operasional_Proyek extends CrudAbstract{

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
				// 'app/views/operasional_proyek/js/initForm.js',
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
				$dataRow[] = $row['tgl'];
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

	public function action_add(){
			$data = isset($_POST) ? $_POST : false;
			$this->auth->cekToken($_SESSION['token_operasional_proyek']['add'], $data['token'], 'operasional_proyek');
			
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

				$this->model('Operasional_ProyekModel');
				// $getSaldo = $this->BankModel->getById($data['id_bank'])['saldo'];

				// if($data['nominal'] > $getSaldo){
				// 	$cek = false;
				// 	$error['nominal'] = "Nominal terlalu besar dan melebihi saldo bank";
				// }

				if($cek){
					// validasi inputan
					$data = array(
						'id' => $this->validation->validInput($data['id']),
						'id_proyek' => $this->validation->validInput($data['id_proyek']),
						'tgl' => $this->validation->validInput($data['tgl']),
						'nama' => $this->validation->validInput($data['nama']),
						'total' => $this->validation->validInput($data['total']),
					);

					if($this->Operasional_ProyeklModel->insert($data)) {
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
				'data' => $data
			);

			echo json_encode($output);		
		}

	public function form($id){

	}

	public function add(){

	}



	public function edit($id){

	}

	public function action_edit(){

	}

	public function detail($id){

	}

	public function delete($id){

	}

	public function export(){

	}

	private function set_validation(){
		
	}	

}