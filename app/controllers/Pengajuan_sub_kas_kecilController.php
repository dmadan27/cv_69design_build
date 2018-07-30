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

				if(strtolower($row['status']) == "disetujui") {
					$status = '<span class="label label-success">';
					$aksi = '<div class="btn-group">'.$aksiDetail.$aksiHapus.'</div>';
				}
				else if(strtolower($row['status']) == "perbaiki") {
					$status = '<span class="label label-warning">';
					$aksi = '<div class="btn-group">'.$aksiDetail.$aksiHapus.'</div>';
				}
				else if(strtolower($row['status']) == "ditolak") $status = '<span class="label label-danger">';
				else if(strtolower($row['status']) == "pending") $status = '<span class="label label-primary">';
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
		*
		*/
		public function export(){

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
