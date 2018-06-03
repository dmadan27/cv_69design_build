<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
	
	/**
	*
	*/
	class Pengajuan_sub_kas_kecil extends CrudAbstract{

		protected $token;

		/**
		* 
		*/
		public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
			$this->model('Pengajuan_sub_kas_kecilModel');
			$this->helper();
			$this->validation();
		}	

		/**
		* 
		*/
		public function index(){
			$this->list();
		}

		/**
		* 
		*/
		protected function list(){
			$css = array('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css');
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'app/views/pengajuan_sub_kas_kecil/js/initList.js',
			);

			$config = array(
				'title' => array(
					'main' => 'Data Pengajuan Sub Kas Kecil',
					'sub' => 'List Semua Data Pengajuan Sub Kas Kecil',
				),
				'css' => $css,
				'js' => $js,
			);

			// set token
			$_SESSION['token_pengajuan_skc'] = array(
				'list' => md5($this->auth->getToken()),
				// 'add' => md5($this->auth->getToken()),
			);

			$this->token = array(
				'list' => password_hash($_SESSION['token_pengajuan_skc']['list'], PASSWORD_BCRYPT),
				// 'add' => password_hash($_SESSION['token_pengajuan_skc']['add'], PASSWORD_BCRYPT),	
			);

			$data = array(
				'token_list' => $this->token['list'],
				// 'token_add' => $this->token['add'],
			);
			
			$this->layout('pengajuan_sub_kas_kecil/list', $config, $data);
		}	

		/**
		* 
		*/
		public function get_list(){
			// cek token
			$token = isset($_POST['token_list']) ? $_POST['token_list'] : false;
			$this->auth->cekToken($_SESSION['token_pengajuan_skc']['list'], $token, 'pengajuan-sub-kas-kecil');

			// config datatable
			$config_dataTable = array(
				'tabel' => 'pengajuan_sub_kas_kecil',
				'kolomOrder' => array(null, 'id', 'id_sub_kas_kecil', 'id_proyek', 'tgl', 'total', 'dana_disetujui', 'status', null),
				'kolomCari' => array('id', 'id_sub_kas_kecil', 'id_proyek', 'tgl', 'total', 'dana_disetujui', 'status'),
				'orderBy' => array('id' => 'desc'),
				'kondisi' => false,
			);

			$dataPengajuan = $this->Pengajuan_sub_kas_kecilModel->getAllDataTable($config_dataTable);

			// set token
			$_SESSION['token_pengajuan_skc']['view'] = md5($this->auth->getToken());
			$_SESSION['token_pengajuan_skc']['edit'] = md5($this->auth->getToken());
			$_SESSION['token_pengajuan_skc']['delete'] = md5($this->auth->getToken());
			
			$this->token = array(
				'view' => password_hash($_SESSION['token_pengajuan_skc']['view'], PASSWORD_BCRYPT),
				'edit' => password_hash($_SESSION['token_pengajuan_skc']['edit'], PASSWORD_BCRYPT),
				'delete' => password_hash($_SESSION['token_pengajuan_skc']['delete'], PASSWORD_BCRYPT),	
			);

			$data = array();
			$no_urut = $_POST['start'];
			foreach($dataPengajuan as $row){
				$no_urut++;

				if(strtolower($row['status']) == "disetujui") $status = '<span class="label label-success">';
				else if(strtolower($row['status']) == "perbaiki") $status = '<span class="label label-warning">';	
				else if(strtolower($row['status']) == "ditolak") $status = '<span class="label label-danger">';
				else if(strtolower($row['status']) == "pending") $status = '<span class="label label-primary">';
				else $status = '<span class="label label-success">';

				$status .= $row['status'].'</span>';

				// button aksi
				$aksiDetail = '<button onclick="getView('."'".strtolower($row["id"])."'".', '."'".$this->token["view"]."'".')" type="button" class="btn btn-sm btn-info btn-flat" title="Lihat Detail"><i class="fa fa-eye"></i></button>';
				$aksiEdit = '<button onclick="getEdit('."'".strtolower($row["id"])."'".', '."'".$this->token["edit"]."'".')" type="button" class="btn btn-sm btn-success btn-flat" title="Edit Data"><i class="fa fa-pencil"></i></button>';
				$aksiHapus = '<button onclick="getDelete('."'".strtolower($row["id"])."'".', '."'".$this->token["delete"]."'".')" type="button" class="btn btn-sm btn-danger btn-flat" title="Hapus Data"><i class="fa fa-trash"></i></button>';
				
				$aksi = '<div class="btn-group">'.$aksiDetail.$aksiEdit.$aksiHapus.'</div>';
				
				$dataRow = array();
				$dataRow[] = $no_urut;
				$dataRow[] = $row['id'];
				$dataRow[] = $row['id_sub_kas_kecil'];
				$dataRow[] = $row['id_proyek'];
				$dataRow[] = $this->helper->cetakTgl($row['tgl'], 'full');
				$dataRow[] = $this->helper->cetakRupiah($row['total']);
				$dataRow[] = $this->helper->cetakRupiah($row['dana_disetujui']);
				$dataRow[] = $status;
				$dataRow[] = $aksi;

				$data[] = $dataRow;
			}

			$output = array(
				'draw' => $_POST['draw'],
				'recordsTotal' => $this->Pengajuan_sub_kas_kecilModel->recordTotal(),
				'recordsFiltered' => $this->Pengajuan_sub_kas_kecilModel->recordFilter(),
				'data' => $data,
			);

			echo json_encode($output);
		}

		/**
		* 
		*/
		public function form($id){
			if($id)	$this->edit(strtoupper($id));
			else $this->add();
		}

		/**
		* 
		*/
		protected function add(){

		}

		/**
		* 
		*/
		public function action_add(){

		}

		/**
		* 
		*/
		protected function edit($id){

		}

		/**
		*
		*/
		public function get_edit($id){

		}

		/**
		* 
		*/
		public function action_edit(){

		}

		/**
		*
		*/
		public function detail($id){

		}

		/**
		*
		*/
		public function delete($id){

		}

		/**
		*
		*/
		public function export(){

		}
	}