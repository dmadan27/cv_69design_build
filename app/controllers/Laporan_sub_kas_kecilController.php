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
				'app/views/pengajuan_sub_kas_kecil/js/initList.js',
				'app/views/pengajuan_sub_kas_kecil/js/initForm.js',
			);

			$config = array(
				'title' => array(
					'main' => 'Data Laporan Pengajuan Sub Kas Kecil',
					'sub' => 'List Semua Data Laporan Pengajuan Sub Kas Kecil',
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
					'tabel' => 'v_pengajuan_sub_kas_kecil_v2',
					'kolomOrder' => array(null, 'id', 'tgl_laporan', 'id_sub_kas_kecil', 'id_proyek', 'nama_pengajuan', 'total', 'dana_disetujui', 'status_laporan', null),
					'kolomCari' => array('id', 'id_sub_kas_kecil', 'nama_skk', 'id_proyek', 'pemilik', 'pembangunan', 'tgl_laporan', 'total', 'dana_disetujui', 'status_laporan'),
					'orderBy' => array('status_laporan_order' => 'ASC', 'id' => 'desc'),
					'kondisi' => "WHERE status_order = '3'",
				);

				$dataLaporan = $this->DataTableModel->getAllDataTable($config_dataTable);

				$data = array();
				$no_urut = $_POST['start'];
				foreach($dataLaporan as $row){
					$no_urut++;

					// button aksi
					$aksiDetail = '<button onclick="getEdit('."'".strtolower($row["id"])."'".')" type="button" class="btn btn-sm btn-info btn-flat" title="Lihat Detail"><i class="fa fa-eye"></i></button>';
					$aksiEdit = '<button onclick="getEdit('."'".strtolower($row["id"])."'".')" type="button" class="btn btn-sm btn-success btn-flat" title="Edit Status Pengajuan"><i class="fa fa-pencil"></i></button>';
					$aksi = '<div class="btn-group">'.$aksiDetail.$aksiEdit.'</div>';

					switch ($row['status_laporan_order']) {
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
							$aksi = '';
							$status = '<span class="label label-danger">';
					}
					$status .= $row['status_laporan'].'</span>';

					$dataRow = array();
					$dataRow[] = $no_urut;
					$dataRow[] = $row['id'];
					$dataRow[] = $this->helper->cetakTgl($row['tgl_laporan'], 'full');
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
		 * 
		 */
		public function edit($id){
			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				$id = strtoupper($id);
				if(empty($id) || $id == "") { $this->redirect(BASE_URL."laporan-sub-kas-kecil"); }
				
				// get data pengajuan dan saldo sub kas kecil
				$this->model('Sub_kas_kecilModel');
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
						// status disetujui
						if($data['status_laporan'] == '3'){
	
							$data = array(
								'id' => $this->validation->validInput($data['id']),
								'status_laporan' => $this->validation->validInput($data['status_laporan'])
							);
	
							if($data['dana_disetujui'] > $getSaldo){
								$this->error['dana_disetujui'] = "Dana yang Disetujui terlalu besar dan melebihi saldo";
							}
							else{
								// update status
								$acc_pengajuan = $this->Pengajuan_sub_kas_kecilModel->acc_pengajuan($data);
								if($acc_pengajuan['success']){
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
									$this->message = $acc_pengajuan['error'];
								}
							}
						}
						else{ // status selain disetujui
							$data = array(
								'id' => $this->validation->validInput($data['id']),
								'status' => $this->validation->validInput($data['status'])
							);
	
							// update status
							$update = $this->Pengajuan_sub_kas_kecilModel->update_status($data);
							if($update['success']){
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
								$this->message = $update['error'];
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
