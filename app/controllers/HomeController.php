<?php
	Defined("BASE_PATH") or die(ACCESS_DENIED);

	/**
	 * Class home, default controller
	 * load class auth
	 * cek auth
	 */
	class Home extends Controller{

		/**
		 * Load class auth, cek auth
		 */
		public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
			$this->model('HomeModel');
			$this->helper();
		}

		/**
		 * Fungsi index, cek session level dan arahkan beranda sesuai dengan session level
		 */
		public function index(){
			// cek jenis user
			switch (strtolower($_SESSION['sess_level'])) {
				// arahkan ke beranda masing-masing
				case 'kas besar':
					$this->beranda_kasBesar();
					break;
					
				case 'kas kecil':
					$this->beranda_kasKecil();
					break;

				case 'owner':
					$this->beranda_owner();
					break;

				default:
					die();
					break;
			}

		}

		/**
		 * Beranda owner
		 */
		private function beranda_owner(){
			// config css-js
			$css = array(
				'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
				'assets/bower_components/dropify/dist/css/dropify.min.css'

			);
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'assets/bower_components/dropify/dist/js/dropify.min.js'
					
			);

			$config = array(
				'title' => array(
					'title' => 'Beranda',
				),
				'property' => array(
					'main' => 'Beranda',
					'sub' => 'Dashboard Owner',
				),
				'css' => $css,
				'js' => $js,
			);

			$data = null;

			$this->layout('beranda/owner', $config, $data);
		}

		/**
		 * Beranda Kas Besar
		 */
		private function beranda_kasBesar(){

			$countUser = $this->HomeModel->getCountUser();

			$countAccpkk = $this->HomeModel->getAccpkk();
			$countPendingpkk = $this->HomeModel->getPendingpkk();

			$countOprMasuk = $this->HomeModel->getOprMasuk();
			$countOprKeluar = $this->HomeModel->getOprKeluar();

			$countOprKredit = $this->HomeModel->getOprProyekKredit();
			$countOprTunai = $this->HomeModel->getOprProyekTunai();

			$countSpkk = $this->HomeModel->getSpkk();

			// config css-js
			$css = array(
				'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
				'assets/bower_components/dropify/dist/css/dropify.min.css'

			);
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'assets/bower_components/dropify/dist/js/dropify.min.js',
				'app/views/beranda/js/initKasbesar.js'
			);

			$config = array(
				'title' => array(
					'title' => 'Beranda',
				),
				'property' => array(
					'main' => 'Beranda',
					'sub' => 'Dashboard Kas Besar',
				),
				'css' => $css,
				'js' => $js,
			);

			$data = array(
				'user_aktif' => $countUser['user_aktif'],
				'acc_pkk' => $countAccpkk['jml_transaksi_disetujui'],
				'pskk' => $countSpkk['jml_transaksi_spkk'],
				'pending_pkk' => $countPendingpkk['jml_transaksi_pending'],
				'pending_pkk' => $countPendingpkk['jml_transaksi_pending'],
				'jml_transaksi_masuk' => $countOprMasuk['jml_uang_masuk'],
				'jml_transaksi_keluar' => $countOprKeluar['jml_uang_keluar'],
				'jml_transaksi_tunai' => $countOprTunai['jml_transaksi_tunai'],
				'jml_transaksi_kredit' => $countOprKredit['jml_transaksi_kredit']
			);

			$this->layout('beranda/kas_besar', $config, $data);
		}

		/**
		 * Table Bank Beranda Kas Besar
		 */
		public function get_bank_list() {
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				// config datatable
				$config_dataTable = array(
					'tabel' => 'bank',
					'kolomOrder' => array(null, 'nama', 'saldo', null),
					'kolomCari' => array('nama', 'saldo'),
					'orderBy' => array('nama' => 'asc'),
					'kondisi' => false,
				);

				$dataBank = $this->HomeModel->getAllDataTable($config_dataTable);
	
				$data = array();
				foreach($dataBank as $row){	
					$dataRow = array();
					$dataRow[] = $row['nama'];
					$dataRow[] = $this->helper->cetakRupiah($row['saldo']);
					$data[] = $dataRow;
				}

				$output = array(
					'draw' => $_POST['draw'],
					'recordsTotal' => $this->HomeModel->recordTotal(),
					'recordsFiltered' => $this->HomeModel->recordFilter(),
					'data' => $data,
				);
				echo json_encode($output);
			} else { 
				$this->redirect();
			}		
		}

		/**
		 * Table Proyek Beranda Kas Besar
		 */
		public function get_proyek_list() {
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				// config datatable
				$config_dataTable = array(
					'tabel' => 'v_proyek_dashboard',
					'kolomOrder' => array(null, 'id_proyek', 'total', null),
					'kolomCari' => array('id_proyek', 'total'),
					'orderBy' => array('id_proyek' => 'asc'),
					'kondisi' => "WHERE status = 'BERJALAN'",
				);

				$dataProyek = $this->HomeModel->getAllDataTable($config_dataTable);
	
				$data = array();
				foreach($dataProyek as $row){	
					$dataRow = array();
					$dataRow[] = $row['id_proyek'];
					$dataRow[] = $this->helper->cetakRupiah($row['total']);
					$data[] = $dataRow;
				}

				$output = array(
					'draw' => $_POST['draw'],
					'recordsTotal' => $this->HomeModel->recordTotal(),
					'recordsFiltered' => $this->HomeModel->recordFilter(),
					'data' => $data,
				);
				echo json_encode($output);
			} else { 
				$this->redirect();
			}		
	}

		/**
		 * Beranda Kas Kecil
		 */
		private function beranda_kasKecil(){

			$sumAccspkk = $this->HomeModel->getSumAccspkk();
			$countPendingspkk = $this->HomeModel->getPendingspkk();
			$sumPendingspkk = $this->HomeModel->getSumPendingspkk();
			$countPkk = $this->HomeModel->getPkk();

			// config css-js
			$css = array(
				'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
				'assets/bower_components/dropify/dist/css/dropify.min.css'

			);
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'assets/bower_components/dropify/dist/js/dropify.min.js',
				'app/views/beranda/js/initKaskecil.js'		
			);

			$config = array(
				'title' => array(
					'title' => 'Beranda',
				),
				'property' => array(
					'main' => 'Beranda',
					'sub' => 'Dashboard Kas Kecil',
				),
				'css' => $css,
				'js' => $js,
			);

			$data = array(
				'sum_acc_spkk' => $this->helper->cetakRupiah($sumAccspkk['dana_disetujui']),
				'pending_spkk' => $countPendingspkk['jml_transaksi_pending_spkk'],
				'sum_pending_spkk' => $this->helper->cetakRupiah($sumPendingspkk['dana_transaksi_pending']),
				'jml_transaksi_pkk' => $countPkk['jml_transaksi_pkk']
			);

			$this->layout('beranda/kas_kecil', $config, $data);
		}

		/**
		 * Table SKK Beranda Kas Kecil
		 */
		public function get_skk_list() {
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				// config datatable
				$config_dataTable = array(
					'tabel' => 'sub_kas_kecil',
					'kolomOrder' => array(null, 'id', 'nama', 'saldo', null),
					'kolomCari' => array('id', 'nama', 'saldo'),
					'orderBy' => array('id' => 'asc'),
					'kondisi' => 'WHERE saldo < 0',
				);

				$dataSkk = $this->HomeModel->getAllDataTable($config_dataTable);

				$data = array();
				foreach($dataSkk as $row){	
					$dataRow = array();
					$dataRow[] = $row['id'];
					$dataRow[] = $row['nama'];
					$dataRow[] = $this->helper->cetakRupiah($row['saldo']);
					$data[] = $dataRow;
				}

				$output = array(
					'draw' => $_POST['draw'],
					'recordsTotal' => $this->HomeModel->recordTotal(),
					'recordsFiltered' => $this->HomeModel->recordFilter(),
					'data' => $data,
				);
				echo json_encode($output);
			} else { 
				$this->redirect();
			}		
		}

		/**
		 * 
		 */
		public function get_lskk_list() {
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				// config datatable
				$config_dataTable = array(
					'tabel' => 'pengajuan_sub_kas_kecil',
					'kolomOrder' => array(null, 'id', 'saldo', null),
					'kolomCari' => array('id', 'saldo'),
					'orderBy' => array('id' => 'asc'),
					'kondisi' => 'WHERE status_laporan = 1',
				);

				$dataLpskk = $this->HomeModel->getAllDataTable($config_dataTable);

				$data = array();
				foreach($dataLpskk as $row){	
					$dataRow = array();
					$dataRow[] = $row['id'];
					$dataRow[] = $this->helper->cetakRupiah($row['total']);
					$data[] = $dataRow;
				}

				$output = array(
					'draw' => $_POST['draw'],
					'recordsTotal' => $this->HomeModel->recordTotal(),
					'recordsFiltered' => $this->HomeModel->recordFilter(),
					'data' => $data,
				);
				echo json_encode($output);
			} else { 
				$this->redirect();
			}		
		}
	}
