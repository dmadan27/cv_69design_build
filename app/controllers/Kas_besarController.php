<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
	
	/**
	*
	*/
	class Kas_besar extends CrudAbstract{

		protected $token;


		/**
		*
		*/
		public function __construct(){
				$this->auth();
				$this->auth->cekAuth();
				$this->model('Kas_besarModel');
				$this->helper();
				$this->validation();
		}	


		public function index(){
				$this->list();
			}


		protected function list(){

				$css = array('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css');
				$js = array(
					'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
					'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
					'app/views/kas_besar/js/initList.js',
				);

				$config = array(
					'title' => array(
						'main' => 'Data Kas Besar',
						'sub' => 'Menampilkan Semua Data Kas Besar',
					),
					'css' => $css,
					'js' => $js,
				);

				$data = array('token' => $this->setToken('list'));
				$this->layout('kas_besar/list', $config, $data);
			}
			/**
			* 
			*/
			public function get_list(){
				// cek token
				$token = isset($_POST['token']) ? $_POST['token'] : false;
				$this->auth->cekToken($token, $_SESSION['token']['kas_besar']['list'], 'kas_besar');
				
				// config datatable
				$config_dataTable = array(
					'tabel' => 'proyek',
					'kolomOrder' => array(null, 'id', 'pemilik', 'tgl', 'pembangunan', 'kota', 'total', 'status', null),
					'kolomCari' => array('id', 'pemilik', 'tgl', 'pembangunan', 'luas_area', 'status'),
					'orderBy' => array('id' => 'desc', 'status' => 'asc'),
					'kondisi' => false,
				);

				$dataProyek = $this->ProyekModel->getAllDataTable($config_dataTable);

				$sess_token = $_SESSION['token']['proyek']['list'];

				$data = array();
				$no_urut = $_POST['start'];
				foreach($dataProyek as $row){
					$no_urut++;

					$status = (strtolower($row['status']) == "selesai") ? '<span class="label label-success">'.$row['status'].'</span>' : '<span class="label label-primary">'.$row['status'].'</span>';

					// button aksi
					$aksiDetail = '<button onclick="getView('."'".strtolower($row["id"])."'".', '."'".$sess_token."'".')" type="button" class="btn btn-sm btn-info btn-flat" title="Lihat Detail"><i class="fa fa-eye"></i></button>';
					$aksiEdit = '<button onclick="getEdit('."'".strtolower($row["id"])."'".', '."'".$sess_token."'".')" type="button" class="btn btn-sm btn-success btn-flat" title="Edit Data"><i class="fa fa-pencil"></i></button>';
					$aksiHapus = '<button onclick="getDelete('."'".strtolower($row["id"])."'".', '."'".$sess_token."'".')" type="button" class="btn btn-sm btn-danger btn-flat" title="Hapus Data"><i class="fa fa-trash"></i></button>';
					
					$aksi = '<div class="btn-group">'.$aksiDetail.$aksiEdit.$aksiHapus.'</div>';
					
					$dataRow = array();
					$dataRow[] = $no_urut;
					$dataRow[] = $row['id'];
					$dataRow[] = $row['pemilik'];
					$dataRow[] = $this->helper->cetakTgl($row['tgl'], 'full');
					$dataRow[] = $row['pembangunan'];
					$dataRow[] = $row['kota'];
					$dataRow[] = $this->helper->cetakRupiah($row['total']);
					$dataRow[] = $status;
					$dataRow[] = $aksi;

					$data[] = $dataRow;
				}

				$output = array(
					'draw' => $_POST['draw'],
					'recordsTotal' => $this->ProyekModel->recordTotal(),
					'recordsFiltered' => $this->ProyekModel->recordFilter(),
					'data' => $data,
				);

				echo json_encode($output);
			}	


			public function form(){
				$id = isset($_GET['id']) ? $_GET['id'] : false;

				// cek jenis form
				if(!$id) $this->add();
				else $this->edit($id);
			}

}