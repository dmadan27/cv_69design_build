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
			);
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
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
			// config datatable
			$config_dataTable = array(
				'tabel' => 'pengajuan_kas_kecil',
				'kolomOrder' => array(null, 'id', 'tgl', 'nama',  'total', 'status',null),
				'kolomCari' => array('id','nama',  'status'),
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

		/**
		* Function action_add
		* method untuk aksi tambah data
		* return berupa json
		* status => status berhasil atau gagal proses tambah
		* notif => pesan yang akan ditampilkan disistem
		* error => error apa saja yang ada dari hasil validasi
		*/
		public function action_add(){
			
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
			
		}

		/**
		* Function delete
		* method yang berfungsi untuk menghapus data
		* param $id didapat dari url
		* return json
		*/
		public function delete($id){			
			if($this->Pengajuan_kasKecilModel->delete($id)) $status = '';
			else $status = 'gagal';

			echo json_encode($status);
		}

		/**
		* Function get_mutasi
		* method yang berfungsi untuk get data mutasi bank sesuai dengan id
		* dipakai di detail data
		*/
		public function get_mutasi(){
			
		}

		/**
		*
		*/
		public function export(){

		}

		/**
		* Fungsi set_validation
		* method yang berfungsi untuk validasi inputan secara server side
		* param $data didapat dari post yang dilakukan oleh user
		* return berupa array, status hasil pengecekan dan error tiap validasi inputan
		*/
		private function set_validation($data){
			
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
	}