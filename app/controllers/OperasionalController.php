<?php 
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	*
	*/
	class Operasional extends Crud_modalsAbstract{

		private $token;
		private $status = false;

		/**
		* load auth, cekAuth
		* load default model, BankModel
		* load helper dan validation
		*/
		public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
			$this->model('OperasionalModel');
			$this->helper();
			$this->validation();
		}	

		/**
		* Function index
		* menjalankan method list
		*/
		public function index(){
			$this->list();
		}

		/**
		* Function list
		* setting layouting list utama
		* generate token list dan add
		*/
		protected function list(){
			// set config untuk layouting
			$css = array(
				'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
				'assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
				'assets/bower_components/select2/dist/css/select2.min.css',

			);
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
				'assets/bower_components/select2/dist/js/select2.full.min.js',
				'assets/plugins/input-mask/jquery.inputmask.bundle.js',
				'app/views/operasional/js/initList.js',
				'app/views/operasional/js/initForm.js',
			);

			$config = array(
				'title' => array(
					'main' => 'Data Operasional',
					'sub' => 'List Semua Data Operasional',
				),
				'css' => $css,
				'js' => $js,
			);
			
			$this->layout('operasional/list', $config, $data = NULL);
		}	

		/**
		* Function get_list
		* method khusus untuk datatable
		* generate token edit dan delete
		* return json
		*/
		public function get_list(){
			// config datatable
			$config_dataTable = array(
				'tabel' => 'v_operasional',
				'kolomOrder' => array(null, 'id', 'nama_bank', 'tgl', 'nama', 'nominal', null),
				'kolomCari' => array('nama', 'tgl', 'nama', 'nama_bank', 'nominal', 'ket'),
				'orderBy' => array('tgl' => 'desc'),
				'kondisi' => false,
			);

			$dataOperasional = $this->OperasionalModel->getAllDataTable($config_dataTable);

			$data = array();
			$no_urut = $_POST['start'];
			foreach($dataOperasional as $row){
				$no_urut++;

				// button aksi
				$aksiDetail = '<button onclick="getView('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-info btn-flat" title="Lihat Detail"><i class="fa fa-eye"></i></button>';
				$aksiEdit = '<button onclick="getEdit('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-success btn-flat" title="Edit Data"><i class="fa fa-pencil"></i></button>';
				$aksiHapus = '<button onclick="getDelete('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-danger btn-flat" title="Hapus Data"><i class="fa fa-trash"></i></button>';
				
				$aksi = '<div class="btn-group">'.$aksiDetail.$aksiEdit.$aksiHapus.'</div>';
				
				$dataRow = array();
				$dataRow[] = $no_urut;
				// $dataRow[] = $row['id'];
				$dataRow[] = $row['nama_bank'];
				$dataRow[] = $row['tgl'];
				$dataRow[] = $row['nama'];
				$dataRow[] = $row['nominal'];
				$dataRow[] = $aksi;
				
				// $dataRow[] = $row['ket'];
				$data[] = $dataRow;
			}

			$output = array(
				'draw' => $_POST['draw'],
				'recordsTotal' => $this->OperasionalModel->recordTotal(),
				'recordsFiltered' => $this->OperasionalModel->recordFilter(),
				'data' => $data,
			);

			echo json_encode($output);		
		}

		/**
		* Function action_add
		* method untuk aksi tambah data
		* return berupa json
		* status => status berhasil atau gagal proses tambah
		* notif => pesan yang akan ditampilkan disistem
		* error => error apa saja yang ada dari hasil validasi
		*/
		public function action_add(){
			$data = isset($_POST) ? $_POST : false;
			
			$error = $notif = array();

			if(!$data){
				$notif = array(
					'type' => "error",
					'title' => "Pesan Gagal",
					'message' => "Terjadi Kesalahan Teknis, Silahkan Coba Kembali",
				);
			}
			else{
				// validasi data
				$validasi = $this->set_validation($data);
				$cek = $validasi['cek'];
				$error = $validasi['error'];

				$this->model('BankModel');
				$getSaldo = $this->BankModel->getById($data['id_bank'])['saldo'];

				if($data['nominal'] > $getSaldo){
					$cek = false;
					$error['nominal'] = "Nominal terlalu besar dan melebihi saldo bank";
				}

				if($cek){
					// validasi inputan
					$data = array(
						'id_bank' => $this->validation->validInput($data['id_bank']),
						'id_kas_besar' => $_SESSION['sess_id'],
						'tgl' => $this->validation->validInput($data['tgl']),
						'nama' => $this->validation->validInput($data['nama']),
						'nominal' => $this->validation->validInput($data['nominal']),
						'ket' => $this->validation->validInput($data['ket'])
					);

					// insert
					if($this->OperasionalModel->insert($data)) {
						$this->status = true;
						$notif = array(
							'type' => "success",
							'title' => "Pesan Berhasil",
							'message' => "Tambah Data Operasional Baru Berhasil",
						);
					}
					else {
						$notif = array(
							'type' => "error",
							'title' => "Pesan Gagal",
							'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
						);
					}
				}
				else {
					$notif = array(
						'type' => "warning",
						'title' => "Pesan Pemberitahuan",
						'message' => "Silahkan Cek Kembali Form Isian",
					);
				}
			}

			$output = array(
				'status' => $status,
				'notif' => $notif,
				'error' => $error,
				'data' => $data
			);

			echo json_encode($output);		
		}

		/**
		* Function edit
		* method untuk get data edit
		* param $id didapat dari url
		* return berupa json
		*/
		public function edit($id){
			$id = strtoupper($id);
			$data = !empty($this->OperasionalModel->getById($id)) ? $this->OperasionalModel->getById($id) : false;
			
			echo json_encode($data);
		}

		// /**
		// * Function action_edit
		// * method untuk aksi edit data
		// * return berupa json
		// * status => status berhasil atau gagal proses edit
		// * notif => pesan yang akan ditampilkan disistem
		// * error => error apa saja yang ada dari hasil validasi
		// */
		public function action_edit(){
			$data = isset($_POST) ? $_POST : false;

			$error = $notif = array();

			if(!$data){
				$notif = array(
					'type' => "error",
					'title' => "Pesan Gagal",
					'message' => "Terjadi Kesalahan Teknis, Silahkan Coba Kembali",
				);
			}
			else{
				// validasi data
				$validasi = $this->set_validation($data);
				$cek = $validasi['cek'];
				$error = $validasi['error'];

				// $getDataBank = $this->BankModel->getById($id);
				// $this->model('BankModel');

				if($cek){
					// validasi inputan
					$data = array(
						'id' =>  $this->validation->validInput($data['id']),
						'id_bank' =>  $this->validation->validInput($data['id_bank']),
						'nama' =>  $this->validation->validInput($data['nama']),
						'nominal' =>  $this->validation->validInput($data['nominal']),
						'ket' =>  $this->validation->validInput($data['ket']),
						

					);

					// update db

					// transact

					if($this->OperasionalModel->update($data)) {
						$this->status = true;
						$notif = array(
							'type' => "success",
							'title' => "Pesan Berhasil",
							'message' => "Edit Data Operasional Berhasil",
						);
					}
					else {
						$notif = array(
							'type' => "error",
							'title' => "Pesan Gagal",
							'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
						);
					}

					// commit
				}
				else {
					$notif = array(
						'type' => "warning",
						'title' => "Pesan Pemberitahuan",
						'message' => "Silahkan Cek Kembali Form Isian",
					);
				}
			
			}

			$output = array(
				'status' => $status,
				'notif' => $notif,
				'error' => $error,
				'data' => $data
			);

			echo json_encode($output);
		}

		/**
		* Function detail
		* method untuk get data detail dan setting layouting detail
		* param $id didapat dari url
		*/
		public function detail($id){
			$id = strtoupper($id);
			if(empty($id) || $id == "") $this->redirect(BASE_URL."operasional/");

			$data_detail = !empty($this->OperasionalModel->getById($id)) ? $this->OperasionalModel->getById($id) : false;

			if(!$data_detail) $this->redirect(BASE_URL."operasional/");

			$css = array(
				'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
			);
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'app/views/operasional/js/initView.js',
				// 'app/views/operasional/js/initForm.js',
			);

			$config = array(
				'title' => array(
					'main' => 'Data Operasional',
					'sub' => 'Detail Data Operasional',
				),
				'css' => $css,
				'js' => $js,
			);

			$data = array(
				'id' => $data_detail['id'],
				'id_bank' => $data_detail['id_bank'],
				'id_kas_besar' => $data_detail['id_kas_besar'],
				'tgl' => $data_detail['tgl'],
				'nama' => $data_detail['nama'],
				'nominal' => $this->helper->cetakRupiah($data_detail['nominal']),
				'ket' => $data_detail['ket'],
			);

			$this->layout('operasional/view', $config, $data);
		}

		/**
		* Function delete
		* method yang berfungsi untuk menghapus data
		* param $id didapat dari url
		* return json
		*/
		public function delete($id){
			$id = strtoupper($id);
			
			$getNamaOperasional = $this->OperasionalModel->getById($id)['nama'];
			$ket = 'Data Operasional Bank '.$getNamaOperasional. 'telah Dihapus';

			$data = array(
				'id' => $id,
				'tgl' => date('Y-m-d'),
				'ket' => $ket,	
			);

			if($this->OperasionalModel->delete($data)) $this->status = true;

			echo json_encode($this->status);
		}

		/**
		* Function get_mutasi
		* method yang berfungsi untuk get data mutasi bank sesuai dengan id
		* dipakai di detail data
		*/
		public function get_mutasi(){
		
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
								   ->setTitle("Data Operasional")
								   ->setSubject("Operasional")
								   ->setDescription("Laporan Semua Data Operasional")
								   ->setKeywords("Data Operasional");

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

			$excel->setActiveSheetIndex(0)->setCellValue('A1', "DATA OPERASIONAL"); // Set kolom A1 dengan tulisan "DATA OPERASIONAL"
			$excel->getActiveSheet()->mergeCells('A1:H1'); // Set Merge Cell pada kolom A1 sampai G1
			$excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
			$excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
			$excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

			// Buat header tabel nya pada baris ke 3
			$excel->setActiveSheetIndex(0)->setCellValue('A3', "NO"); // Set kolom A3 dengan tulisan "NO"
			$excel->setActiveSheetIndex(0)->setCellValue('B3', "ID"); // Set kolom B3 dengan tulisan "ID"
			$excel->setActiveSheetIndex(0)->setCellValue('C3', "TANGGAL"); // Set kolom C3 dengan tulisan "TANGGAL"
			$excel->setActiveSheetIndex(0)->setCellValue('D3', "NAMA"); // Set kolom D3 dengan tulisan "NAMA"
			$excel->setActiveSheetIndex(0)->setCellValue('E3', "NOMINAL"); // Set kolom E3 dengan tulisan "NOMINAL"
			$excel->setActiveSheetIndex(0)->setCellValue('F3', "KETERANGAN"); // Set kolom F3 dengan tulisan "KETERANGAN"
			$excel->setActiveSheetIndex(0)->setCellValue('G3', "ID_BANK"); // Set kolom G3 dengan tulisan "NAMA BANK"
			$excel->setActiveSheetIndex(0)->setCellValue('H3', "NAMA BANK"); // Set kolom H3 dengan tulisan "NAMA BANK"
			



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
			$sql = $pdo->prepare("SELECT * FROM v_operasional");
			$sql->execute(); // Eksekusi querynya

			$no = 1; // Untuk penomoran tabel, di awal set dengan 1
			$numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
			while($data = $sql->fetch()){ // Ambil semua data dari hasil eksekusi $sql
				$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $no);
				$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $data['id']);
				$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $data['tgl']);
				$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $data['nama']);
				$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $data['nominal']);
				$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $data['ket']);
				$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $data['id_bank']);
				$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $data['nama_bank']);
				
					
				
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
			$excel->getActiveSheet()->getColumnDimension('G')->setWidth(30); // Set width kolom G
			$excel->getActiveSheet()->getColumnDimension('H')->setWidth(30); // Set width kolom H



			// Set orientasi kertas jadi LANDSCAPE
			$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

			// Set judul file excel nya
			$excel->getActiveSheet(0)->setTitle("Laporan Data Operasional");
			$excel->setActiveSheetIndex(0);

			// Proses file excel
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment; filename="Data Operasional.xlsx"'); // Set nama file excel nya
			header('Cache-Control: max-age=0');

			$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
			$write->save('php://output');
			
		}

		/**
		*
		*/
		public function get_bank(){
			$this->model('BankModel');

			$data_bank = $this->BankModel->getAll();
			$data = array();

			foreach($data_bank as $row){
				$dataRow = array();
				$dataRow['id'] = $row['id'];
				$dataRow['text'] = $row['nama'].' - '.$this->helper->cetakRupiah($row['saldo']);

				$data[] = $dataRow;
			}

			echo json_encode($data);
		}

		/**
		* Fungsi set_validation
		* method yang berfungsi untuk validasi inputan secara server side
		* param $data didapat dari post yang dilakukan oleh user
		* return berupa array, status hasil pengecekan dan error tiap validasi inputan
		*/
		private function set_validation($data){
		
			// id_bank
			$this->validation->set_rules($data['id_bank'], 'id bank', 'id_bank', 'string | 1 | 255 | required');
			// tgl
			$this->validation->set_rules($data['tgl'], 'Tanggal', 'tgl', 'string | 1 | 255 | required ');
			// nama 
			$this->validation->set_rules($data['nama'], 'Nama Kebutuhan', 'nama', 'string | 1 | 255 | required');
			// nominal 
			$this->validation->set_rules($data['nominal'], 'Nominal Uang', 'nominal', 'nilai | 0 | 99999999999 | required');
			// ket 
			$this->validation->set_rules($data['ket'], 'Keterangan', 'ket', 'string | 1 | 255 | required');

			return $this->validation->run();
		}
	}