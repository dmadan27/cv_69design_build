<?php
	Defined("BASE_PATH") or die(ACCESS_DENIED);

	/**
	 * 
	 */
	class Laporan_sub_kas_kecil extends Controller {

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
            $this->model('DataTableModel');
			$this->model('Laporan_sub_kas_kecilModel');
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
				'assets/bower_components/select2/dist/css/select2.min.css'
			);
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js',
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'assets/plugins/input-mask/jquery.inputmask.bundle.js',
				'assets/bower_components/select2/dist/js/select2.full.min.js',
				'app/views/laporan_sub_kas_kecil/js/initList.js',
				'app/views/laporan_sub_kas_kecil/js/initForm.js',
			);

			$config = array(
				'title' => array(
					'main' => 'Data Laporan Pengajuan Sub Kas Kecil',
					'sub' => 'List Semua Data Laporan',
				),
				'css' => $css,
				'js' => $js,
			);

			$this->layout('laporan_sub_kas_kecil/list', $config, $data = null);
		}

		/**
		 * Method get_list
		 * Proses get data untuk list laporan pengajuan sub kas kecil
		 * Data akan di parsing dalam bentuk dataTable
		 * @return output {object} array berupa json
		 */
		public function get_list(){
			if($_SERVER['REQUEST_METHOD'] == "POST") {
				// config datatable
				$config_dataTable = array(
					'tabel' => 'v_laporan_pengajuan_sub_kas_kecil',
					'kolomOrder' => array(null, 'id', 'tgl', 'id_sub_kas_kecil', 'id_proyek', 'nama_pengajuan', 'total', 'total_asli', 'status_order', null),
					'kolomCari' => array('id', 'id_sub_kas_kecil', 'nama_skk', 'id_proyek', 'pemilik', 'pembangunan', 'tgl', 'total', 'total_asli', 'status_laporan'),
					'orderBy' => array('status_order' => 'ASC', 'id' => 'desc'),
					'kondisi' => "WHERE status_order IS NOT NULL",
				);

				$dataLaporan = $this->DataTableModel->getAllDataTable($config_dataTable);

				$data = array();
				$no_urut = $_POST['start'];
				foreach($dataLaporan as $row){
					$no_urut++;

					$base64_edit = rtrim(strtr(base64_encode($row["id"].' EDIT'), '+/', '-_'), '=');

					// button aksi
					$aksiDetail = '<button onclick="getEdit('."'".strtolower($row["id"])."'".')" type="button" class="btn btn-sm btn-info btn-flat" title="Lihat Detail"><i class="fa fa-eye"></i></button>';
					$aksiEdit = '<button onclick="getEdit('."'".strtolower($row["id"])."'".', '."'".$base64_edit."'".')" type="button" class="btn btn-sm btn-success btn-flat" title="Edit Status Pengajuan"><i class="fa fa-pencil"></i></button>';
					$aksi = '<div class="btn-group">'.$aksiDetail.$aksiEdit.'</div>';

					switch ($row['status_order']) {
						case '1': // pending
							$status = '<span class="label label-primary">';
							break;

						case '2': // perbaiki
							$aksi = '<div class="btn-group">'.$aksiDetail.'</div>';
							$status = '<span class="label label-warning">';
							break;
						
						case '3': // disetujui
							$aksi = '<div class="btn-group">'.$aksiDetail.'</div>';
							$status = '<span class="label label-success">';
							break;

						default: // ditolak
							$aksi = '<div class="btn-group">'.$aksiDetail.'</div>';
							$status = '<span class="label label-danger">';
					}
					$status .= $row['status_laporan'].'</span>';

					$dataRow = array();
					$dataRow[] = $no_urut;
					$dataRow[] = $row['id'];
					$dataRow[] = (!empty($row['tgl'])) ? $this->helper->cetakTgl($row['tgl'], 'full') : '-';
					$dataRow[] = $row['id_sub_kas_kecil'].' - '.$row['nama_skk'];
					$dataRow[] = $row['id_proyek'];
					$dataRow[] = $row['nama_pengajuan'];
					$dataRow[] = $this->helper->cetakRupiah($row['total']);
					$dataRow[] = $this->helper->cetakRupiah($row['total_asli']);
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
		 * 
		 */
		public function edit($id){
			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				$id = strtoupper($id);
				if(empty($id) || $id == "") { $this->redirect(BASE_URL."laporan-sub-kas-kecil"); }
				
				
				$dataPengajuan = $this->Pengajuan_sub_kas_kecilModel->getById($id);
				$dataSaldoSkc = $this->Sub_kas_kecilModel->getSaldoById($dataPengajuan['id_sub_kas_kecil']);
				$dataPengajuan['saldo'] = $dataSaldoSkc['saldo'];

				$output = array(
					'data' => $dataPengajuan
				);

				echo json_encode($output);
			}
			else { $this->redirect(); }
		}

		/**
		 * 
		 */
		public function action_edit(){
			if($_SERVER['REQUEST_METHOD'] == "POST") {
				$data = isset($_POST) ? $_POST : false;
				
				if(!$data){
					$this->notif = array(
						'type' => 'error',
						'title' => "Pesan Gagal",
						'message' => "Terjadi Kesalahan Teknis, Silahkan Coba Kembali",
					);
				}
				else{ 
					// validasi data
					$validasi = $this->set_validation($data);
					$cek = $validasi['cek'];
					$this->error = $validasi['error'];

					if($cek){
						// status disetujui, ditolak, atau masih pending
						if($data['status_laporan'] != '2' && $data['status_laporan'] != '0') {
							$data_update = array(
								'id' => $this->validation->validInput($data['id']),
								'status_laporan' => $this->validation->validInput($data['status_laporan'])
							);
	
							// update status
							$update_laporan = $this->Laporan_sub_kas_kecilModel->update_laporan($data_update);
							if($update_laporan['success']) {
								$this->success = true;
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
								$this->message = $update_laporan['error'];
							}
						}
						else if($data['status_laporan'] == '2' && $data['status_laporan'] != '0') { // diperbaiki
							$data_update = array(
								'id' => $this->validation->validInput($data['id']),
								'id_sub_kas_kecil' => $this->validation->validInput($data['id_sub_kas_kecil'])
							);
	
							// update status
							$update_laporan = $this->Laporan_sub_kas_kecilModel->perbaiki_laporan($data_update);
							if($update_laporan['success']) {
								$this->success = true;
								$this->notif = array(
									'type' => 'success',
									'title' => "Pesan Berhasil",
									'message' => "Edit Status Pengajuan Sub Kas Kecil Berhasil",
								);
							}
							else{
								$this->notif = array(
									'type' => 'error',
									'title' => "Pesan Gagal",
									'message' => "Terjadi Kesalahan Sistem, Silahkan Coba Lagi",
								);
								$this->message = $update_laporan['error'];
							}
						}
					}
					else{
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
		public function detail($id){
			$id = strtoupper($id);
			$dataLaporan = !empty($this->Laporan_sub_kas_kecilModel->getById($id)) ? 
				$this->Laporan_sub_kas_kecilModel->getById($id) : false;

			if((empty($id) || $id == "") || !$dataLaporan) { $this->redirect(BASE_URL."laporan-sub-kas-kecil/"); }

			$cekAction = false;
			$action = (isset($_GET['action']) && $_GET['action'] != '') ? $_GET['action'] : false;
			if($action) {
				$cek = base64_decode(str_pad(strtr($action, '-_', '+/'), strlen($action) % 4, '=', STR_PAD_RIGHT));
				$veriry = $id.' EDIT';
				if($cek === $veriry) { $cekAction = true; }
			}

			$css = array(
				'assets/bower_components/Magnific-Popup-master/dist/magnific-popup.css',
				'assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css'
			);
			$js = array(
				'assets/bower_components/Magnific-Popup-master/dist/jquery.magnific-popup.min.js',
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'app/views/laporan_sub_kas_kecil/js/initView.js',
			);

			$config = array(
				'title' => array(
					'main' => 'Data Laporan Pengajuan Sub Kas Kecil',
					'sub' => 'Detail Data Laporan',
				),
				'css' => $css,
				'js' => $js,
			);

			switch ($dataLaporan['status_order']) {
				case '1': // pending
					$status = '<span class="label label-primary">';
					break;

				case '2': // perbaiki
					$status = '<span class="label label-warning">';
					break;
				
				case '3': // disetujui
					$status = '<span class="label label-success">';
					break;

				default: // ditolak
					$status = '<span class="label label-danger">';
			}
			$status .= $dataLaporan['status_laporan'].'</span>';

			$parsing_dataLaporan = array(
				'id' => $dataLaporan['id'],
				'skk' => $dataLaporan['id_sub_kas_kecil'].' - '.$dataLaporan['nama_skk'],
				'tgl' => (!empty($dataLaporan['tgl'])) ? $this->helper->cetakTgl($dataLaporan['tgl'], 'full') : '-',
				'id_proyek' => $dataLaporan['id_proyek'].' - '.$dataLaporan['pemilik'],
				'nama_pengajuan' => $dataLaporan['nama_pengajuan'],
				'total' => $this->helper->cetakRupiah($dataLaporan['total']),
				'total_asli' => $this->helper->cetakRupiah($dataLaporan['total_asli']),
				'status' => $status
			);

			$dataDetail = !empty($this->Laporan_sub_kas_kecilModel->getDetailById($id)) ? 
				$this->Laporan_sub_kas_kecilModel->getDetailById($id) : false;
			$dataBuktiLaporan = !empty($this->Laporan_sub_kas_kecilModel->getBuktiLaporanById($id)) ? 
				$this->Laporan_sub_kas_kecilModel->getBuktiLaporanById($id) : false;

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
					$dataRow['subtotal_asli'] = $this->helper->cetakRupiah($row['harga_asli']);
					$dataRow['sisa'] = $this->helper->cetakRupiah($row['sisa']);
					
					$parsing_dataDetail[] = $dataRow;
				}
			}

			$data = array(
				'data_laporan' => $parsing_dataLaporan,
				'data_detail' => $parsing_dataDetail,
				'data_bukti_laporan' => $dataBuktiLaporan,
				'action' => $cekAction
			);

			$this->layout('laporan_sub_kas_kecil/view', $config, $data);
			// $this->view('laporan_sub_kas_kecil/view', $data);
		}

		/**
		 * 
		 */
		public function delete($id){

		}

		/**
		 * 
		 */
		public function export(){
			
		}

		/**
		 * 
		 */
		public function export_detail(){

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
			$this->validation->set_rules($data['status_laporan'], 'Status Laporan Pengajuan Sub Kas Kecil', 'status_laporan', 'string | 1 | 1 | required');

			return $this->validation->run();
		}

	}
