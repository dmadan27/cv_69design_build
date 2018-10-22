<?php 
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	*
	*/
	class Pengajuan_kas_kecil extends Crud_modalsAbstract{

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
			$this->model('Pengajuan_kasKecilModel');
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
				'assets/plugins/input-mask/jquery.inputmask.bundle.js',
				'assets/bower_components/select2/dist/js/select2.full.min.js',
				'app/views/pengajuan_kas_kecil/js/initList.js',
				'app/views/pengajuan_kas_kecil/js/initForm.js',
			);

			$config = array(
				'title' => array(
					'main' => 'Data Pengajuan Kas Kecil',
					'sub' => 'List Data Pengajuan Kas Kecil',
				),
				'css' => $css,
				'js' => $js,
			);

			$this->layout('pengajuan_kas_kecil/list', $config, $data = NULL);
		}	

		/**
		* Function get_list
		* method khusus untuk datatable
		* generate token edit dan delete
		* return json
		*/
		public function get_list(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				// config datatable
				$config_dataTable = array(
					'tabel' => 'pengajuan_kas_kecil',
					'kolomOrder' => array(null, 'id','id_kas_kecil', 'tgl', 'nama',  'total', 'status',null),
					'kolomCari' => array('id','id_kas_kecil','nama',  'status'),
					'orderBy' => array('id' => 'asc'),
					'kondisi' => false,
				);

				$dataPengajuanKasKecil = $this->Pengajuan_kasKecilModel->getAllDataTable($config_dataTable);

				$data = array();
				$no_urut = $_POST['start'];
				foreach($dataPengajuanKasKecil as $row){
					$no_urut++;

					$status = ($row['status'] == "PENDING") ? '<span class="label label-warning">'.$row['status'].'</span>' : '<span class="label label-danger">'.$row['status'].'</span>';

					// // button aksi
					$aksiDetail = '<button onclick="getView('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-info btn-flat" title="Lihat Detail"><i class="fa fa-eye"></i></button>';
					$aksiEdit = '<button onclick="getEdit('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-success btn-flat" title="Edit Data"><i class="fa fa-pencil"></i></button>';
					$aksiHapus = '<button onclick="getDelete('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-danger btn-flat" title="Hapus Data"><i class="fa fa-trash"></i></button>';
					
					$aksi = '<div class="btn-group">'.$aksiDetail.$aksiEdit.$aksiHapus.'</div>';
					
					$dataRow = array();
					$dataRow[] = $no_urut;
					$dataRow[] = $row['id'];
					$dataRow[] = $row['id_kas_kecil'];
					$dataRow[] = $row['tgl'];
					$dataRow[] = $row['nama'];
					$dataRow[] = $row['total'];
					$dataRow[] = $row['status'];		
					$dataRow[] = $aksi;

					$data[] = $dataRow;
				}

				$output = array(
					'draw' => $_POST['draw'],
					'recordsTotal' => $this->Pengajuan_kasKecilModel->recordTotal(),
					'recordsFiltered' => $this->Pengajuan_kasKecilModel->recordFilter(),
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
							'id' => $this->validation->validInput($data['id']),
							'id_kas_kecil' =>$this->validation->validInput($data['id_kas_kecil']),
							'tgl' => $this->validation->validInput($data['tgl']),
							'nama' => $this->validation->validInput($data['nama']),
							'total' => $this->validation->validInput($data['total']),
							'status' => $this->validation->validInput($data['status']),
						);

						// insert pengajuan kas kecil
						if($this->Pengajuan_kasKecilModel->insert($data)) {
							$this->status = true;
							$notif = array(
								'type' => 'success',
								'title' => "Pesan Berhasil",
								'message' => "Tambah Data Pengajuan Kas Kecil Berhasil",
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
					'data' => $data
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
			$data = !empty($this->Pengajuan_kasKecilModel->getById($id)) ? $this->Pengajuan_kasKecilModel->getById($id) : false;
			echo json_encode($data);
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
			$data = isset($_POST) ? $_POST : false;
			
			$error = $notif = array();
			if(!$data){
				$notif = array(
					'type' => "error",
					'title' => "Pesan Pemberitahuan",
					'message' => "Silahkan Cek Kembali Form Isian",
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
						// 'tgl' => $this->validation->validInput($data['tgl']),
						// 'nama' => $this->validation->validInput($data['nama']),
						// 'total' => $this->validation->validInput($data['total']),
						'status' => $this->validation->validInput($data['status'])
							
					);

					// update db
					if($this->Pengajuan_kasKecilModel->update($data)) {
						$status = true;
						$notif = array(
							'type' => "success",
							'title' => "Pesan Berhasil",
							'message' => "Edit Data Pengajuan Kas Kecil Berhasil",
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
						'type' => 'warning',
						'title' => "Pesan Pemberitahuan",
						'message' => "Silahkan Cek Kembali Form Isian",
					);
					
				}
			}

			$output = array(
				'status' => $status,
				'notif' => $notif,
				'error' => $error,
				// 'data' => $data
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
			$data_detail = !empty($this->Pengajuan_kasKecilModel->getById($id)) ? $this->Pengajuan_kasKecilModel->getById($id) : false;

			if((empty($id) || $id == "") || !$data_detail) $this->redirect(BASE_URL."pengajuan-kas-kecil/");

			$css = array(
				'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
			);
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'assets/plugins/input-mask/jquery.inputmask.bundle.js',
				'app/views/pengajuan_kas_kecil/js/initView.js',
				'app/views/pengajuan_kas_kecil/js/initForm.js',
			);

			$config = array(
				'title' => array(
					'main' => 'Data Pengajuan Kas Kecil',
					'sub' => 'Detail Data Pengajuan Kas Kecil',
				),
				'css' => $css,
				'js' => $js,
			);

			// $status = ($data_detail['status'] == "AKTIF") ? 
			// 	'<span class="label label-success">'.$data_detail['status'].'</span>' : 
			// 	'<span class="label label-danger">'.$data_detail['status'].'</span>';



			$data = array(
				'id' => $data_detail['id'],
				'id_kas_kecil' => $data_detail['id_kas_kecil'],
				'tgl' => $data_detail['tgl'],
				'nama' => $data_detail['nama'],
				'total' => $data_detail['total'],
				'status' => $data_detail['status'],
			);

			$this->layout('pengajuan_kas_kecil/view', $config, $data);

			
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
				if(empty($id) || $id == "") $this->redirect(BASE_URL."pengajuan-kas-kecil/");

				if($this->Pengajuan_kasKecilModel->delete($id)) $this->status = true;

				echo json_encode($this->status);
			}
			else $this->redirect();	
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
								   ->setTitle("Data Pengajuan Kas Kecil")
								   ->setSubject("Pengajuan Kas Kecil")
								   ->setDescription("Laporan Semua Data Pengajuan Kas Kecil")
								   ->setKeywords("Data Pengajuan Kas Kecil");

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

			$excel->setActiveSheetIndex(0)->setCellValue('A1', "DATA PENGAJUAN KAS KECIL"); // Set kolom A1 dengan tulisan "DATA PENGAJUAN KAS KECIL"
			$excel->getActiveSheet()->mergeCells('A1:H1'); // Set Merge Cell pada kolom A1 sampai G1
			$excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
			$excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
			$excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

			// Buat header tabel nya pada baris ke 3
			$excel->setActiveSheetIndex(0)->setCellValue('A3', "NO"); // Set kolom A3 dengan tulisan "NO"
			$excel->setActiveSheetIndex(0)->setCellValue('B3', "ID"); // Set kolom B3 dengan tulisan "NO"
			
			$excel->setActiveSheetIndex(0)->setCellValue('C3', "NAMA"); // Set kolom C3 dengan tulisan "NAMA"
			$excel->setActiveSheetIndex(0)->setCellValue('D3', "TANGGAL"); // Set kolom D3 dengan tulisan "TANGGAL"
			$excel->setActiveSheetIndex(0)->setCellValue('E3', "TOTAL"); // Set kolom E3 dengan tulisan "TOTAL"
			$excel->setActiveSheetIndex(0)->setCellValue('F3', "STATUS"); // Set kolom F3 dengan tulisan "STATUS"
			$excel->setActiveSheetIndex(0)->setCellValue('G3', "ID KAS KECIL"); // Set kolom G3 dengan tulisan "ID KAS KECIL"
			$excel->setActiveSheetIndex(0)->setCellValue('H3', "NAMA KAS KECIL"); // Set kolom H3 dengan tulisan "NAMA KAS KECIL"
			$excel->setActiveSheetIndex(0)->setCellValue('I3', "TANGGAL CETAK"); // Set kolom H3 dengan tulisan "NAMA KAS KECIL"
			




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
			$sql = $pdo->prepare("SELECT * FROM v_pengajuan_kas_kecil");
			$sql->execute(); // Eksekusi querynya

			$no = 1; // Untuk penomoran tabel, di awal set dengan 1
			$numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
			while($data = $sql->fetch()){ // Ambil semua data dari hasil eksekusi $sql
				$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $no);
				$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $data['id']);
				$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $data['nama']);
				$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $data['tgl']);
				$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $data['total']);
				$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $data['status']);
				$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $data['id_kas_kecil']);
				$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $data['nama_kas_kecil']);
				
				
				
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
			$excel->getActiveSheet(0)->setTitle("Laporan Data Pengajuan Kas Kecil");
			$excel->setActiveSheetIndex(0);

			// Proses file excel
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment; filename="Data Pengajuan Kas Kecil.xlsx"'); // Set nama file excel nya
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
			// $required = ($action =="action-add") ? 'not_required' : 'required';
			// id
			$this->validation->set_rules($data['id'], 'ID Pengajuan Kas Kecil', 'id', 'string | 1 | 255 | required');
			// id_kas_kecil
			$this->validation->set_rules($data['id_kas_kecil'], 'ID Pengajuan Kas Kecil', 'id_kas_kecil', 'string | 1 | 255 | required');
			// tgl
			$this->validation->set_rules($data['tgl'], 'Tanggal Pengajuan Kas Kecil', 'tgl', 'string | 1 | 255 | required');
			// nama pengajuan kas kecil
			$this->validation->set_rules($data['nama'], 'Nama Pengajuan', 'nama', 'string | 1 | 255 | required');
			// total
			$this->validation->set_rules($data['total'], 'Total Pengajuan', 'total', 'nilai | 1 | 99999999 | required');
			// status
			$this->validation->set_rules($data['status'], 'Status Pengajuan', 'status', 'string | 1 | 255 | required');
			
			return $this->validation->run();
			
		}

		/**
		*
		*/
		public function get_notif(){
			$notif = $this->Pengajuan_kasKecilModel->getAll_pending();
			$jumlah = $this->Pengajuan_kasKecilModel->getTotal_pending();

			$data_notif = '';
			foreach($notif as $value){
		        $data_notif .= '<li><a href="'.BASE_URL.'pengajuan-kas-kecil/detail/'.strtolower($value['id']).'">';
		        $data_notif .= '<strong>'.$value['id'].' - '.$value['nama_kas_kecil'].'</strong>';
		        $data_notif .= '</br>Total: '.$this->helper->cetakRupiah($value['total']); 
		        $data_notif .= '</a></li>';
			}

			$output = array(
				'notif' => $notif,
				'jumlah' => $jumlah,
				'text' => 'Anda memiliki '.$jumlah.' pengajuan yang masih Pending',
				'data' => $data_notif,
				'view_all' => BASE_URL.'pengajuan-kas-kecil/',
			);

			echo json_encode($output);
		}
		

		/**
		*
		*/
		public function get_nama_kas_kecil(){
			$this->model('Kas_kecilModel');
			$data_kas_kecil =  $this->Kas_kecilModel->getAll();
			$data = array();


				foreach ($data_kas_kecil as $row) {
					$dataRow = array();
					$dataRow['id'] = $row['id'];
					$dataRow['text'] = $row['id'].' - '.$row['nama'];

					$data[] = $dataRow;
				}
				

			echo json_encode($data);
		}

		/**
		*
		*/
		public function get_last_id(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$data = !empty($this->Pengajuan_kasKecilModel->getLastID()['id']) ? $this->Pengajuan_kasKecilModel->getLastID()['id'] : false;

				if(!$data) $id = 'PKK0001';
				else{
					$kode = 'PKK';
					$noUrut = (int)substr($data, 3, 4);
					$noUrut++;

					$id = $kode.sprintf("%04s", $noUrut);
				}

				echo json_encode($id);
			}
			else $this->redirect();
		}

		/**
		* 
		*/
		public function count_pengajuan_kas_kecil_disetujui(){
			$this->model('Pengajuan_kasKecilModel');
			$data_pkk_disetujui = $this->Pengajuan_kasKecilModel->getTotal_setujui();

			// foreach($data_pkk_disetujui as $row){
			// 	$data[] = $row;
			// }
			
			echo json_encode($data_pkk_disetujui);

		}


	}