<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	*
	*/
	class Mutasi_bank extends Controller{

		private $token;
		private $status = false;

		/**
		*
		*/
		public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
			$this->model('Mutasi_bankModel');
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
		private function list(){
			$css = array('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css');
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'app/views/mutasi_bank/js/initList.js',
			);

			$config = array(
				'title' => array(
					'main' => 'Data Mutasi Bank',
					'sub' => 'Menampilkan Semua Data Mutasi Bank',
				),
				'css' => $css,
				'js' => $js,
			);
			
			$this->layout('mutasi_bank/list', $config, $data = NULL);
		}	

		/**
		* 
		*/
		public function get_list(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				// config datatable
				$config_dataTable = array(
					'tabel' => 'mutasi_bank',
					// 'kolomOrder' => array(null, 'id', 'nama', 'alamat', 'status', null),
					// 'kolomCari' => array('id', 'nama', 'alamat', 'status'),
					// 'orderBy' => array('id' => 'desc', 'status' => 'asc'),
					'kondisi' => false,
				);

				$dataMutasi = $this->Mutasi_bankModel->getAllDataTable($config_dataTable);

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
					
					// $dataRow = array();
					// $dataRow[] = $no_urut;
					// $dataRow[] = $row['id'];
					// $dataRow[] = $row['nama'];
					// $dataRow[] = $row['alamat'];
					// $dataRow[] = $row['status'];
					// $dataRow[] = $aksi;

					$data[] = $dataRow;
				}

				$output = array(
					'draw' => $_POST['draw'],
					'recordsTotal' => $this->Mutasi_bankModel->recordTotal(),
					'recordsFiltered' => $this->Mutasi_bankModel->recordFilter(),
					'data' => $data,
				);

				echo json_encode($output);
			}
			else $this->redirect();
		}
	}