<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
	
	/**
	*
	*/
	class Kas_besar extends Crud_modalsAbstract{

		private $token;
		private $status = false;

		/**
		*
		*/
		public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
			$this->model('Kas_besarModel');
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
				'assets/bower_components/dropify/dist/css/dropify.min.css',
			);
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'assets/bower_components/dropify/dist/js/dropify.min.js',
				'assets/plugins/input-mask/jquery.inputmask.bundle.js',
				'app/views/kas_besar/js/initList.js',
				'app/views/kas_besar/js/initForm.js',
			);

			$config = array(
				'title' => array(
					'main' => 'Data Kas Besar',
					'sub' => 'Menampilkan Semua Data Kas Besar',
				),
				'css' => $css,
				'js' => $js,
			);

			$this->layout('kas_besar/list', $config, $data = null);
		}

		/**
		* 
		*/
		public function get_list(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				// config datatable
				$config_dataTable = array(
					'tabel' => 'kas_besar',
					'kolomOrder' => array(null, 'id', 'nama', 'alamat', 'status', null),
					'kolomCari' => array('id', 'nama', 'alamat', 'status'),
					'orderBy' => array('id' => 'desc', 'status' => 'asc'),
					'kondisi' => false,
				);

				$dataKasBesar = $this->Kas_besarModel->getAllDataTable($config_dataTable);

				$data = array();
				$no_urut = $_POST['start'];
				foreach($dataKasBesar as $row){
					$no_urut++;

					$status = (strtolower($row['status']) == "AKTIF") ? '<span class="label label-success">'.$row['status'].'</span>' : '<span class="label label-primary">'.$row['status'].'</span>';

					//button aksi
					$aksiDetail = '<button onclick="getView('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-info btn-flat" title="Lihat Detail"><i class="fa fa-eye"></i></button>';
					$aksiEdit = '<button onclick="getEdit('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-success btn-flat" title="Edit Data"><i class="fa fa-pencil"></i></button>';
					$aksiHapus = '<button onclick="getDelete('."'".$row["id"]."'".')" type="button" class="btn btn-sm btn-danger btn-flat" title="Hapus Data"><i class="fa fa-trash"></i></button>';
					
					$aksi = '<div class="btn-group">'.$aksiDetail.$aksiEdit.$aksiHapus.'</div>';
					
					$dataRow = array();
					$dataRow[] = $no_urut;
					$dataRow[] = $row['id'];
					$dataRow[] = $row['nama'];
					$dataRow[] = $row['alamat'];
					$dataRow[] = $row['status'];
					$dataRow[] = $aksi;

					$data[] = $dataRow;
				}

				$output = array(
					'draw' => $_POST['draw'],
					'recordsTotal' => $this->Kas_besarModel->recordTotal(),
					'recordsFiltered' => $this->Kas_besarModel->recordFilter(),
					'data' => $data,
				);

				echo json_encode($output);
			}
			else $this->redirect();
		}	

		/**
		*
		*/
		public function action_add(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$data = isset($_POST) ? $_POST : false;
				$foto = isset($_FILES['foto']) ? $_FILES['foto'] : false;

				$cekFoto = true;
				$error = $notif = array();

				if(!$data){
					$notif = array(
						'type' => 'error',
						'title' => "Pesan Gagal",
						'message' => "Terjadi Kesalahan Teknis, Silahkan Coba Kembali",
					);
				}
				else{
					$validasi = $this->set_validation($data);
					$cek = $validasi['cek'];
					$error = $validasi['error'];

					// cek password dan konf password
					if($data['password'] != $data['konf_password']){
						$cek = false;
						$error['password'] = $error['konf_password'] = 'Password dan Konfirmasi Password Berbeda';
					}
					
					// jika upload foto
					if($foto){
						$configFoto = array(
							'jenis' => 'gambar',
							'error' => $foto['error'],
							'size' => $foto['size'],
							'name' => $foto['name'],
							'tmp_name' => $foto['tmp_name'],
							'max' => 2*1048576,
						);
						$validasiFoto = $this->validation->validFile($configFoto);
						if(!$validasiFoto['cek']){
							$cek = false;
							$error['foto'] = $validasiFoto['error'];
						}
						else $valueFoto = md5($data['id']).$validasiFoto['namaFile'];
					}
					else $valueFoto = NULL;

					if($cek){
						$data = array(
							'id' => $this->validation->validInput($data['id']),
							'nama' => $this->validation->validInput($data['nama']),
							'alamat' => $this->validation->validInput($data['alamat']),
							'no_telp' => $this->validation->validInput($data['no_telp']),
							'email' => $this->validation->validInput($data['email'], false),
							'foto' => $this->validation->validInput($valueFoto, false),
							'status' => $this->validation->validInput($data['status']),
							'password' => password_hash($this->validation->validInput($data['password'], false), PASSWORD_BCRYPT),
								
						);

						// jika upload foto
						if($foto){
							$path = ROOT.DS.'assets'.DS.'images'.DS.'user'.$valueFoto;
							if(!move_uploaded_file($foto['tmp_name'], $path)){
								$error['foto'] = "Upload Foto Gagal";
								$this->status = $cekFoto = false;
							}
						}

						if($cekFoto){
							// cek email
							if($this->Kas_besarModel->checkExistEmail($data['email'])){
								// insert data
								if($this->Kas_besarModel->insert($data)) {
									$this->status = true;
									$notif = array(
										'type' => 'success',
										'title' => "Pesan Berhasil",
										'message' => "Tambah Data  Kas Besar Baru Berhasil",
									);
								}
								else {
									$notif = array(
										'type' => 'error',
										'title' => "Pesan Gagal",
										'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
									);
									$path = ROOT.DS.'assets'.DS.'images'.DS.'user'.$valueFoto;
									$this->helper->rollback_file($path, false);
								}
							}
							else {
								$notif = array(
									'type' => 'warning',
									'title' => "Pesan Pemberitahuan",
									'message' => "Silahkan Cek Kembali Form Isian",
								);
								$error['email'] = "Email telah digunakan sebelumnya";
							}
						}
					}
					else{
						$notif = array(
							'type' => 'warning',
							'title' => "Pesan Pemberitahuan",
							'message' => "Silahkan Cek Kembali Form Isian",
						);
					}
				}

				$output = array(
					'status' => $this->status,
					'status_foto' => $cekFoto,
					'notif' => $notif,
					'error' => $error,
					'data' => $data,
					'foto' => $foto
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
				$data = !empty($this->Kas_besarModel->getById($id)) ? $this->Kas_besarModel->getById($id) : false;

				if((empty($id) || $id == "") || !$data) $this->redirect(BASE_URL."kas-besar/");
				
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

					if($cek){
						// validasi inputan
						$data = array(
							'id' =>  $this->validation->validInput($data['id']),
							'nama' =>  $this->validation->validInput($data['nama']),
							'alamat' =>  $this->validation->validInput($data['alamat']),
							'no_telp' =>  $this->validation->validInput($data['no_telp']),
							'email' =>  $this->validation->validInput($data['email'], false),
							'status' =>  $this->validation->validInput($data['status']),
						);

						// update db
						if($this->Kas_besarModel->update($data)) {
							$this->status = true;
							$notif = array(
								'type' => "success",
								'title' => "Pesan Berhasil",
								'message' => "Edit Data Kas Besar Berhasil",
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
		*
		*/
		public function update_foto($id){

		}

		/**
		*
		*/
		public function hapus_foto($id){
			
		}

		/**
		* Function detail
		* method untuk get data detail dan setting layouting detail
		* param $id didapat dari url
		*/
		public function detail($id){
			$id = strtoupper($id);
			$data_detail = !empty($this->Kas_besarModel->getById($id)) ? $this->Kas_besarModel->getById($id) : false;

			if(!$data_detail || (empty($id) || $id == "")) $this->redirect(BASE_URL."kas-besar/");

			$css = array(
				'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
				'assets/bower_components/dropify/dist/css/dropify.min.css'
			);
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'assets/bower_components/dropify/dist/js/dropify.min.js',
				'app/views/kas_besar/js/initView.js',
				'app/views/kas_besar/js/initForm.js',
			);

			$config = array(
				'title' => array(
					'main' => 'Data Kas Besar',
					'sub' => 'Detail Data Kas Besar',
				),
				'css' => $css,
				'js' => $js,
			);

			$status = ($data_detail['status'] == "AKTIF") ? 
				'<span class="label label-success">'.$data_detail['status'].'</span>' : 
				'<span class="label label-danger">'.$data_detail['status'].'</span>';
			
			// validasi foto
			if(!empty($data_detail['foto'])){
				// cek foto di storage
				$filename = ROOT.DS.'assets'.DS.'images'.DS.'user'.DS.$data_detail['foto'];
				if(!file_exists($filename)) 
					$foto = BASE_URL.'assets/images/user/default.jpg';
				else
					$foto = BASE_URL.'assets/images/user/'.$data_detail['foto'];
			}
			else $foto = BASE_URL.'assets/images/user/default.jpg';

			$data = array(
				'id' => $data_detail['id'],
				'nama' => $data_detail['nama'],
				'alamat' => $data_detail['alamat'],
				'no_telp' => $data_detail['no_telp'],
				'email' => $data_detail['email'],
				'foto' => $foto,
				'status' => $status,
			);

			$this->layout('kas_besar/view', $config, $data);
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
				
				if($this->Kas_besarModel->delete($id)) $this->status = true;

				echo json_encode($this->status);
			}
			else $this->redirect();
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
								   ->setTitle("Data Kas Besar")
								   ->setSubject("Kas Besar")
								   ->setDescription("Laporan Semua Data Kas Besar")
								   ->setKeywords("Data Kas Besar");

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

			$excel->setActiveSheetIndex(0)->setCellValue('A1', "DATA KAS BESAR"); // Set kolom A1 dengan tulisan "DATA SISWA"
			$excel->getActiveSheet()->mergeCells('A1:G1'); // Set Merge Cell pada kolom A1 sampai F1
			$excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
			$excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
			$excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

			// Buat header tabel nya pada baris ke 3
			$excel->setActiveSheetIndex(0)->setCellValue('A3', "NO"); // Set kolom A3 dengan tulisan "NO"
			$excel->setActiveSheetIndex(0)->setCellValue('B3', "ID"); // Set kolom B3 dengan tulisan "ID"
			$excel->setActiveSheetIndex(0)->setCellValue('C3', "NAMA"); // Set kolom C3 dengan tulisan "NAMA"
			$excel->setActiveSheetIndex(0)->setCellValue('D3', "ALAMAT"); // Set kolom C3 dengan tulisan "NAMA"
			$excel->setActiveSheetIndex(0)->setCellValue('E3', "NO TELP"); // Set kolom D3 dengan tulisan "NO Telepon"
			$excel->setActiveSheetIndex(0)->setCellValue('F3', "EMAIL"); // Set kolom E3 dengan tulisan "Email"
			$excel->setActiveSheetIndex(0)->setCellValue('G3', "STATUS"); // Set kolom E3 dengan tulisan "STATUS"



			// Apply style header yang telah kita buat tadi ke masing-masing kolom header
			$excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('E3')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('F3')->applyFromArray($style_col);
			$excel->getActiveSheet()->getStyle('G3')->applyFromArray($style_col);




			// Set height baris ke 1, 2 dan 3
			$excel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
			$excel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);
			$excel->getActiveSheet()->getRowDimension('3')->setRowHeight(20);

			// Buat query untuk menampilkan semua data siswa
			$sql = $pdo->prepare("SELECT * FROM kas_besar");
			$sql->execute(); // Eksekusi querynya

			$no = 1; // Untuk penomoran tabel, di awal set dengan 1
			$numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
			while($data = $sql->fetch()){ // Ambil semua data dari hasil eksekusi $sql
				$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $no);
				$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $data['id']);
				$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $data['nama']);
				$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $data['alamat']);
				$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $data['no_telp']);
				$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $data['email']);
				$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $data['status']);
				

				
				// Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
				$excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);
				$excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
				$excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
				$excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
				$excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);
				$excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);
				$excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);



				
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
			$excel->getActiveSheet()->getColumnDimension('G')->setWidth(15); // Set width kolom F




			// Set orientasi kertas jadi LANDSCAPE
			$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

			// Set judul file excel nya
			$excel->getActiveSheet(0)->setTitle("Laporan Data Kas Besar");
			$excel->setActiveSheetIndex(0);

			// Proses file excel
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment; filename="Data Kas Besar.xlsx"'); // Set nama file excel nya
			header('Cache-Control: max-age=0');

			$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
			$write->save('php://output');
			
		}

		/**
		*
		*/
		public function get_last_id(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$data = !empty($this->Kas_besarModel->getLastID()['id']) ? $this->Kas_besarModel->getLastID()['id'] : false;

				if(!$data) $id = 'KB001';
				else{
					$kode = 'KB';
					$noUrut = (int)substr($data, 2, 3);
					$noUrut++;

					$id = $kode.sprintf("%03s", $noUrut);
				}

				echo json_encode($id);
			}
			else $this->redirect();
		}

		/**
		* Fungsi set_validation
		* method yang berfungsi untuk validasi inputan secara server side
		* param $data didapat dari post yang dilakukan oleh user
		* return berupa array, status hasil pengecekan dan error tiap validasi inputan
		*/
		private function set_validation($data){
			$required = ($data['action'] == "action-edit") ? 'not_required' : 'required';

			// ID
			$this->validation->set_rules($data['id'], 'ID Kas Kecil', 'id', 'string | 1 | 255 | required');
			// nama
			$this->validation->set_rules($data['nama'], 'Nama', 'nama', 'string | 1 | 255 | required');
			// alamat
			$this->validation->set_rules($data['alamat'], 'Alamat Proyek', 'alamat', 'string | 1 | 255 | not_required');
			// no_telp
			$this->validation->set_rules($data['no_telp'], 'Nomor Telepon', 'no_telp', 'angka | 1 | 255 | required');
			// email
			$this->validation->set_rules($data['email'], 'Alamat Email', 'email', 'email | 1 | 255 |', $required);
			// status
			$this->validation->set_rules($data['status'], 'Status', 'status', 'string | 1 | 255 | required');

			return $this->validation->run();
		}

	}