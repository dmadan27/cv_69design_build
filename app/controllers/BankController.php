<?php 
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	class Bank extends Crud_modalsAbstract{

		/**
		*
		*/
		public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
			$this->model('BankModel');
			$this->helper();
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
			// set config untuk layouting
			$css = array('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css');
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'app/views/bank/js/initList.js',
				'app/views/bank/js/initForm.js',
			);

			$config = array(
				'title' => array(
					'main' => 'Data Bank',
					'sub' => '',
				),
				'css' => $css,
				'js' => $js,
			);
			
			// set token
			$token = md5($this->auth->getToken()); // md5
			$_SESSION['token'] = $token; // md5 di hash
			$data = array(
				'tokenCrsf' => password_hash($token, PASSWORD_BCRYPT),
			);

			$this->layout('bank/list', $config, $data);
		}	

		/**
		*
		*/
		public function get_list(){
			$token = isset($_POST['token']) ? $_POST['token'] : false;

			// cek token
			if(!password_verify($_SESSION['token'], $token)) $this->redirect(BASE_URL.'bank/');
			else{
				// config datatable
				$config_dataTable = array(
					'tabel' => 'bank',
					'kolomOrder' => array(null, 'nama', 'saldo', 'status', null),
					'kolomCari' => array('nama', 'saldo', 'status'),
					'orderBy' => array('id' => 'asc'),
					'kondisi' => false,
				);

				$dataBank = $this->BankModel->getAllDataTable($config_dataTable);

				$data = array();
				$no_urut = $_POST['start'];
				foreach($dataBank as $row){
					$no_urut++;

					// button aksi
					$aksiDetail = '<button onclick="getView('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-info btn-flat" title="Lihat Detail"><i class="fa fa-eye"></i></button>';
					$aksiEdit = '<button onclick="getEdit('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-success btn-flat" title="Edit Data"><i class="fa fa-pencil"></i></button>';
					$aksiHapus = '<button onclick="getDelete('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-danger btn-flat" title="Hapus Data"><i class="fa fa-trash"></i></button>';
					
					$aksi = '<div class="btn-group">'.$aksiDetail.$aksiEdit.$aksiHapus.'</div>';
					
					$dataRow = array();
					$dataRow[] = $no_urut;
					$dataRow[] = $row['nama'];
					$dataRow[] = $this->helper->cetakRupiah($row['saldo']);
					$dataRow[] = $row['status'];
					$dataRow[] = $aksi;

					$data[] = $dataRow;
				}

				$output = array(
					'draw' => $_POST['draw'],
					'recordsTotal' => $this->BankModel->recordTotal(),
					'recordsFiltered' => $this->BankModel->recordFilter(),
					'data' => $data,
				);

				echo json_encode($output);
			}			
		}

		/**
		*
		*/
		public function action_add($data){

		}

		/**
		*
		*/
		public function edit($id){

		}

		/**
		*
		*/
		public function action_edit($data){

		}

		/**
		*
		*/
		public function detail($id){
			echo "Halaman View";
		}

		/**
		*
		*/
		public function detele($id){

		}

		/**
		*
		*/
		public function export(){

		}

	}