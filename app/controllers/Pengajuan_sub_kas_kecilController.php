<?php
	Defined("BASE_PATH") or die(ACCESS_DENIED);

	/**
	 * Class Pengajuan_sub_kas_kecil
	 */
	class Pengajuan_sub_kas_kecil extends Controller 
	{

		private $success = false;
		private $notif = array();
		private $error = array();
		private $message = NULL;

		/**
		 * Method __construct
		 * Default load saat pertama kali controller diakses
		 */
		public function __construct() {
			$this->auth();
			$this->auth->cekAuth();
			$this->model('Pengajuan_sub_kas_kecilModel');
			$this->model('DataTableModel');
			$this->helper();
			$this->validation();
		}

		/**
		 * Method index
		 * Render list pengajuan sub kas kecil
		 */
		public function index() {
			$this->list();
		}

		/**
		 * Method list
		 * Proses menampilkan list semua data pengajuan sub kas kecil
		 */
		private function list() {
			$css = array(
				'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
				'assets/bower_components/select2/dist/css/select2.min.css',
				'assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
			);
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js',
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'assets/plugins/input-mask/jquery.inputmask.bundle.js',
				'assets/bower_components/select2/dist/js/select2.full.min.js',
				'assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
				'assets/bower_components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.id.min.js',
				'assets/js/library/export.js',
				'app/views/form_export/js/initFormStartEndDate.js',
				'app/views/pengajuan_sub_kas_kecil/js/initList.js',
				'app/views/pengajuan_sub_kas_kecil/js/initForm.js',
			);

			$config = array(
				'title' => 'Menu Pengajuan Sub Kas Kecil',
				'property' => array(
					'main' => 'Data Pengajuan Sub Kas Kecil',
					'sub' => 'List Semua Data Pengajuan Sub Kas Kecil',
				),
				'css' => $css,
				'js' => $js,
			);

			$this->layout('pengajuan_sub_kas_kecil/list', $config, $data = null);
		}

		/**
		 * Method get_list
		 * Proses get data untuk list pengajuan sub kas kecil
		 * Data akan di parsing dalam bentuk dataTable
		 * @return output {object} array berupa json
		 */
		public function get_list() {
			if($_SERVER['REQUEST_METHOD'] == "POST") {
				// config datatable
				$config = array(
					'tabel' => 'v_pengajuan_sub_kas_kecil_v2',
					'kolomOrder' => array(null, 'id', 'tgl', 'id_sub_kas_kecil', 'id_proyek', 'nama_pengajuan', 'total', 'dana_disetujui', 'status', null),
					'kolomCari' => array('id', 'id_sub_kas_kecil', 'nama_skk', 'id_proyek', 'pemilik', 'pembangunan', 'tgl', 'total', 'dana_disetujui', 'status'),
					'orderBy' => array('status_order' => 'ASC', 'id' => 'desc'),
					'kondisi' => false,
				);

				$dataPengajuan = $this->DataTableModel->getAllDataTable($config);

				$data = array();
				$no_urut = $_POST['start'];
				foreach($dataPengajuan as $row){
					$no_urut++;

					// button aksi
					$aksiDetail = '<button onclick="getView('."'".strtolower($row["id"])."'".')" type="button" class="btn btn-sm btn-info btn-flat" title="Lihat Detail"><i class="fa fa-eye"></i></button>';
					$aksiEdit = '<button onclick="getEdit('."'".strtolower($row["id"])."'".')" type="button" class="btn btn-sm btn-success btn-flat" title="Edit Status Pengajuan"><i class="fa fa-pencil"></i></button>';
					$aksiHapus = '<button onclick="getDelete('."'".strtolower($row["id"])."'".')" type="button" class="btn btn-sm btn-danger btn-flat" title="Hapus Data"><i class="fa fa-trash"></i></button>';
					$aksi = '<div class="btn-group">'.$aksiDetail.$aksiEdit.$aksiHapus.'</div>';

					switch ($row['status_order']) {
						case '1': // pending
							$status = '<span class="label label-primary">';
							break;

						case '2': // perbaiki
							$aksi = '<div class="btn-group">'.$aksiDetail.'</div>';
							$status = '<span class="label label-warning">';
							break;
						
						case '3': // disetujui
						case '4': // langsung
							$aksi = '<div class="btn-group">'.$aksiDetail.'</div>';
							$status = '<span class="label label-success">';
							break;

						default: // ditolak
							$aksi = '<div class="btn-group">'.$aksiDetail.$aksiHapus.'</div>';
							$status = '<span class="label label-danger">';
					}
					$status .= $row['status'].'</span>';

					$dataRow = array();
					$dataRow[] = $no_urut;
					$dataRow[] = $row['id'];
					$dataRow[] = $this->helper->cetakTgl($row['tgl'], 'full');
					$dataRow[] = $row['id_sub_kas_kecil'].' - '.$row['nama_skk'];
					$dataRow[] = $row['id_proyek'];
					$dataRow[] = $row['nama_pengajuan'];
					$dataRow[] = $this->helper->cetakRupiah($row['total']);
					$dataRow[] = $this->helper->cetakRupiah($row['dana_disetujui']);
					$dataRow[] = $status;
					$dataRow[] = $aksi;

					$data[] = $dataRow;
				}

				$output = array(
					'draw' => $_POST['draw'],
					'recordsTotal' => $this->DataTableModel->recordTotal(),
					'recordsFiltered' => $this->DataTableModel->recordFilter(),
					'data' => $data,
				);

				echo json_encode($output);
			}
			else { $this->redirect(); }
		}

		/**
		 * Method edit
		 * Proses get data edit pengajuan sub kas kecil
		 * @param id {string}
		 * @param output {object}
		 */
		public function edit($id) {
			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				$id = strtoupper($id);
				if(empty($id) || $id == "") { $this->redirect(BASE_URL."pengajuan-sub-kas-kecil"); }
				
				// get data pengajuan dan saldo sub kas kecil
				$this->model('Sub_kas_kecilModel');
				$this->model('Kas_kecilModel');
				
				$dataPengajuan = $this->Pengajuan_sub_kas_kecilModel->getById($id);
				$dataEstimasiPengajuan = (!empty($this->Pengajuan_sub_kas_kecilModel->
											getEstimasiPengajuan_byId($dataPengajuan['id_sub_kas_kecil'])['estimasi_pengeluaran_saldo'])) ?
										$this->Pengajuan_sub_kas_kecilModel->
											getEstimasiPengajuan_byId($dataPengajuan['id_sub_kas_kecil'])['estimasi_pengeluaran_saldo'] : 0;
				
				// die(var_dump($dataEstimasiPengajuan));

				$dataSaldoSkk = $this->Sub_kas_kecilModel->getById($dataPengajuan['id_sub_kas_kecil']);
				$dataSaldoKK = $this->Kas_kecilModel->getById($_SESSION['sess_id']);
				
				$dataPengajuan['saldo_sub_kas_kecil'] = $dataSaldoSkk['saldo'];
				$dataPengajuan['sisa_saldo_sub_kas_kecil'] = $dataSaldoSkk['saldo'] - $dataEstimasiPengajuan;
				$dataPengajuan['saldo_kas_kecil'] = $dataSaldoKK['saldo'];
				
				$dataPengajuan['total_pengajuan'] = $dataPengajuan['total'] - ($dataSaldoSkk['saldo'] - $dataEstimasiPengajuan); 
				$dataPengajuan['dana_disetujui'] = $dataPengajuan['total_pengajuan'];

				$output = array(
					'data' => $dataPengajuan
				);

				echo json_encode($output);
			}
			else { $this->redirect(); }
		}

		/**
		 * Method get_edit
		 * Proses get data detail proyek dan detail skk untuk keperluan edit proyek
		 * @param id {string}
		 * @return output {object} array berupa json
		 */
		public function action_edit() {
			if($_SERVER['REQUEST_METHOD'] == "POST") {
				$data = isset($_POST) ? $_POST : false;
				
				if(!$data) {
					$this->notif = array(
						'type' => 'error',
						'title' => "Pesan Gagal",
						'message' => "Terjadi Kesalahan Teknis, Silahkan Coba Kembali",
					);
				}
				else { 
					// validasi data
					$validasi = $this->set_validation($data);
					$cek = $validasi['cek'];
					$this->error = $validasi['error'];

					if($cek) {
						// mendapatkan id sub kas kecil (untuk kirim notif ke android)
						$id_skk = trim(explode("-", $this->validation->validInput($data['id_skk']))[0]);

						// status disetujui
						if($data['status'] == '3') {
	
							$data = array(
								'id' => $this->validation->validInput($data['id']),
								'id_kas_kecil' => $_SESSION['sess_id'],
								'tgl' => date('Y-m-d'),
								'dana_disetujui' => $this->validation->validInput($data['dana_disetujui']),
								'status' => $this->validation->validInput($data['status']),
								'modified_by' => $_SESSION['sess_email']
							);
	
							$this->model('Kas_kecilModel');
							$getSaldo = $this->Kas_kecilModel->getById($_SESSION['sess_id'])['saldo'];
	
							if($data['dana_disetujui'] > $getSaldo) {
								$this->error['dana_disetujui'] = "Dana yang Disetujui terlalu besar dan melebihi saldo";
							}
							else {
								// update status
								$acc_pengajuan = $this->Pengajuan_sub_kas_kecilModel->acc_pengajuan($data);
								if($acc_pengajuan['success']) {
									$this->success = true;

									// KIRIM NOTIF KE ANDROID
									$this->helper->sendNotif(array(
										'show' => "1",
										'id_skk' => $id_skk,
										'title' => "Pengajuan Telah Disetujui",
										'body' => "Pengajuan ".$data['id']." telah disetujui.",
										'refresh' => "2,1,4"
									));

									$this->notif = array(
										'type' => 'success',
										'title' => "Pesan Berhasil",
										'message' => "Edit Status Pengajuan Sub Kas Kecil Berhasil",
									);
								}
								else {
									$this->notif = array(
										'type' => 'error',
										'title' => "Pesan Gagal",
										'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
									);
									$this->message = $acc_pengajuan['error'];
								}
							}
						}
						else { // status selain disetujui
							$data = array(
								'id' => $this->validation->validInput($data['id']),
								'status' => $this->validation->validInput($data['status']),
								'ket' => $this->validation->validInput($data['ket']),
								'modified_by' => $_SESSION['sess_email']
							);
	
							// update status
							$update = $this->Pengajuan_sub_kas_kecilModel->update_status($data);
							if($update['success']) {
								$this->success = true;

								// KIRIM NOTIF KE ANDROID
								$status_pengajuan = $this->helper->getNamaStatusPengajuanSKK($data['status']);
								$body = ($data['status'] == "5") ? "Pengajuan ".$data['id']." ditolak karena ".$data['ket'] : "Pengajuan ".$data['id']." ".$status_pengajuan.".";
								$this->helper->sendNotif(array(
									'show' => "1",
									'id_skk' => $id_skk,
									'title' => "Pengajuan ".$status_pengajuan,
									'body' => $body,
									'refresh' => "1"
								));

								$this->notif = array(
									'type' => 'success',
									'title' => "Pesan Berhasil",
									'message' => "Edit Status Pengajuan Sub Kas Kecil Berhasil",
								);
							}
							else {
								$this->notif = array(
									'type' => 'error',
									'title' => "Pesan Gagal",
									'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
								);
								$this->message = $update['error'];
							}
						}
					}
					else {
						$this->notif = array(
							'type' => 'warning',
							'title' => "Pesan Pemberitahuan",
							'message' => "Silahkan Cek Kembali Form Isian",
						);
					}
				}

				$output = array(
					'success' => $this->success,
					'notif' => $this->notif,
					'error' => $this->error,
					'message' => $this->message,
					'data' => $data
				);
	
				echo json_encode($output);
			}
			else { $this->redirect(); }
		}

		/**
		 * 
		 */
		public function detail($id ){
			$id = strtoupper($id);
			$dataPengajuan = !empty($this->Pengajuan_sub_kas_kecilModel->getById($id)) ? $this->Pengajuan_sub_kas_kecilModel->getById($id) : false;

			if((empty($id) || $id == "") || !$dataPengajuan) { $this->redirect(BASE_URL."pengajuan-sub-kas-kecil/"); }

			$cekAction = false;

			if($dataPengajuan['status_order'] == '1' ) { $cekAction = true; }

			$css = array(
				'assets/bower_components/select2/dist/css/select2.min.css',
				'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css'
			);
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'assets/plugins/input-mask/jquery.inputmask.bundle.js',
				'assets/bower_components/select2/dist/js/select2.full.min.js',
				'app/views/pengajuan_sub_kas_kecil/js/initView.js',
			);

			$config = array(
				'title' => 'Menu Pengajuan Sub Kas Kecil',
				'property' => array(
					'main' => 'Data Pengajuan Sub Kas Kecil',
					'sub' => 'Detail Data Pengajuan',
				),
				'css' => $css,
				'js' => $js,
			);
			
			switch ($dataPengajuan['status_order']) {
				case '1': // pending
					$status = '<span class="label label-primary">';
					break;

				case '2': // perbaiki
					$status = '<span class="label label-warning">';
					break;
				
				case '3': // disetujui
				case '4': // langsung
					$status = '<span class="label label-success">';
					break;

				default: // ditolak
					$status = '<span class="label label-danger">';
			}
			$status .= $dataPengajuan['status'].'</span>';

			$parsing_dataPengajuan = array(
				'id' => $dataPengajuan['id'],
				'skk' => $dataPengajuan['id_sub_kas_kecil'].' - '.$dataPengajuan['nama_skk'],
				'tgl' => $this->helper->cetakTgl($dataPengajuan['tgl'], 'full'),
				'id_proyek' => $dataPengajuan['id_proyek'].' - '.$dataPengajuan['pemilik'],
				'nama_pengajuan' => $dataPengajuan['nama_pengajuan'],
				'total' => $this->helper->cetakRupiah($dataPengajuan['total']),
				'dana_disetujui' => $this->helper->cetakRupiah($dataPengajuan['dana_disetujui']),
				'status' => $status
			);

			$dataDetail = !empty($this->Pengajuan_sub_kas_kecilModel->getDetailById($id)) ? 
				$this->Pengajuan_sub_kas_kecilModel->getDetailById($id) : false;
			
			$parsing_dataDetail = array();
			if($dataDetail) {
				$no_urut = 0;
				foreach($dataDetail as $row) {
					$dataRow = array();

					$no_urut++;
					$dataRow['no_urut'] = $no_urut;
					$dataRow['nama'] = $row['nama'];
					$dataRow['jenis'] = $row['jenis'];
					$dataRow['satuan'] = $row['satuan'];
					$dataRow['qty'] = $row['qty'];
					$dataRow['harga'] = $this->helper->cetakRupiah($row['harga']);
					$dataRow['subtotal'] = $this->helper->cetakRupiah($row['subtotal']);
					
					$parsing_dataDetail[] = $dataRow;
				}
			}

			$data = array(
				'data_pengajuan' => $parsing_dataPengajuan,
				'data_detail' => $parsing_dataDetail,
				'action' => $cekAction
			);

			$this->layout('pengajuan_sub_kas_kecil/view', $config, $data);
			// $this->view('pengajuan_sub_kas_kecil/view', $data);
		}

		/**
		 * 
		 */
		public function delete($id) {

		}

		/**
		 * 
		 */
		public function export() {
			
		}

		/**
		 * 
		 */
		public function export_detail() {

		}

		/**
		 * 
		 */
		public function get_notif() {
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
		private function set_validation($data) {
			$required = ($data['status'] == "1") ? 'required' : 'not_required';

			// status
			$this->validation->set_rules($data['status'], 'Status Pengajuan Sub Kas Kecil', 'status', 'string | 1 | 1 | required');
			// dana_disetujui
			$this->validation->set_rules($data['dana_disetujui'], 'Dana yang Disetujui', 'dana_disetujui', 'nilai | 1 | 99999999999 | '.$required);

			return $this->validation->run();
		}

	}