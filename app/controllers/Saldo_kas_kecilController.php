<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	* Class Saldo Kas Kecil
	* Extend Abstract Crud_modalsAbstract
	*/
	class Saldo_kas_kecil extends Crud_modalsAbstract{

		private $token;
		// private $status = false;
		// private $success = false;
		// private $notif = array();
		// private $error = array();
		// private $message = NULL;


		/**
		*
		*/
		public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
			$this->model('Mutasi_saldo_kas_kecilModel');
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
				'app/views/mutasi_saldo_kas_kecil/js/initList.js',
			);

			$config = array(
				'title' => array(
					'main' => 'Data Mutasi Saldo Kas Kecil',
					'sub' => 'Ini adalah halaman Data Mutasi Saldo Kas Kecil.',
				),
				'css' => $css,
				'js' => $js,
			);
			
			$this->layout('mutasi_saldo_kas_kecil/list', $config, $data = NULL);
		}	

		/**
		* 
		*/
		public function get_list(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				// config datatable
				$config_dataTable = array(
					'tabel' => 'mutasi_saldo_kas_kecil',
					'kolomOrder' => array(null, 'id', 'id_kas_kecil', 'tgl', 'uang_masuk', 'uang_keluar', 'saldo', 'ket'),
					'kolomCari' => array('id','id_kas_kecil', 'tgl', 'uang_masuk', 'uang_keluar', 'saldo', 'ket'),
					'orderBy' => array('id' => 'desc'),
					'kondisi' => false,
				);

				$dataMutasi = $this->Mutasi_saldo_kas_kecilModel->getAllDataTable($config_dataTable);

				$data = array();
				$no_urut = $_POST['start'];
				foreach($dataMutasi as $row){
					$no_urut++;

					// $status = (strtolower($row['status']) == "AKTIF") ? '<span class="label label-success">'.$row['status'].'</span>' : '<span class="label label-primary">'.$row['status'].'</span>';

					// //button aksi
					// $aksiDetail = '<button onclick="getView('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-info btn-flat" title="Lihat Detail"><i class="fa fa-eye"></i></button>';
					// $aksiEdit = '<button onclick="getEdit('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-success btn-flat" title="Edit Data"><i class="fa fa-pencil"></i></button>';
					// $aksiHapus = '<button onclick="getDelete('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-danger btn-flat" title="Hapus Data"><i class="fa fa-trash"></i></button>';
					
					// $aksi = '<div class="btn-group">'.$aksiDetail.$aksiEdit.$aksiHapus.'</div>';
					
					$dataRow = array();
					$dataRow[] = $no_urut;
					// $dataRow[] = $row['id'];
					$dataRow[] = $row['id_kas_kecil'];
					$dataRow[] = $row['tgl'];
					$dataRow[] = $row['uang_masuk'];
					$dataRow[] = $row['uang_keluar'];
					$dataRow[] = $row['saldo'];
					$dataRow[] = $row['ket'];

					$data[] = $dataRow;
				}

				$output = array(
					'draw' => $_POST['draw'],
					'recordsTotal' => $this->Mutasi_saldo_kas_kecilModel->recordTotal(),
					'recordsFiltered' => $this->Mutasi_saldo_kas_kecilModel->recordFilter(),
					'data' => $data,
				);

				echo json_encode($output);
			}
			else $this->redirect();
		}

		public function action_add(){

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
		
		/**
		*	Export data ke format Excel
		*/

	}