<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	class Beranda_kas_besar extends CrudAbstract{

		private $token;
		// private $status = false;

		/** Penambahan beberapa property baru */
		private $success = false;
		private $notif = array();
		private $error = array();
		private $message = NULL;


		public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
			$this->model('Beranda_kas_besarModel');
			$this->helper();
			$this->validation();
			$this->excel();
		}

		public function index(){
			$this->list();

		}

		/**
		 * Method list
		 * Proses menampilkan list semua data beranda
		 */
		protected function list(){
			$css = array('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css');
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'app/views/Beranda_kas_besar/js/initList.js',
			);

			$config = array(
				'title' => array(
					'main' => 'Data Rekapitulasi Keuangan',
					'sub' => 'List Semua Data Rekapitulasi',
				),
				'css' => $css,
				'js' => $js,
			);

			$this->layout('Beranda_kas_besar/list', $config, $data = null);
		}

		/**
		 * Method get_list
		 * Proses get data untuk list proyek
		 * Data akan di parsing dalam bentuk dataTable
		 * @return output {object} array berupa json
		 */
		public function get_list(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){



				// config datatable
				$config_dataTable = array(
					'tabel' => 'v_proyek_berjalan_selesai',
					'kolomOrder' => array(null, 'id', 'pembangunan', 'pemilik', 'status', 'total'),
					'kolomCari' => array('id', 'pembangunan', 'pemilik', 'status', 'total'),
					'orderBy' => array('id' => 'desc'),
					'kondisi' => false,
				);


				

				$databerandaKasBesar = $this->Beranda_kas_besarModel->getAllDataTable($config_dataTable);

				$data = array();
				$no_urut = $_POST['start'];
				foreach($databerandaKasBesar as $row){
					$no_urut++;

					
					
					$dataRow = array();
					$dataRow[] = $no_urut;
					$dataRow[] = $row['id'];
					$dataRow[] = $row['pembangunan'];
					$dataRow[] = $row['pemilik'];
					$dataRow[] = $row['status'];
					$dataRow[] = $this->helper->cetakRupiah($row['total']);

					$data[] = $dataRow;
				}


				$output = array(
					'draw' => $_POST['draw'],
					'recordsTotal' => $this->Beranda_kas_besarModel->recordTotal(),
					'recordsFiltered' => $this->Beranda_kas_besarModel->recordFilter(),
					'data' => $data,
				);

				echo json_encode($output);
			}
			else { $this->redirect(); }	
			
		}

		/* 

		*/
		public function get_listSaldo_KK_andSKK(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){

				$config_dataTable = array(
					'tabel' => 'v_saldo_kaskecil_and_subkaskecil',
					'kolomOrder' => array(null, 'id', 'nama', 'saldo'),
					'kolomCari' => array('id', 'nama', 'saldo'),
					'orderBy' => array('id' => 'asc'),
					'kondisi' => false,
				);

				$dataSaldo_saldo_KK_and_SKK = $this->Beranda_kas_besarModel->getAllDataTable($config_dataTable);

				$data = array();
				// $no_urut = $_POST['start'];
				foreach($dataSaldo_saldo_KK_and_SKK as $row){
					
					
					$dataRow = array();
					// $dataRow[] = $no_urut;
					$dataRow[] = $row['id'];
					$dataRow[] = $row['nama'];
					$dataRow[] = $row['saldo'];

					$data[] = $dataRow;
				}

				$output = array(
					'draw' => $_POST['draw'],
					'recordsTotal' => $this->Beranda_kas_besarModel->recordTotal(),
					'recordsFiltered' => $this->Beranda_kas_besarModel->recordFilter(),
					'data' => $data,
				);

				echo json_encode($output);
			}
			else{ $this->redirect(); }
		}

		public function form($id){
			if($id)	{ $this->edit(strtoupper($id)); }
			else { $this->add(); }
		}

		/**
		 * Method add
		 * Proses render form add proyek
		 */
		protected function add(){
			
		}

		/**
		 * Method action_add
		 * Proses penambahan data proyek
		 * @return output {object} array berupa json
		 */
		public function action_add(){
			
		}


		/**
		 * Method edit
		 * Proses render form edit proyek
		 * @param id {string}
		 */
		protected function edit($id){
			
		}

		/**
		 * Method action_edit
		 * Proses pengeditan data proyek
		 * @return output {object} array berupa json
		 */
		public function action_edit(){
			
		}

		/**
		 * Method detail
		 * Proses render detail view proyek
		 * @param id {string}
		 */
		public function detail($id){
		
		}

		/**
		 * Method delete
		 * Proses hapus data proyek
		 * @param id {string}
		 * @return result 
		 */
		public function delete($id){
			
		}

		/**
		 * Method export
		 */
		public function export(){
			
		}

	}