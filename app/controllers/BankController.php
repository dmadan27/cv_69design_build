<?php 
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	* Class Bank extend ke Abstract Crud Modals
	*/
	class Bank extends Crud_modalsAbstract{

		private $token;
		private $status = false;

		/**
		* Default load saat pertama kali controller diakses
		*/
		public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
			$this->model('BankModel');
			$this->helper();
			$this->validation();
		}	

		/**
		* Method pertama kali yang diakses
		*/
		public function index(){
			$this->list();
		}

		/**
		* Method List
		* Menampilkan list semua data proyek
		* Passing data css dan js yang dibutuhkan di list proyek
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
				'app/views/bank/js/initList.js',
				'app/views/bank/js/initForm.js',
			);

			$config = array(
				'title' => array(
					'main' => 'Data Bank',
					'sub' => 'List Semua Data Bank',
				),
				'css' => $css,
				'js' => $js,
			);

			$this->layout('bank/list', $config, $data = null);
		}	

		/**
		* Method get list
		* Get data semua list bank yang akan di passing ke dataTable
		* Request berupa POST dan output berupa JSON
		*/
		public function get_list(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
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

					$status = ($row['status'] == "AKTIF") ? '<span class="label label-success">'.$row['status'].'</span>' : '<span class="label label-danger">'.$row['status'].'</span>';

					// button aksi
					$aksiDetail = '<button onclick="getView('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-info btn-flat" title="Lihat Detail"><i class="fa fa-eye"></i></button>';
					$aksiEdit = '<button onclick="getEdit('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-success btn-flat" title="Edit Data"><i class="fa fa-pencil"></i></button>';
					$aksiHapus = '<button onclick="getDelete('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-danger btn-flat" title="Hapus Data"><i class="fa fa-trash"></i></button>';
					
					$aksi = '<div class="btn-group">'.$aksiDetail.$aksiEdit.$aksiHapus.'</div>';
					
					$dataRow = array();
					$dataRow[] = $no_urut;
					$dataRow[] = $row['nama'];
					$dataRow[] = $this->helper->cetakRupiah($row['saldo']);
					$dataRow[] = $status;
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
			else $this->redirect();				
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
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$data = isset($_POST) ? $_POST : false;
						
				$error = $notif = array();

				if(!$data){
					$notif = array(
						'type' => 'error',
						'title' => "Pesan Gagal",
						'message' => "Terjadi Kesalahan Teknis, Silahkan Coba Kembali",
					);
				}
				else{
					// validasi data
					$validasi = $this->set_validation($data);
					$cek = $validasi['cek'];
					$error = $validasi['error'];

					if($cek){
						// validasi inputan
						$data = array(
							'nama' => $this->validation->validInput($data['nama']),
							'saldo' => $this->validation->validInput($data['saldo']),
							'status' => $this->validation->validInput($data['status']),
						);

						// insert bank
						if($this->BankModel->insert($data)) {
							$this->status = true;
							$notif = array(
								'type' => 'success',
								'title' => "Pesan Berhasil",
								'message' => "Tambah Data Bank Baru Berhasil",
							);
						}
						else {
							$notif = array(
								'type' => 'error',
								'title' => "Pesan Gagal",
								'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
							);
						}
					}
					else {
						$notif = array(
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
					// 'data' => $data
				);

				echo json_encode($output);	
			}
			else $this->redirect();
		}

		/**
		* Function edit
		* method untuk get data edit
		* param $id didapat dari url
		* return berupa json
		*/
		public function edit($id){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$id = strtoupper($id);
				$data = !empty($this->BankModel->getById($id)) ? $this->BankModel->getById($id) : false;

				echo json_encode($data);
			}
			else $this->redirect();	
			
		}

		/**
		* Function action_edit
		* method untuk aksi edit data
		* return berupa json
		* status => status berhasil atau gagal proses edit
		* notif => pesan yang akan ditampilkan disistem
		* error => error apa saja yang ada dari hasil validasi
		*/
		public function action_edit(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$data = isset($_POST) ? $_POST : false;
				
				$error = $notif = array();

				if(!$data){
					$notif = array(
						'type' => 'error',
						'title' => "Pesan Gagal",
						'message' => "Terjadi Kesalahan Teknis, Silahkan Coba Kembali",
					);
				}
				else{
					// validasi data
					$validasi = $this->set_validation($data);
					$cek = $validasi['cek'];
					$error = $validasi['error'];

					if($cek){
						// validasi inputan
						$data = array(
							'id' => $this->validation->validInput($data['id']),
							'nama' => $this->validation->validInput($data['nama']),
							'status' => $this->validation->validInput($data['status'])
						);

						// update bank
						if($this->BankModel->update($data)) {
							$status = true;
							$notif = array(
								'type' => 'success',
								'title' => "Pesan Berhasil",
								'message' => "Edit Data Bank Berhasil",
							);
						}
						else {
							$notif = array(
								'type' => 'error',
								'title' => "Pesan Gagal",
								'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
							);
						}
					}
					else {
						$notif = array(
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
					// 'data' => $data
				);

				echo json_encode($output);
			}
			else $this->redirect();
		}

		/**
		* Function detail
		* method untuk get data detail dan setting layouting detail
		* param $id didapat dari url
		*/
		public function detail($id){
			$id = strtoupper($id);
			$data_detail = !empty($this->BankModel->getById($id)) ? $this->BankModel->getById($id) : false;

			if((empty($id) || $id == "") || !$data_detail) $this->redirect(BASE_URL."bank/");

			$css = array(
				'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
			);
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'assets/plugins/input-mask/jquery.inputmask.bundle.js',
				'app/views/bank/js/initView.js',
				'app/views/bank/js/initForm.js',
			);

			$config = array(
				'title' => array(
					'main' => 'Data Bank',
					'sub' => 'Detail Data Bank',
				),
				'css' => $css,
				'js' => $js,
			);

			$status = ($data_detail['status'] == "AKTIF") ? 
				'<span class="label label-success">'.$data_detail['status'].'</span>' : 
				'<span class="label label-danger">'.$data_detail['status'].'</span>';

			$data = array(
				'id_bank' => $data_detail['id'],
				'nama' => $data_detail['nama'],
				'saldo' => $this->helper->cetakRupiah($data_detail['saldo']),
				'status' => $status,
			);

			$this->layout('bank/view', $config, $data);
		}

		/**
		* Function delete
		* method yang berfungsi untuk menghapus data
		* param $id didapat dari url
		* return json
		*/
		public function delete($id){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$id = strtoupper($id);
				if(empty($id) || $id == "") $this->redirect(BASE_URL."bank/");

				if($this->BankModel->delete($id)) $this->status = true;

				echo json_encode($this->status);
			}
			else $this->redirect();	
		}

		/**
		* Function get_mutasi
		* method yang berfungsi untuk get data mutasi bank sesuai dengan id
		* dipakai di detail data
		*/
		public function get_mutasi($id){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$this->model('Mutasi_bankModel');
				
				// config datatable
				$config_dataTable = array(
					'tabel' => 'mutasi_bank',
					'kolomOrder' => array(null, 'tgl', 'uang_masuk', 'uang_keluar', 'saldo', 'ket'),
					'kolomCari' => array('tgl', 'uang_masuk', 'uang_keluar', 'saldo', 'ket'),
					'orderBy' => array('id' => 'desc'),
					'kondisi' => 'WHERE id_bank = '.$id.' ',
				);

				$dataMutasi = $this->Mutasi_bankModel->getAllDataTable($config_dataTable);

				$data = array();
				$no_urut = $_POST['start'];
				foreach($dataMutasi as $row){
					$no_urut++;
					
					$dataRow = array();
					$dataRow[] = $no_urut;
					$dataRow[] = $row['tgl'];
					$dataRow[] = $this->helper->cetakRupiah($row['uang_masuk']);
					$dataRow[] = $this->helper->cetakRupiah($row['uang_keluar']);
					$dataRow[] = $this->helper->cetakRupiah($row['saldo']);
					$dataRow[] = $row['ket'];

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

		/**
		*
		*/
		public function export(){
			include ('app/library/export_phpexcel/koneksi.php');
			
			// Load plugin PHPExcel nya
			require_once 'app/library/export_phpexcel/PHPExcel/PHPExcel.php';

			$excel = new PHPExcel();

			// Settingan awal fil excel
			$excel->getProperties()->setCreator('Jaka Pratama')
								   ->setLastModifiedBy('PC Personal')
								   ->setTitle("Data Bank")
								   ->setSubject("Bank")
								   ->setDescription("Laporan Semua Data Bank")
								   ->setKeywords("Data Bank");

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

			$excel->setActiveSheetIndex(0)->setCellValue('A1', "DATA BANK"); // Set kolom A1 dengan tulisan "DATA SISWA"
			$excel->getActiveSheet()->mergeCells('A1:F1'); // Set Merge Cell pada kolom A1 sampai F1
			$excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
			$excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
			$excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

			// Buat header tabel nya pada baris ke 3
			$excel->setActiveSheetIndex(0)->setCellValue('A3', "NO"); // Set kolom A3 dengan tulisan "NO"
			$excel->setActiveSheetIndex(0)->setCellValue('B3', "ID"); // Set kolom B3 dengan tulisan "NIS"
			$excel->setActiveSheetIndex(0)->setCellValue('C3', "NAMA"); // Set kolom C3 dengan tulisan "NAMA"
			$excel->setActiveSheetIndex(0)->setCellValue('D3', "SALDO"); // Set kolom D3 dengan tulisan "SALDO"
			$excel->setActiveSheetIndex(0)->setCellValue('E3', "STATUS"); // Set kolom E3 dengan tulisan "TELEPON"


			// Apply style header yang telah kita buat tadi ke masing-masing kolom header
			$excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('E3')->applyFromArray($style_col);


			// Set height baris ke 1, 2 dan 3
			$excel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
			$excel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);
			$excel->getActiveSheet()->getRowDimension('3')->setRowHeight(20);

			// Buat query untuk menampilkan semua data siswa
			$sql = $pdo->prepare("SELECT * FROM bank");
			$sql->execute(); // Eksekusi querynya

			$no = 1; // Untuk penomoran tabel, di awal set dengan 1
			$numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
			while($data = $sql->fetch()){ // Ambil semua data dari hasil eksekusi $sql
				$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $no);
				$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $data['id']);
				$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $data['nama']);
				$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $data['saldo']);
				$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $data['status']);
					
				
				
				// Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
				$excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);
				$excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
				$excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
				$excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
				$excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);

				
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


			// Set orientasi kertas jadi LANDSCAPE
			$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

			// Set judul file excel nya
			$excel->getActiveSheet(0)->setTitle("Laporan Data Bank");
			$excel->setActiveSheetIndex(0);

			// Proses file excel
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment; filename="Data Bank.xlsx"'); // Set nama file excel nya
			header('Cache-Control: max-age=0');

			$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
			$write->save('php://output');


			
			
		}

		/**
		* Fungsi set_validation
		* method yang berfungsi untuk validasi inputan secara server side
		* param $data didapat dari post yang dilakukan oleh user
		* return berupa array, status hasil pengecekan dan error tiap validasi inputan
		*/
		private function set_validation($data){
			$required = ($data['action'] == "action-edit") ? 'not_required' : 'required';

			// nama bank
			$this->validation->set_rules($data['nama'], 'Nama Bank', 'nama', 'string | 1 | 255 | required');
			// saldo awal
			$this->validation->set_rules($data['saldo'], 'Saldo Awal Bank', 'saldo', 'nilai | 0 | 99999999999 | '.$required);
			// status
			$this->validation->set_rules($data['status'], 'Status Bank', 'status', 'string | 1 | 255 | required');

			return $this->validation->run();
		}

	}