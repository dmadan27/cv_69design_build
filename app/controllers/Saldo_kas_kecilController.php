<?php
	Defined("BASE_PATH") or die(ACCESS_DENIED);

	/**
	 * Class Saldo Kas Kecil
	 * Extend Abstract Crud_modalsAbstract
	 */
	class Saldo_kas_kecil extends Controller {

		private $status = false;
		private $success = false;
		private $notif = array();
		private $error = array();
		private $message = NULL;


		/**
		 * 
		 */
		public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
			$this->model('Mutasi_saldo_kas_kecilModel');
			$this->model('Saldo_kas_kecilModel');
			$this->model('UserModel');
			$this->helper();
			$this->validation();
			$this->excel();
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
			$saldo_kasKecil = $this->UserModel->getKasKecil($_SESSION['sess_email']);
			$css = array(
				'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
				'assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
				'assets/bower_components/select2/dist/css/select2.min.css'
			);
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
				'assets/bower_components/select2/dist/js/select2.full.min.js',
				'app/views/mutasi_saldo_kas_kecil/js/initList.js',
			);

			$config = array(
				'title' => 'Menu Mutasi Saldo Kas Kecil',
				'property' => array(
					'main' => 'Data Mutasi Saldo Kas Kecil',
					'sub' => 'List Mutasi Saldo Kas Kecil',
				),
				'css' => $css,
				'js' => $js,
			);
			
			$data = array(
				'saldo' => $this->helper->cetakRupiah($saldo_kasKecil['saldo']),
			);

			$this->layout('mutasi_saldo_kas_kecil/list', $config, $data);
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
					'kolomCari' => array('id', 'tgl', 'uang_masuk', 'uang_keluar', 'saldo', 'ket'),
					'orderBy' => array('id' => 'desc'),
					'kondisi' => 'where id_kas_kecil = "'.$_SESSION['sess_id'].'"',
				);

				$dataMutasi = $this->Mutasi_saldo_kas_kecilModel->getAllDataTable($config_dataTable);

				$data = array();
				$no_urut = $_POST['start'];
				foreach($dataMutasi as $row){
					$no_urut++;

					
					
					$dataRow = array();
					$dataRow[] = $no_urut;
					// $dataRow[] = $row['id'];
					$dataRow[] = $this->helper->cetakTgl($row['tgl'], 'full');
					$dataRow[] = $this->helper->cetakRupiah($row['uang_masuk']);
					$dataRow[] = $this->helper->cetakRupiah($row['uang_keluar']);
					$dataRow[] = $this->helper->cetakRupiah($row['saldo']);
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

		/**
		 * Export data ke format Excel
		 */
		public function export(){
			$tgl_awal = $_GET['tgl_awal'];
			$tgl_akhir = $_GET['tgl_akhir'];

			$row = $this->Saldo_kas_kecilModel->getExport($tgl_awal, $tgl_akhir);
			$header = array_keys($row[0]); 
			$header[1] = 'ID KAS KECIL';
			$this->excel->setProperty('Laporan Mutasi Saldo Kas Kecil','Laporan Mutasi Saldo Kas Kecil','Data Laporan Mutasi Saldo Kas Kecil');
			$this->excel->setData($header, $row);
			$this->excel->getData('Data Mutasi Saldo Kas Kecil', 'Data Mutasi Saldo Kas Kecil', 4, 5 );

			$this->excel->getExcel('Data Mutasi Saldo Kas Kecil');	
		}

	}