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
			
			// // set token
			// $_SESSION['token_bank'] = array(
			// 	'list' => md5($this->auth->getToken()),
			// 	'add' => md5($this->auth->getToken()),
			// );

			// $this->token = array(
			// 	'list' => password_hash($_SESSION['token_bank']['list'], PASSWORD_BCRYPT),
			// 	'add' => password_hash($_SESSION['token_bank']['add'], PASSWORD_BCRYPT),	
			// );

			// $data = array(
			// 	'token_list' => $this->token['list'],
			// 	'token_add' => $this->token['add'],
			// );

			$this->layout('operasional_proyek/list', $config);
	}

	public function action_add(){

	}
	public function form($id){

	}

	public function add(){

	}



	public function get_list(){
		// $token = isset($_POST['token_list']) ? $_POST['token_list'] : false;
			
			// cek token
			// $this->auth->cekToken($_SESSION['token_bank']['list'], $token, 'bank');
			
			// config datatable
			$config_dataTable = array(
				'tabel' => 'operasional_proyek',
				'kolomOrder' => array(null, 'id', 'id_proyek', 'tgl', 'nama', 'total', null),
				'kolomCari' => array('id'),
				'orderBy' => array('id' => 'asc'),
				'kondisi' => false,
			);

			$dataOperasionalProyek = $this->Operasional_ProyekModel->getAllDataTable($config_dataTable);

			// // set token
			// $_SESSION['token_bank']['edit'] = md5($this->auth->getToken());
			// $_SESSION['token_bank']['delete'] = md5($this->auth->getToken());
			
			// $this->token = array(
			// 	'edit' => password_hash($_SESSION['token_bank']['edit'], PASSWORD_BCRYPT),
			// 	'delete' => password_hash($_SESSION['token_bank']['delete'], PASSWORD_BCRYPT),	
			// );

			$data = array();
			$no_urut = $_POST['start'];
			foreach($dataOperasionalProyek as $row){
				$no_urut++;

				// $status = ($row['status'] == "AKTIF") ? '<span class="label label-success">'.$row['status'].'</span>' : '<span class="label label-danger">'.$row['status'].'</span>';

				//button aksi
				$aksiDetail = '<button onclick="getView('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-info btn-flat" title="Lihat Detail"><i class="fa fa-eye"></i></button>';
				// $aksiEdit = '<button onclick="getEdit('."'".$row["id"]."'".', '."'".$this->token["edit"]."'".')" type="button" class="btn btn-sm btn-success btn-flat" title="Edit Data"><i class="fa fa-pencil"></i></button>';
				// $aksiHapus = '<button onclick="getDelete('."'".$row["id"]."'".', '."'".$this->token["delete"]."'".')" type="button" class="btn btn-sm btn-danger btn-flat" title="Hapus Data"><i class="fa fa-trash"></i></button>';
				
				$aksi = '<div class="btn-group">'.$aksiDetail.'</div>';
				
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