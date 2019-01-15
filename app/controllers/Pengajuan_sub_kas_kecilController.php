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
			$css = array(
				'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
			);
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js',
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'assets/plugins/input-mask/jquery.inputmask.bundle.js',
				'app/views/pengajuan_sub_kas_kecil/js/initList.js',
				'app/views/pengajuan_sub_kas_kecil/js/initForm.js',
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
			$_SESSION['token_pengajuan_skc']['edit_status'] = md5($this->auth->getToken());
			$_SESSION['token_pengajuan_skc']['delete'] = md5($this->auth->getToken());

			$this->token = array(
				'view' => password_hash($_SESSION['token_pengajuan_skc']['view'], PASSWORD_BCRYPT),
				'edit_status' => password_hash($_SESSION['token_pengajuan_skc']['edit_status'], PASSWORD_BCRYPT),
				'delete' => password_hash($_SESSION['token_pengajuan_skc']['delete'], PASSWORD_BCRYPT),
			);

			$data = array();
			$no_urut = $_POST['start'];
			foreach($dataPengajuan as $row){
				$no_urut++;

				// button aksi
				$aksiDetail = '<button onclick="getView('."'".strtolower($row["id"])."'".', '."'".$this->token["view"]."'".')" type="button" class="btn btn-sm btn-info btn-flat" title="Lihat Detail"><i class="fa fa-eye"></i></button>';
				$aksiEdit = '<button onclick="getEditStatus('."'".strtolower($row["id"])."'".', '."'".$this->token["edit_status"]."'".')" type="button" class="btn btn-sm btn-success btn-flat" title="Edit Status Pengajuan"><i class="fa fa-pencil"></i></button>';
				$aksiHapus = '<button onclick="getDelete('."'".strtolower($row["id"])."'".', '."'".$this->token["delete"]."'".')" type="button" class="btn btn-sm btn-danger btn-flat" title="Hapus Data"><i class="fa fa-trash"></i></button>';
				$aksi = '<div class="btn-group">'.$aksiDetail.$aksiEdit.$aksiHapus.'</div>';

				if(strtolower($this->helper->getNamaStatusPengajuanSKK($row['status'])) == "disetujui") {
					$status = '<span class="label label-success">';
					$aksi = '<div class="btn-group">'.$aksiDetail.$aksiHapus.'</div>';
				}
				else if(strtolower($this->helper->getNamaStatusPengajuanSKK($row['status'])) == "perbaiki") {
					$status = '<span class="label label-warning">';
					$aksi = '<div class="btn-group">'.$aksiDetail.$aksiHapus.'</div>';
				}
				else if(strtolower($this->helper->getNamaStatusPengajuanSKK($row['status'])) == "ditolak") $status = '<span class="label label-danger">';
				else if(strtolower($this->helper->getNamaStatusPengajuanSKK($row['status'])) == "pending") $status = '<span class="label label-primary">';
				else $status = '<span class="label label-success">';

				$status .= $row['status'].'</span>';

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
		public function edit_status($id){
			$id = strtoupper($id);
			$token = isset($_POST['token_edit_status']) ? $_POST['token_edit_status'] : false;
			$this->auth->cekToken($_SESSION['token_pengajuan_skc']['edit_status'], $token, 'pengajuan-sub-kas-kecil');

			$this->model('Sub_kas_kecilModel');

			$dataPengajuan = $this->Pengajuan_sub_kas_kecilModel->getById($id);
			$dataSaldoSkc = $this->Sub_kas_kecilModel->getSaldoById($dataPengajuan['id_sub_kas_kecil']);

			$output = array(
				'dataPengajuan' => $dataPengajuan,
				'total' => $this->helper->cetakRupiah($dataPengajuan['total']),
				'saldo' =>  $this->helper->cetakRupiah($dataSaldoSkc['saldo']),
			);

			echo json_encode($output);
		}

		/**
		*
		*/
		public function action_edit_status(){
			$data = isset($_POST) ? $_POST : false;
			$this->auth->cekToken($_SESSION['token_pengajuan_skc']['edit_status'], $data['token'], 'pengajuan-sub-kas-kecil');

			$status = false;
			$error = "";

			if(!$data){
				$notif = array(
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
					// status disetujui
					if($data['status'] == 'DISETUJUI'){
						$ket_kas_kecil = '';
						$ket_sub_kas_kecil = '';

						$data = array(
							'id' => $this->validation->validInput($data['id']),
							'id_kas_kecil' => $_SESSION['sess_id'],
							// 'id_sub_kas_kecil' => $this->validation->validInput($data['id_sub_kas_kecil']),
							'tgl' => date('Y-m-d'),
							'dana_disetujui' => $this->validation->validInput($data['dana_disetujui']),
							// 'status' => $this->validation->validInput($data['status']),
							'status' => $this->validation->validInput($this->helper->getIdStatusPengajuanSKK($data['status'])),
							// 'ket_kas_kecil' => $this->validation->validInput($ket_kas_kecil),
							'ket_kas_kecil' => $this->validation->validInput("PERSETUJUAN PENGAJUAN SKK ".$data['id']),
							// 'ket_sub_kas_kecil' => $this->validation->validInput($ket_sub_kas_kecil),
							'ket_sub_kas_kecil' => $this->validation->validInput("PERSETUJUAN PENGAJUAN ".$data['id']." OLEH ".$_SESSION['sess_id']),
						);

						$this->model('Kas_kecilModel');
						$getSaldo = $this->Kas_kecilModel->getById($_SESSION['sess_id'])['saldo'];

						if($data['dana_disetujui'] > $getSaldo){
							$status = false;
							$error['dana_disetujui'] = "Dana yang Disetujui terlalu besar dan melebihi saldo";
						}
						else{

							// update status
							if($this->Pengajuan_sub_kas_kecilModel->acc_pengajuan($data)){
								$status = true;
								$notif = array(
									'title' => "Pesan Berhasil",
									'message' => "Edit Status Pengajuan Sub Kas Kecil Berhasil",
								);
							}
							else{
								$notif = array(
									'title' => "Pesan Gagal",
									'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
								);
							}

						}
					}
					else{ // status selain disetujui
						$data = array(
							'id' => $this->validation->validInput($data['id']),
							'status' => $this->validation->validInput($this->helper->getIdStatusPengajuanSKK($data['status'])),
						);

						// update status
						if($this->Pengajuan_sub_kas_kecilModel->update_status($data)){
							$status = true;
							$notif = array(
								'title' => "Pesan Berhasil",
								'message' => "Edit Status Pengajuan Sub Kas Kecil Berhasil",
							);
						}
						else{
							$notif = array(
								'title' => "Pesan Gagal",
								'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
							);
						}
					}
				}
				else{
					$notif = array(
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
			$excel->getActiveSheet()->mergeCells('A1:I1'); // Set Merge Cell pada kolom A1 sampai F1
			$excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
			$excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
			$excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

			// Buat header tabel nya pada baris ke 3
			$excel->setActiveSheetIndex(0)->setCellValue('A3', "NO"); // Set kolom A3 dengan tulisan "NO"
			$excel->setActiveSheetIndex(0)->setCellValue('B3', "ID PENGAJUAN"); // Set kolom B3 dengan tulisan "ID PENGAJUAN"
			$excel->setActiveSheetIndex(0)->setCellValue('C3', "ID SKK"); // Set kolom C3 dengan tulisan "ID SKK"
			$excel->setActiveSheetIndex(0)->setCellValue('D3', "ID PROYEK"); // Set kolom D3 dengan tulisan "ID PROYEK"
			$excel->setActiveSheetIndex(0)->setCellValue('E3', "TANGGAL"); // Set kolom E3 dengan tulisan "TANGGAL"
			$excel->setActiveSheetIndex(0)->setCellValue('F3', "TOTAL"); // Set kolom F3 dengan tulisan "TOTAL"
			$excel->setActiveSheetIndex(0)->setCellValue('G3', "DANA APPROVE"); // Set kolom G3 dengan tulisan "DANA APPROVE"
			$excel->setActiveSheetIndex(0)->setCellValue('H3', "STATUS"); // Set kolom H3 dengan tulisan "STATUS"
			$excel->setActiveSheetIndex(0)->setCellValue('I3', "STATUS LAPORAN"); // Set kolom I3 dengan tulisan "STATUS LAPORAN"




			// Apply style header yang telah kita buat tadi ke masing-masing kolom header
			$excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('E3')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('F3')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('G3')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('H3')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('I3')->applyFromArray($style_col);
			



			// Set height baris ke 1, 2 dan 3
			$excel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
			$excel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);
			$excel->getActiveSheet()->getRowDimension('3')->setRowHeight(20);

			// Buat query untuk menampilkan semua data siswa
			$sql = $pdo->prepare("SELECT * FROM pengajuan_sub_kas_kecil");
			$sql->execute(); // Eksekusi querynya

			$no = 1; // Untuk penomoran tabel, di awal set dengan 1
			$numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
			while($data = $sql->fetch()){ // Ambil semua data dari hasil eksekusi $sql
				$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $no);
				$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $data['id']);
				$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $data['id_sub_kas_kecil']);
				$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $data['id_proyek']);
				$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $data['tgl']);
				$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $data['total']);
				$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $data['dana_disetujui']);
				$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $data['status']);
				$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $data['status_laporan']);
				
					
				
				// Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
				$excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);
				$excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
				$excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
				$excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
				$excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);
				$excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);
				$excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);
				$excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_row);
				$excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_row);
				


				
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
			$excel->getActiveSheet()->getColumnDimension('I')->setWidth(15); // Set width kolom I



			// Set orientasi kertas jadi LANDSCAPE
			$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

			// Set judul file excel nya
			$excel->getActiveSheet(0)->setTitle("Laporan Data Pengajuan Sub Kas Kecil");
			$excel->setActiveSheetIndex(0);

			// Proses file excel
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment; filename="Data Pengajuan Sub Kas Kecil.xlsx"'); // Set nama file excel nya
			header('Cache-Control: max-age=0');

			$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
			$write->save('php://output');
			
		}

		/**
		*
		*/
		public function export_detail(){

		}

		/**
		*
		*/
		public function get_notif(){
			$notif = $this->Pengajuan_sub_kas_kecilModel->getAll_pending();
			$jumlah = $this->Pengajuan_sub_kas_kecilModel->getTotal_pending();

			$data_notif = '';
			foreach($notif as $value){
		        $data_notif .= '<li><a href="'.BASE_URL.'pengajuan-sub-kas-kecil/detail/'.strtolower($value['id']).'">';
		        $data_notif .= '<strong>'.$value['id'].' - '.$value['nama_skc'].'</strong>';
		        $data_notif .= '</br>Total: '.$this->helper->cetakRupiah($value['total']);
		        $data_notif .= '</a></li>';
			}

			$output = array(
				'notif' => $notif,
				'jumlah' => $jumlah,
				'text' => 'Anda memiliki '.$jumlah.' pengajuan yang masih Pending',
				'data' => $data_notif,
				'view_all' => BASE_URL.'pengajuan-sub-kas-kecil/',
			);

			// echo "<pre>";
			// echo json_encode(print_r($output));
			echo json_encode($output);
		}

		/**
		* Fungsi set_validation
		* method yang berfungsi untuk validasi inputan secara server side
		* param $data didapat dari post yang dilakukan oleh user
		* return berupa array, status hasil pengecekan dan error tiap validasi inputan
		*/
		private function set_validation($data){
			$required = ($data['status'] == "DISETUJUI") ? 'required' : 'not_required';

			// status
			$this->validation->set_rules($data['status'], 'Status Pengajuan Sub Kas Kecil', 'status', 'string | 1 | 255 | required');
			// dana_disetujui
			$this->validation->set_rules($data['dana_disetujui'], 'Dana yang Disetujui', 'dana_disetujui', 'nilai | 1 | 99999999999 | '.$required);

			return $this->validation->run();
		}

	}
