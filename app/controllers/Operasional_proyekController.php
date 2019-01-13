<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	*
	*/
	class Operasional_proyek extends CrudAbstract{

		// private $token;
		// private $status = false;
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
			$this->model('Operasional_proyekModel');
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
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				// config datatable
				$config_dataTable = array(
					'tabel' => 'operasional_proyek',
					'kolomOrder' => array(null, 'id', 'id_proyek', 'id_kas_besar', 'id_distributor', 'tgl', 'nama', 'total', null),
					'kolomCari' => array('id', 'id_proyek', 'id_kas_besar', 'id_distributor', 'tgl', 'nama', 'total'),
					'orderBy' => array('id' => 'asc'),
					'kondisi' => false,
				);

				$dataOperasionalProyek = $this->Operasional_proyekModel->getAllDataTable($config_dataTable);

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
					$dataRow[] = $row['id_kas_besar'];
					$dataRow[] = $row['id_distributor'];
					$dataRow[] = $this->helper->cetakTgl($row['tgl'], 'full');
					$dataRow[] = $row['nama'];
					$dataRow[] = $this->helper->cetakRupiah($row['total']);
					$dataRow[] = $aksi;

					$data[] = $dataRow;
				}

				$output = array(
					'draw' => $_POST['draw'],
					'recordsTotal' => $this->Operasional_proyekModel->recordTotal(),
					'recordsFiltered' => $this->Operasional_proyekModel->recordFilter(),
					'data' => $data,
				);

				echo json_encode($output);	

			}
			else $this->redirect;

			
				
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
				'id_bank' => '',
				'id_kas_besar' => '',
				'id_distributor' => '',
				'tgl' => '',
				'nama' => '',
				'jenis' => '',
				'total' => '',
				'sisa' => '',
				'status' => '',
				'status_lunas' => '',
				'ket' => '',
				
					
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
				$dataDetail = isset($_POST['listDetail']) ? json_decode($_POST['listDetail'], true) : false;
				
				if(!$data){
					
					$this->notif = array(
						'type' => "error",
						'title' => "Pesan Gagal",
						'message' => "Terjadi kesalahan teknis, silahkan coba kembali",
					);
				} else {
					// validasi data
					$validasi = $this->set_validation($dataOperasionalProyek, $data['action']);
					$cek = $validasi['cek'];
					$this->error = $validasi['error'];
					
					if($cek){
						$keterangan = 'OPERASIONAL PROYEK ['.$dataOperasionalProyek['id'].'] - '.strtoupper($dataOperasionalProyek['nama']);

						// validasi input
						$dataOperasionalProyek = array(
							'id' => $this->validation->validInput($dataOperasionalProyek['id']),
							'id_proyek' => $this->validation->validInput($dataOperasionalProyek['id_proyek']),
							'id_bank' => $this->validation->validInput($dataOperasionalProyek['id_bank']),
							'id_kas_besar' => $_SESSION['sess_id'],
							'id_distributor' => $this->validation->validInput($dataOperasionalProyek['id_distributor']),
							'tgl' => $this->validation->validInput($dataOperasionalProyek['tgl']),
							'nama' => $this->validation->validInput($dataOperasionalProyek['nama']),
							'jenis' => $this->validation->validInput($dataOperasionalProyek['jenis']),
							'total' => $this->validation->validInput($dataOperasionalProyek['total']),
							'sisa' => $this->validation->validInput($dataOperasionalProyek['sisa']),
							'status' => $this->validation->validInput($dataOperasionalProyek['status']),
							'status_lunas' => $this->validation->validInput($dataOperasionalProyek['status_lunas']),
							'ket' => $this->validation->validInput($dataOperasionalProyek['ket']),
							'keterangan' => $keterangan,
						);

						$dataInsert = array(
							'dataOperasionalProyek' => $dataOperasionalProyek,
							'listDetail'=>$dataDetail
						);
						
						$res = $this->Operasional_proyekModel->insert($dataInsert);
						if($res['success']){
							$this->success = true;
							$_SESSION['notif'] = array(
								'type' => "success",
								'title' => "Pesan Berhasil",
								'message' => "Tambah Data Operasional Proyek Baru Berhasil",
							);
							$this->notif['default'] = $_SESSION['notif'];

						} else if($res['invalidtotaldetail'] == "invalidTotal") {

							$this->notif['default'] = array(
								'type' => "warning",
								'title' => "Pesan Pemberitahuan",
								'message' => "Cek Kembali List Detail / Total Detail Anda",
							);

						} else {

							$this->notif['default'] = array(
								'type' => "error",
								'title' => "Pesan Gagal",
								'message' => "Terjadi kesalahan teknis, silahkan coba kembali",
							);

						}
					} else {
						$this->notif['default'] = array(
							'type' => 'warning',
							'title' => "Pesan Pemberitahuan",
							'message' => "Silahkan Cek Kembali Form Isian",
						);
					}
					
				}

				$output = array(
					'status' => $this->success,
					'notif' => $this->notif,
					'error' => $this->error,
					'cek' => array(
						'cek' => $cek,
						
					),
					'data' => $data,
					'dataOperasionalProyek' => $dataOperasionalProyek,
				);
				echo json_encode($output);
			}
			else $this->redirect();
		}

		/**
		*
		*/
		protected function edit($id){
			$id = strtoupper($id);

			$dataOperasionalProyek = !empty($this->Operasional_proyekModel->getById($id)) ? $this->Operasional_proyekModel->getById($id) :false;
			$id_bank = !empty($this->Operasional_proyekModel->getBankById($id)) ? $this->Operasional_proyekModel->getBankById($id) :false;

			if(empty($id) || $id == "") $this->redirect(BASE_URL."operasional-proyek/");

			$css = array(
  				'assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
				'assets/bower_components/select2/dist/css/select2.min.css',
				'assets/plugins/bootstrap-slider/slider.css',
  			);
			$js = array(
				'assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
				'assets/bower_components/select2/dist/js/select2.full.min.js',
				'assets/plugins/input-mask/jquery.inputmask.bundle.js',
				'assets/plugins/bootstrap-slider/bootstrap-slider.js',
				'app/views/operasional_proyek/js/initForm.js',	
			);

			$config = array(
				'title' => array(
					'main' => 'Data Operasional Proyek',
					'sub' => 'Form Edit Data',
				),
				'css' => $css,
				'js' => $js,
			);

			$data = array(
				'action' => 'action-edit',
				'id' => $dataOperasionalProyek['id'],
				'id_proyek'=> $dataOperasionalProyek['id_proyek'],
				'id_bank'=> $id_bank['id_bank'],
				'id_kas_besar'=> $dataOperasionalProyek['id_kas_besar'],
				'id_distributor'=> $dataOperasionalProyek['id_distributor'],
				'tgl'=> $dataOperasionalProyek['tgl'],
				'nama'=> $dataOperasionalProyek['nama'],
				'jenis'=> $dataOperasionalProyek['jenis'],
				'total'=> $dataOperasionalProyek['total'],
				'sisa'=> $dataOperasionalProyek['sisa'],
				'status'=> $dataOperasionalProyek['status'],
				'status_lunas'=> $dataOperasionalProyek['status_lunas'],
				'ket'=> $dataOperasionalProyek['ket'],
			);

			$this->layout('operasional_proyek/form', $config, $data);
		}

		/**
		* Method get edit
		* Get data detail proyek dan detail skk
		* Request berupa POST dan output berupa JSON
		* Parameter id => id proyek
		*/
		public function get_edit($id){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$id = strtoupper($id);
				if(empty($id) || $id == "") $this->redirect(BASE_URL."operasional-proyek/");

				$dataOperasionalProyek = $this->Operasional_proyekModel->getById($id);
				$dataDetail = $this->Operasional_proyekModel->getDetailById($id);

				$id_bank = !empty($this->Operasional_proyekModel->getBankById($id)) ? $this->Operasional_proyekModel->getBankById($id) :false;

				$dataOperasionalProyek['id_bank'] = $id_bank['id_bank'];

				$dataDetail_new = array();
				foreach($dataDetail as $row) {
					$temp = $row;
					$temp['tgl_detail_full'] = $this->helper->cetakTgl($row['tgl_detail'], 'full');
					$temp['total_detail_full'] = $this->helper->cetakRupiah($row['total_detail']);

					$dataDetail_new[] = $temp;
				}

				$output = array(
					'dataOperasionalProyek' => $dataOperasionalProyek,
					'dataDetail' => $dataDetail_new
				);

				echo json_encode($output);
			}
			else $this->redirect();	
		}

		/**
		*
		*/
		public function action_edit(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$data = isset($_POST) ? $_POST :false;
				$dataOperasionalProyek = isset($_POST['dataOperasionalProyek']) ? json_decode($_POST['dataOperasionalProyek'], true) : false;
				$dataDetail = isset($_POST['listDetail']) ? json_decode($_POST['listDetail'], true) : false;	
				$dataDetailTambahan = isset($_POST['listDetail_Tambahan']) ? json_decode($_POST['listDetail_Tambahan'], true) : false;
				$toDeleteList = isset($_POST['toDelete']) ? json_decode($_POST['toDelete'], true) : false;
				$toEditList = isset($_POST['toEdit']) ? json_decode($_POST['toEdit'], true) : false;

				$error = $notif = array();
				if(!$data){
					$notif = array(
						'title' => "Pesan Gagal",
						'message' => "Terjadi kesalahan teknis, silahkan coba kembali",
					);
				}
				else{
					$validasi = $this->set_validation($dataOperasionalProyek, $data['action']);
					$cek = $validasi['cek'];
					$error = $validasi['error'];

					

					if($cek){
						$keterangan = 'OPERASIONAL PROYEK ['.$dataOperasionalProyek['id'].'] - '.strtoupper($dataOperasionalProyek['nama']);
						
						$dataOperasionalProyek = array(
							'id' => $this->validation->validInput($dataOperasionalProyek['id']),
							'id_proyek' => $this->validation->validInput($dataOperasionalProyek['id_proyek']),
							'id_bank' => $this->validation->validInput($dataOperasionalProyek['id_bank']),
							'id_kas_besar' => $_SESSION['sess_id'],
							'id_distributor' => $this->validation->validInput($dataOperasionalProyek['id_distributor']),
							'tgl' => $this->validation->validInput($dataOperasionalProyek['tgl']),
							'nama' => $this->validation->validInput($dataOperasionalProyek['nama']),
							'jenis' => $this->validation->validInput($dataOperasionalProyek['jenis']),
							'total' => $this->validation->validInput($dataOperasionalProyek['total']),
							'sisa' => $this->validation->validInput($dataOperasionalProyek['sisa']),
							'status' => $this->validation->validInput($dataOperasionalProyek['status']),
							'status_lunas' => $this->validation->validInput($dataOperasionalProyek['status_lunas']),
							'ket' => $this->validation->validInput($dataOperasionalProyek['ket']),
							'keterangan' => $keterangan,
						);

					$dataUpdate = array(
							'dataOperasionalProyek' => $dataOperasionalProyek,
							'dataDetail' => $dataDetail,
							'dataDetailTambahan' => $dataDetailTambahan,
							'toDelete' => $toDeleteList,
							'toEdit' => $toEditList
					);
					$res = $this->Operasional_proyekModel->update($dataUpdate);
					// update data
					if($res['success']){

						$this->success = true;
							$_SESSION['notif'] = array(
								'type' => "success",
								'title' => "Pesan Berhasil",
								'message' => "Edit Data Proyek Berhasil",
							);
							$notif['default'] = $_SESSION['notif'];

					} else if($res['invalidtotaldetail'] == "invalidTotal") {

						$this->notif['default'] = array(
							'type' => "warning",
							'title' => "Pesan Pemberitahuan",
							'message' => "Cek Kembali List Detail / Total Detail Anda",
						);

					} else {

						$this->notif['default'] = array(
							'type' => "error",
							'title' => "Pesan Gagal",
							'message' => "Terjadi kesalahan teknis, silahkan coba kembali",
						);

					}

				}

			}

			$output = array(
				'status' => $this->success,
				'notif' => $this->notif,
				'error' => $this->error,
				'cek' => array(
					'cek' => $cek,
				),
				'dataOperasionalProyek' => $dataOperasionalProyek,
			);

			echo json_encode($output);			
		} else $this->redirect();
	}

		/**
		*
		*/
		public function detail($id){
			$id = strtoupper($id);
			$dataOperasionalProyek = !empty($this->Operasional_proyekModel->getById_fromView($id)) ? $this->Operasional_proyekModel->getById_fromView($id) : false;

			$dataDetailOperasionalProyek = !empty($this->Operasional_proyekModel->getDetailById_fromView($id)) ? $this->Operasional_proyekModel->getDetailById_fromView($id) : false;

			$dataHistoryPembelanjaan = !empty($this->Operasional_proyekModel->getBYid_fromHistoryPembelian($id)) ? $this->Operasional_proyekModel->getBYid_fromHistoryPembelian($id) : false;

			// var_dump($dataHistoryPembelanjaan);

			// if((empty($id) || $id == "") || !$dataOperasionalProyek) $this->redirect(BASE_URL."operasional-proyek/");


			// if((empty($id) || $id == "") || !$dataHistoryPembelanjaan) $this->redirect(BASE_URL."operasional-proyek/");
			

			$css = array(
				'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css'
			);
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'app/views/operasional_proyek/js/initView.js',
			);

			$config = array(
				'title' => array(
					'main' => 'Data Operasional Proyek',
					'sub' => 'Detail Data Operasional Proyek',
				),
				'css' => $css,
				'js' => $js,
			);

			$dataOperasionalProyek = array(

				'id' => $dataOperasionalProyek['id'],
				'id_proyek' =>   $dataOperasionalProyek['id_proyek'],
				'pemilik_proyek' =>   $dataOperasionalProyek['pemilik_proyek'],
				'nama_pembangunan' =>   $dataOperasionalProyek['nama_pembangunan'],
				'id_kas_besar' => $dataOperasionalProyek['id_kas_besar'],
				'nama_kas_besar' => $dataOperasionalProyek['nama_kas_besar'],
				'id_distributor' => $dataOperasionalProyek['id_distributor'],
				'nama_distributor' => $dataOperasionalProyek['nama_distributor'],
				'tgl_pengajuan' => $dataOperasionalProyek['tgl_pengajuan'],
				'nama_pengajuan' => $dataOperasionalProyek['nama_pengajuan'],
				'jenis_pengajuan' => $dataOperasionalProyek['jenis_pengajuan'],
				'total_pengajuan' => $dataOperasionalProyek['total_pengajuan'],
				'sisa_pengajuan' => $dataOperasionalProyek['sisa_pengajuan'],
				'status_pengajuan' => $dataOperasionalProyek['status_pengajuan'],
				'status_lunas' => $dataOperasionalProyek['status_lunas'],
				'keterangan' => $dataOperasionalProyek['keterangan'],
				'id_bank' => $dataOperasionalProyek['id_bank'],
				'nama_detail' => $dataOperasionalProyek['nama_detail'],
				'tgl_detail' => $dataOperasionalProyek['tgl_detail'],
				'total_detail' => $dataOperasionalProyek['total_detail'],
					
			);

			$dataDetailOperasionalProyek = array(
				'id_bank' => $dataOperasionalProyek['id_bank'],
				'nama_detail' => $dataOperasionalProyek['nama_detail'],
				'tgl_detail' => $dataOperasionalProyek['tgl_detail'],
				'total_detail' => $dataOperasionalProyek['total_detail'],
			);

			$dataHistoryPembelanjaan = array(
				'id' => $dataHistoryPembelanjaan['id'],
				'tgl' => $dataHistoryPembelanjaan['tgl'],
				'nama' => $dataHistoryPembelanjaan['nama'],
				'total' => $dataHistoryPembelanjaan['total'],
				'status_lunas' => $dataHistoryPembelanjaan['status_lunas'],
				'ID_DISTRIBUTOR' => $dataHistoryPembelanjaan['ID_DISTRIBUTOR'],
				'NAMA_DISTRIBUTOR' => $dataHistoryPembelanjaan['NAMA_DISTRIBUTOR'],
				'pemilik' => $dataHistoryPembelanjaan['pemilik'],
					
			);




			// foreach ($this->Operasional_proyekModel->getBYid_fromHistoryPembelian($id) as $row) {
				
			// 	$dataRow = array();
			// 	$dataRow['id'] = $row['id'];
			// 	$dataRow['tgl'] = $row['tgl'];
			// 	$dataRow['nama'] = $row['nama'];
			// 	$dataRow['total'] = $row['total'];
			// 	$dataRow['status_lunas'] = $row['status_lunas'];
			// 	$dataRow['ID_DISTRIBUTOR'] = $row['ID_DISTRIBUTOR'];
			// 	$dataRow['NAMA_DISTRIBUTOR'] = $row['NAMA_DISTRIBUTOR'];
			// 	$dataRow['pemilik'] = $row['pemilik'];
				
			// 	$dataHistoryPembelanjaan[] = $dataRow;
			// }
			
			$data = array(
				'dataOperasionalProyek' => $dataOperasionalProyek,
				'dataDetailOperasionalProyek' => $dataDetailOperasionalProyek,
				'dataHistoryPembelanjaan' => $dataHistoryPembelanjaan
			);

			$this->layout('operasional_proyek/view', $config, $data);


		}

		/**
		*
		*/
		public function delete($id){
			if($_SERVER['REQUEST_METHOD'] == "POST" && $id != ''){
				$id = strtoupper($id);
				
				if(empty($id) || $id == "") { $this->redirect(BASE_URL."operasional-proyek/"); }

				$dataOperasionalProyek = $this->Operasional_proyekModel->getById($id);
				$dataDetail = $this->Operasional_proyekModel->getDetailById($id);

				$data = array(
					'dataOperasionalProyek' => $dataOperasionalProyek,
					'dataDetail' => $dataDetail
				);

				$delete = $this->Operasional_proyekModel->delete($data);

				if($delete['success']){
					$this->success = true;
					$_SESSION['notif'] = array(
						'type' => "success",
						'title' => "Pesan Berhasil",
						'message' => "Hapus Data Operasional Proyek Baru Berhasil",
					);
					$notif['default'] = $_SESSION['notif'];
				} else {
					$notif['default'] = array(
						'type' => "error",
						'title' => "Pesan Gagal",
						'message' => "Terjadi kesalahan teknis, silahkan coba kembali",
					);
				}

				echo json_encode(array(
					'success' => $this->success,
					'message' => $this->message,
					'notif' => $this->notif
				));
			} else { 
				$this->redirect(); 
			}	
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

				$data = !empty($this->Operasional_proyekModel->getLastID($id_temp)['id']) ? $this->Operasional_proyekModel->getLastID($id_temp)['id'] : false;

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
		public function get_nama_proyek_lama($id = false){
			$this->model('ProyekModel');
			$data_nama_proyek = (!$id) ? $this->ProyekModel->getAll() : $this->ProyekModel->getById($id);
			
			$data = array();

			if(!$id){
				foreach($data_nama_proyek as $row){
					$dataRow = array();
					$dataRow['id'] = $row['id'];
					$dataRow['text'] = $row['id'].' - '.$row['pembangunan'];

					$data[] = $dataRow;
				}
			}
			else{
				$data[] = array(
					'id' => $data_nama_proyek['id'],
					 'text' => $data_nama_proyek['id'].' - '.$data_nama_proyek['pembangunan']
					);
			}

			echo json_encode($data);

			// var_dump($data);
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
		 * Method get_bank
		 * Proses get data bank yang aktif untuk keperluan select
		 * @return data {object} array berupa json
		 */
		public function get_nama_bank(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$data_bank = $this->Operasional_proyekModel->get_selectBank();
				$data = array();

				foreach($data_bank as $row){
					$dataRow = array();
					$dataRow['id'] = $row['id'];
					$dataRow['text'] = $row['nama'];

					$data[] = $dataRow;
				}

				echo json_encode($data);
			}
			else { $this->redirect(); }
		}
		

		/**
		*
		*/
		public function get_nama_kas_besar($id = false){
			$this->model('Kas_besarModel');
			$data_kas_besar = (!$id) ? $this->Kas_besarModel->getAll() : $this->Kas_besarModel->getById($id);
			$data = array();

			if(!$id){
				foreach ($data_kas_besar as $row) {
					$dataRow = array();
					$dataRow['id'] = $row['id'];
					$dataRow['text'] = $row['id'].' - '.$row['nama'];

					$data[] = $dataRow;
				}
			}
			else{
				$data[] = array(
					'id' => $data_kas_besar['id'],
					 'text' => $data_kas_besar['id'].' - '.$data_kas_besar['nama']
					);
			}
				

			echo json_encode($data);
		}
		/**
		*
		*/
		public function get_nama_distributor(){
			$this->model('DistributorModel');
			$data_distributor = $this->DistributorModel->getAll();
			$data = array();

			foreach ($data_distributor as $row) {
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
		public function action_add_detail(){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$data = isset($_POST) ? $_POST : false;

				$validasi = $this->set_validation_detail($data);
				$cek = $validasi['cek'];
				$this->error = $validasi['error'];

				if($cek) {
					$this->success = true;
					$data['tgl_detail_full'] = $this->helper->cetakTgl($data['tgl_detail'], 'full');
					$data['total_detail_full'] = $this->helper->cetakRupiah($data['total_detail']);
				}

				$output = array(
					'status' => $this->success,
					'error' => $this->error,
					'data' => $data,
				);
				echo json_encode($output);
			}
		}

		/**
		*
		*/
		public function get_list_history_pembelian($id){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$config_dataTable = array(
					'tabel' => 'v_history_pembelian_operasionalproyek',
					'kolomOrder' => array('id', 'tgl', 'nama', 'total', 'status_lunas', 'ID_DISTRIBUTOR', 'NAMA_DISTRIBUTOR','pemilik'),
					'kolomCari' => array('id', 'tgl', 'nama', 'total', 'status_lunas', 'ID_DISTRIBUTOR', 'NAMA_DISTRIBUTOR','pemilik'),
					'orderBy' => array('id' => 'desc'),
					'kondisi' => 'where id = "'.$id.'"',
				);

				$dataHistoryPembelanjaan = $this->Operasional_proyekModel->getAllDataTable($config_dataTable);

				$data = array();
				// $no_urut = $_POST['start'];
				foreach($dataHistoryPembelanjaan as $row){
					// $no_urut++;

					// $status = (strtolower($row['status']) == "selesai") ? '<span class="label label-success">'.$row['status'].'</span>' : '<span class="label label-primary">'.$row['status'].'</span>';

					// if($row['progress'] == 100)
					// 	$progress = '<span class="label label-success">'.$row['progress'].' %</span>';
					// else if($row['progress'] >= 50  && $row['progress'] < 100)
					// 	$progress = '<span class="label label-primary">'.$row['progress'].' %</span>';
					// else if($row['progress'] >= 20 && $row['progress'] < 50)
					// 	$progress = '<span class="label label-warning">'.$row['progress'].' %</span>';
					// else if($row['progress'] < 20)
					// 	$progress = '<span class="label label-danger">'.$row['progress'].' %</span>';

					// button aksi
					// $aksiDetail = '<button onclick="getView('."'".strtolower($row["id"])."'".')" type="button" class="btn btn-sm btn-info btn-flat" title="Lihat Detail"><i class="fa fa-eye"></i></button>';
					// $aksiEdit = '<button onclick="getEdit('."'".strtolower($row["id"])."'".')" type="button" class="btn btn-sm btn-success btn-flat" title="Edit Data"><i class="fa fa-pencil"></i></button>';
					// $aksiHapus = '<button onclick="getDelete('."'".strtolower($row["id"])."'".')" type="button" class="btn btn-sm btn-danger btn-flat" title="Hapus Data"><i class="fa fa-trash"></i></button>';
					
					
					$dataRow = array();
					// $dataRow[] = $no_urut;
					$dataRow[] = $row['id'];
					$dataRow[] = $row['tgl'];
					$dataRow[] = $row['nama'];
					$dataRow[] = $row['total'];
					$dataRow[] = $row['status_lunas'];
					$dataRow[] = $row['ID_DISTRIBUTOR'];
					$dataRow[] = $row['NAMA_DISTRIBUTOR'];
					$dataRow[] = $row['pemilik'];

					$data[] = $dataRow;
				}

				$output = array(
					'draw' => $_POST['draw'],
					'recordsTotal' => $this->Operasional_proyekModel->recordTotal(),
					'recordsFiltered' => $this->Operasional_proyekModel->recordFilter(),
					'data' => $data,
				);

				echo json_encode($output);
			}
			else{ $this->redirect(); }
		}

		/**
		*
		*/
		public function get_detail_operasional_proyek($id){
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$config_dataTable = array(
					'tabel' => 'v_operasional_proyek',
					'kolomOrder' => array('id_bank', 'nama_detail', 'tgl_detail','total_detail'),
					'kolomCari' => array('id_bank', 'nama_detail', 'tgl_detail','total_detail'),
					'orderBy' => array('id_bank' => 'asc'),
					'kondisi' => 'where id = "'.$id.'"',
				);

				$dataDetailOperasionalProyek = $this->Operasional_proyekModel->getAllDataTable($config_dataTable);

				$data = array();
				// $no_urut = $_POST['start'];
				foreach($dataDetailOperasionalProyek as $row){
					// $no_urut++;
					
					$dataRow = array();
					// $dataRow[] = $no_urut;
					$dataRow[] = $row['id_bank'];					
					$dataRow[] = $row['nama_detail'];
					$dataRow[] = $row['tgl_detail'];
					$dataRow[] = $row['total_detail'];

					$data[] = $dataRow;
				}

				$output = array(
					'draw' => $_POST['draw'],
					'recordsTotal' => $this->Operasional_proyekModel->recordTotal(),
					'recordsFiltered' => $this->Operasional_proyekModel->recordFilter(),
					'data' => $data,
				);

				echo json_encode($output);
			}
			else{ $this->redirect(); }
		}

		/**
		* Function validasi form utama
		*/
		private function set_validation($data, $action){
			// $required = ($action =="action-add") ? 'not_required' : 'required';

			if($action == "action-add"){
			
				// id
				$this->validation->set_rules($data['id'], 'ID Operasional Proyek', 'id', 'string | 1 | 255 | required');
				// id_proyek
				$this->validation->set_rules($data['id_proyek'], 'ID proyek', 'id_proyek', 'string | 1 | 255 | required');
				// id_distributor
				$this->validation->set_rules($data['id_distributor'], 'ID Distributor', 'id_distributor', 'string | 1 | 255 | required');
				// tgl
				$this->validation->set_rules($data['tgl'], 'Tanggal Operasional Proyek', 'tgl', 'string | 1 | 255 | required');
				// nama
				$this->validation->set_rules($data['nama'], 'Nama Pengajuan', 'nama', 'string | 1 | 255 | required');
				// jenis
				$this->validation->set_rules($data['jenis'], 'Jenis Pengajuan', 'jenis', 'string | 1 | 255 | required');
				// total
				$this->validation->set_rules($data['total'], 'Total Pengajuan', 'total', 'nilai | 1 | 9999999 | required');
				// sisa
				$this->validation->set_rules($data['sisa'], 'Sisa Pengajuan', 'sisa', 'nilai | 0 | 9999999 | required');
				// status
				$this->validation->set_rules($data['jenis'], 'Status Pengajuan', 'status', 'string | 1 | 255 | required');
				// status lunas
				$this->validation->set_rules($data['status_lunas'], 'Status Lunas Pengajuan', 'status_lunas', 'string | 1 | 255 | required');
				// keterangan
				$this->validation->set_rules($data['ket'], 'Keterangan Pengajuan', 'ket', 'string | 1 | 255 | required');

				return $this->validation->run();
			
			} else if($action == "action-edit"){

				// id
				$this->validation->set_rules($data['id'], 'ID Operasional Proyek', 'id', 'string | 1 | 255 | required');
				// id_proyek
				$this->validation->set_rules($data['id_proyek'], 'ID proyek', 'id_proyek_f', 'string | 1 | 255 | required');
				// id_distributor
				$this->validation->set_rules($data['id_distributor'], 'ID Distributor', 'id_distributor_f', 'string | 1 | 255 | required');
				// tgl
				$this->validation->set_rules($data['tgl'], 'Tanggal Operasional Proyek', 'tgl', 'string | 1 | 255 | required');
				// nama
				$this->validation->set_rules($data['nama'], 'Nama Pengajuan', 'nama', 'string | 1 | 255 | required');
				// jenis
				$this->validation->set_rules($data['jenis'], 'Jenis Pengajuan', 'jenis', 'string | 1 | 255 | required');
				// total
				$this->validation->set_rules($data['total'], 'Total Pengajuan', 'total', 'nilai | 1 | 9999999 | required');
				// sisa
				$this->validation->set_rules($data['sisa'], 'Sisa Pengajuan', 'sisa', 'nilai | 0 | 9999999 | required');
				// status
				$this->validation->set_rules($data['jenis'], 'Status Pengajuan', 'status', 'string | 1 | 255 | required');
				// status lunas
				$this->validation->set_rules($data['status_lunas'], 'Status Lunas Pengajuan', 'status_lunas', 'string | 1 | 255 | required');
				// keterangan
				$this->validation->set_rules($data['ket'], 'Keterangan Pengajuan', 'ket', 'string | 1 | 255 | required');

				return $this->validation->run();

			}

		
		

			
			
			
		}

		/**
		* Function validasi form detail
		*/
		private function set_validation_detail($data){
			//id_bank
			$this->validation->set_rules($data['id_bank'], 'ID Bank', 'id_bank', 'string | 1 | 255 | required');
			// nama
			$this->validation->set_rules($data['nama_detail'], 'Nama Kebutuhan', 'nama_detail', 'string | 1 | 255 | required');
			// tgl
			$this->validation->set_rules($data['tgl_detail'], 'Tanggal Operasional', 'tgl_detail', 'string | 1 | 255 | required');
			// total
			$this->validation->set_rules($data['total_detail'], 'Total', 'total_detail', 'nilai | 1 | 9999999999 | required');
			
			return $this->validation->run();
		}

	}
