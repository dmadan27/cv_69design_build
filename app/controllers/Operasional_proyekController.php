<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	*
	*/
	class Operasional_proyek extends CrudAbstract{

		private $token;
		private $status = false;

		/**
		*
		*/
		public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
			$this->model('Operasional_ProyekModel');
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
			// set config untuk layouting
			$css = array(
				'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
			);
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'assets/plugins/input-mask/jquery.inputmask.bundle.js',
				'app/views/operasional_proyek/js/initList.js',

			);

			$config = array(
				'title' => array(
					'main' => 'Data Operasional Proyek',
					'sub' => 'List Semua Data Operasional Proyek',
				),
				'css' => $css,
				'js' => $js,
			);

			$this->layout('operasional_proyek/list', $config, $data = NULL);
		}

		/**
		*
		*/
		public function get_list(){
			// config datatable
			$config_dataTable = array(
				'tabel' => 'operasional_proyek',
				'kolomOrder' => array(null, 'id', 'id_proyek', 'tgl', 'nama', 'total', null),
				'kolomCari' => array('id','id_proyek', 'tgl', 'nama'),
				'orderBy' => array('id' => 'asc'),
				'kondisi' => false,
			);

			$dataOperasionalProyek = $this->Operasional_ProyekModel->getAllDataTable($config_dataTable);

			$data = array();
			$no_urut = $_POST['start'];
			foreach($dataOperasionalProyek as $row){
				$no_urut++;

				// $status = ($row['status'] == "AKTIF") ? '<span class="label label-success">'.$row['status'].'</span>' : '<span class="label label-danger">'.$row['status'].'</span>';

				//button aksi
				$aksiDetail = '<button onclick="getView('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-info btn-flat" title="Lihat Detail"><i class="fa fa-eye"></i></button>';
				$aksiEdit = '<button onclick="getEdit('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-success btn-flat" title="Edit Data"><i class="fa fa-pencil"></i></button>';
				$aksiHapus = '<button onclick="getDelete('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-danger btn-flat" title="Hapus Data"><i class="fa fa-trash"></i></button>';
				
				$aksi = '<div class="btn-group">'.$aksiDetail.$aksiEdit.$aksiHapus.'</div>';
				
				$dataRow = array();
				$dataRow[] = $no_urut;
				$dataRow[] = $row['id'];
				$dataRow[] = $row['id_proyek'];
				$dataRow[] = $this->helper->cetakTgl($row['tgl'], 'full');
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
			$css = array(
  				'assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
				'assets/bower_components/select2/dist/css/select2.min.css',
				 'assets/plugins/iCheck/all.css',

  			);
			$js = array(
				'assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
				'assets/bower_components/select2/dist/js/select2.full.min.js',
				'assets/plugins/iCheck/icheck.min.js',
				'assets/plugins/input-mask/jquery.inputmask.bundle.js',
				'app/views/operasional_proyek/js/initForm.js',	
			);

			$config = array(
				'title' => array(
					'main' => 'Data Operasional Proyek',
					'sub' => 'Form Tambah Data',
				),
				'css' => $css,
				'js' => $js,
			);

			$data = array(
				'action' => 'action-add',
				'id' => '',
				'id_proyek' => '',
				'tgl' => '',
				'nama' => '',
				'total' => '',
			);

			$this->layout('operasional_proyek/form', $config, $data);
		}

		/**
		*
		*/
		public function action_add(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$data = isset($_POST) ? $_POST : false;
				$dataOperasionalProyek = isset($_POST['dataOperasionalProyek']) ? json_decode($_POST['dataOperasionalProyek'], true) : false;
				$dataDetail = isset($_POST['dataDetail']) ? json_decode($_POST['dataDetail'], true) : false;
				// $dataSkk = isset($_POST['dataSkk']) ? json_decode($_POST['dataSkk'], true) : false;
				
				$error = $notif = array();
				$cekDetail = true;

				if(!$data){
					$notif = array(
						'type' => "error",
						'title' => "Pesan Gagal",
						'message' => "Terjadi kesalahan teknis, silahkan coba kembali",
					);
				}
				else{
					// validasi data
					$validasi = $this->set_validation($dataOperasionalProyek, $data['action']);
					$cek = $validasi['cek'];
					$error = $validasi['error'];

					if(empty($dataDetail)){
						$cek = false;
						$cekDetail = false;
					}
					
					if($cek){
						$ket = 'OPERASIONAL PROYEK ['.$dataOperasionalProyek['id'].'] - '.strtoupper($dataOperasionalProyek['nama']);

						// validasi input
						$dataOperasionalProyek = array(
							'id' => $this->validation->validInput($dataOperasionalProyek['id']),
							'id_proyek' => $this->validation->validInput($dataOperasionalProyek['id_proyek']),
							'id_bank' => $this->validation->validInput($dataOperasionalProyek['id_bank']),
							'id_kas_besar' => $_SESSION['sess_id'],
							'tgl' => $this->validation->validInput($dataOperasionalProyek['tgl']),
							'nama' => $this->validation->validInput($dataOperasionalProyek['nama']),
							'total' => $this->validation->validInput($dataOperasionalProyek['total']),
							'ket' => $ket,
						);

						$dataInsert = array(
							'dataOperasionalProyek' => $dataOperasionalProyek,
							'dataDetail' => $dataDetail
						);

						// insert data proyek
						if($this->Operasional_ProyekModel->insert($dataInsert)){
							$this->status = true;
							$_SESSION['notif'] = array(
								'type' => "success",
								'title' => "Pesan Berhasil",
								'message' => "Tambah Data Operasional Proyek Baru Berhasil",
							);
							$notif['default'] = $_SESSION['notif'];
						}
						else{
							$notif['default'] = array(
								'type' => "error",
								'title' => "Pesan Gagal",
								'message' => "Terjadi kesalahan teknis, silahkan coba kembali",
							);
						}
					}
					else{
						if(!$cekDetail){
							$notif['data_detail'] = array(
								'type' => 'warning',
								'title' => "Pesan Pemberitahuan",
								'message' => "Silahkan Cek Kembali Data Detail",
							);
						}

						$notif['default'] = array(
							'type' => 'warning',
							'title' => "Pesan Pemberitahuan",
							'message' => "Silahkan Cek Kembali Form Isian",
						);
					}
				}

				$output = array(
					'status' => $this->status,
					'notif' => $notif,
					'error' => $error,
					'cek' => array(
						'cek' => $cek,
						'data_detail' => $cekDetail,
						
					),
					'data' => $data,
					'dataOperasionalProyek' => $dataOperasionalProyek,
					'dataDetail' => $dataDetail,
				
				);
				echo json_encode($output);
			}
			else $this->redirect();
		}

		/**
		*
		*/
		protected function edit($id){
			if(empty($id) || $id == "") $this->redirect(BASE_URL."operasional-proyek/");

			
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
		*	Export data ke format Excel
		*/
		public function export(){
			include ('app/library/export_phpexcel/koneksi.php');
			
			// Load plugin PHPExcel nya
			require_once 'app/library/export_phpexcel/PHPExcel/PHPExcel.php';

			$excel = new PHPExcel();

			// Settingan awal fil excel
			$excel->getProperties()->setCreator('Jaka Pratama, Romadan Saputra, Fajar Cahyo')
								   ->setLastModifiedBy('PC Personal')
								   ->setTitle("Data Operasional Proyek")
								   ->setSubject("Operasional Proyek")
								   ->setDescription("Laporan Semua Data Operasional Proyek")
								   ->setKeywords("Data Operasional Proyek");

			// Buat sebuah variabel untuk menampung pengaturan style dari header tabel
			$style_col = array(
				'font' => array('bold' => true), // Set font nya jadi bold
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
				),
				'borders' => array(
					'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
					'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
					'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
					'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
				)
			);

			// Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
			$style_row = array(
				'alignment' => array(
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
				),
				'borders' => array(
					'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
					'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
					'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
					'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
				)
			);

			$excel->setActiveSheetIndex(0)->setCellValue('A1', "DATA OPERASIONAL PROYEK"); // Set kolom A1 dengan tulisan "DATA SISWA"
			$excel->getActiveSheet()->mergeCells('A1:H1'); // Set Merge Cell pada kolom A1 sampai F1
			$excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
			$excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
			$excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

			// Buat header tabel nya pada baris ke 3
			$excel->setActiveSheetIndex(0)->setCellValue('A3', "NO"); // Set kolom A3 dengan tulisan "NO"
			$excel->setActiveSheetIndex(0)->setCellValue('B3', "ID"); // Set kolom B3 dengan tulisan "ID"
			$excel->setActiveSheetIndex(0)->setCellValue('C3', "ID PROYEK"); // Set kolom C3 dengan tulisan "ID PROYEK"
			$excel->setActiveSheetIndex(0)->setCellValue('D3', "ID BANK"); // Set kolom D3 dengan tulisan "ID BANK"
			$excel->setActiveSheetIndex(0)->setCellValue('E3', "ID KAS BESAR"); // Set kolom E3 dengan tulisan "ID KAS BESAR"
			$excel->setActiveSheetIndex(0)->setCellValue('F3', "TANGGAL"); // Set kolom F3 dengan tulisan "TANGGAL"
			$excel->setActiveSheetIndex(0)->setCellValue('G3', "NAMA"); // Set kolom G3 dengan tulisan "NAMA"
			$excel->setActiveSheetIndex(0)->setCellValue('H3', "TOTAL"); // Set kolom H3 dengan tulisan "TOTAL"



			// Apply style header yang telah kita buat tadi ke masing-masing kolom header
			$excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('E3')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('F3')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('G3')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('H3')->applyFromArray($style_col);


			// Set height baris ke 1, 2 dan 3
			$excel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
			$excel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);
			$excel->getActiveSheet()->getRowDimension('3')->setRowHeight(20);

			// Buat query untuk menampilkan semua data siswa
			$sql = $pdo->prepare("SELECT * FROM operasional_proyek");
			$sql->execute(); // Eksekusi querynya

			$no = 1; // Untuk penomoran tabel, di awal set dengan 1
			$numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
			while($data = $sql->fetch()){ // Ambil semua data dari hasil eksekusi $sql
				$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $no);
				$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $data['id']);
				$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $data['id_proyek']);
				$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $data['id_bank']);
				$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $data['id_kas_besar']);
				$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $data['tgl']);
				$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $data['nama']);
				$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $data['total']);
				
				
				// Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
				$excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);
				$excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
				$excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
				$excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
				$excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);
				$excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);
				$excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);
				$excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_row);
				

				
				$excel->getActiveSheet()->getRowDimension($numrow)->setRowHeight(20);
				
				$no++; // Tambah 1 setiap kali looping
				$numrow++; // Tambah 1 setiap kali looping
			}

			// Set width kolom
			$excel->getActiveSheet()->getColumnDimension('A')->setWidth(5); // Set width kolom A
			$excel->getActiveSheet()->getColumnDimension('B')->setWidth(15); // Set width kolom B
			$excel->getActiveSheet()->getColumnDimension('C')->setWidth(25); // Set width kolom C
			$excel->getActiveSheet()->getColumnDimension('D')->setWidth(20); // Set width kolom D
			$excel->getActiveSheet()->getColumnDimension('E')->setWidth(15); // Set width kolom E
			$excel->getActiveSheet()->getColumnDimension('F')->setWidth(15); // Set width kolom F
			$excel->getActiveSheet()->getColumnDimension('G')->setWidth(15); // Set width kolom G
			$excel->getActiveSheet()->getColumnDimension('H')->setWidth(15); // Set width kolom H



			// Set orientasi kertas jadi LANDSCAPE
			$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

			// Set judul file excel nya
			$excel->getActiveSheet(0)->setTitle("Laporan Data Operasional Proyel");
			$excel->setActiveSheetIndex(0);

			// Proses file excel
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment; filename="Data Operasional Proyek.xlsx"'); // Set nama file excel nya
			header('Cache-Control: max-age=0');

			$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
			$write->save('php://output');
			
		}

		/**
		*
		*/
		public function get_last_id(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$proyek = isset($_POST['get_proyek']) ? $this->validation->validInput($_POST['get_proyek']) : false;

				$id_temp = ($proyek) ? 'OPRY-'.$proyek.'-' : 'OPRY-[ID_PROYEK]-';

				$data = !empty($this->Operasional_ProyekModel->getLastID($id_temp)['id']) ? $this->Operasional_ProyekModel->getLastID($id_temp)['id'] : false;

				if(!$data) $id = $id_temp.'0001';
				else{
					$noUrut = (int)substr($data, 17, 4);
					$noUrut++;

					$id = $id_temp.sprintf("%04s", $noUrut);
				}

				// if(!$data) $id = 'PRY0001';
				// else{
				// 	// $data = implode('', $data);
				// 	$kode = 'PRY';
				// 	$noUrut = (int)substr($data, 3, 4);
				// 	$noUrut++;

				// 	$id = $kode.sprintf("%04s", $noUrut);
				// }

				echo json_encode($id);
			}		

		}

		/**
		*
		*/
		public function get_nama_proyek(){
			$this->model('ProyekModel');
			$data_nama_proyek = $this->ProyekModel->getAll();
			$data = array();

			foreach($data_nama_proyek as $row){
				$dataRow = array();
				$dataRow['id'] = $row['id'];
				$dataRow['text'] = $row['id'].' - '.$row['pembangunan'];

				$data[] = $dataRow;
			}

			echo json_encode($data);
		}

		/**
		*
		*/
		public function get_nama_bank(){
			$this->model('BankModel');
			$data_nama_bank = $this->BankModel->getAll();
			$data = array();

			foreach($data_nama_bank as $row){
				$dataRow = array();
				$dataRow['id'] = $row['id'];
				$dataRow['text'] = $row['nama']. ' - '.$row['saldo'];

				$data[] = $dataRow;
			}

			echo json_encode($data);
		}

		/**
		*
		*/
		public function action_add_detail(){
			$data = isset($_POST) ? $_POST : false;
			
			$status = false;
			$error = "";

			$validasi = $this->set_validation_detail($data);
			$cek = $validasi['cek'];
			$error = $validasi['error'];

			if($cek) $status = true;

			$output = array(
				'status' => $status,
				// 'notif' => $notif,
				'error' => $error,
				'data' => $data,
			);
			echo json_encode($output);

		}

		/**
		* Function validasi form utama
		*/
		private function set_validation($data, $action){
			
			// id
			$this->validation->set_rules($data['id'], 'ID Operasional Proyek', 'id', 'string | 1 | 255 | required');
			// id_proyek
			$this->validation->set_rules($data['id_proyek'], 'ID proyek', 'id_proyek', 'string | 1 | 255 | required');
			// id_bank
			$this->validation->set_rules($data['id_bank'], 'ID Bank', 'id_bank', 'string | 1 | 255 | required');
			// tgl
			$this->validation->set_rules($data['tgl'], 'Tanggal Operasional Proyek', 'tgl', 'string | 1 | 255 | required');
			// nama
			$this->validation->set_rules($data['nama'], 'Nama Pengajuan', 'nama', 'string | 1 | 255 | required');
			// total
			$this->validation->set_rules($data['total'], 'Total Pengajuan', 'total', 'nilai | 1 | 99999 | required');
			
			return $this->validation->run();
		}

		/**
		* Function validasi form detail
		*/
		private function set_validation_detail($data){
			// nama
			$this->validation->set_rules($data['nama_detail'], 'Nama Kebutuhan', 'nama_detail', 'string | 1 | 255 | required');
			// jenis
			$this->validation->set_rules($data['jenis_detail'], 'Jenis Kebutuhan', 'jenis_detail', 'string | 1 | 255 | required');
			// satuan
			$this->validation->set_rules($data['satuan_detail'], 'Satuan', 'satuan_detail', 'string | 1 | 255 | required');
			// kuantiti
			$this->validation->set_rules($data['qty_detail'], 'Kuantiti', 'qty_detail', 'angka | 1 | 255 | required');
			// harga
			$this->validation->set_rules($data['harga_detail'], 'Harga Kebutuhan', 'harga_detail', 'nilai | 1 | 9999999999 | required');
			// sub_total
			$this->validation->set_rules($data['sub_total_detail'], 'Sub Total', 'sub_total_detail', 'nilai | 1 | 9999999999 | required');
			// status
			$this->validation->set_rules($data['status_detail'], 'Status', 'status_detail', 'string | 1 | 255 | required');
			// harga asli
			$this->validation->set_rules($data['harga_asli_detail'], 'Harga Asli', 'harga_asli_detail', 'nilai | 1 | 9999999999 | required');
			// sisa
			$this->validation->set_rules($data['sisa_detail'], 'Sisa', 'sisa_detail', 'nilai | 1 | 9999999999 | required');
			// status lunas
			$this->validation->set_rules($data['status_lunas_detail'], 'Status Lunas', 'status_lunas_detail', 'string | 1 | 255 | required');

			return $this->validation->run();
		}

	}
