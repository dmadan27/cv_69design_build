<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	*
	*/
	class Api extends Controller{

		protected $status = true;

		/**
		*
		*/
		public function __construct(){
			$this->auth();
			$this->auth->mobileOnly();
			if(!$this->auth->cekAuthMobile()) $this->status = false;
		}

		/**
		*
		*/
		public function index(){
			$this->pengajuan();
		}

		/**
		*
		*/
		public function pengajuan(){
			$page = (isset($_POST['page']) && !empty($_POST['page'])) ? $_POST['page'] : 1;

			$this->model('Pengajuan_sub_kas_kecilModel');

			$dataPengajuan = $this->Pengajuan_sub_kas_kecilModel->getAll_mobile($page);
			$totalData = $this->Pengajuan_sub_kas_kecilModel->get_recordTotal_mobile();
			$totalPage = ceil($totalData/10);

			$next = ($page < $totalPage) ? ($page + 1) : null;

			$output = array(
				'list_pengajuan' => $dataPengajuan,
				'next' => $next,
				'status' => $this->status,
				// 'page' => $page,
				// 'totalData' => $totalData,
				// 'totalPage' => $totalPage,
			);

			echo json_encode($output);
		}

		/**
		*
		*/
		public function generate_id_pengajuan($id_pengajuan) {
			$this->model('Pengajuan_sub_kas_kecilModel');

			$data = !empty($this->Pengajuan_sub_kas_kecilModel->getLastID($id_pengajuan)['id']) ? $this->Pengajuan_sub_kas_kecilModel->getLastID($id_pengajuan)['id'] : false;

			if(!$data) $id = $id_pengajuan.'0001';
			else{
				// $data = implode('', $data);
				$kode = $id_pengajuan;
				$noUrut = (int)substr($data, 20, 4);
				$noUrut++;

				$id = $kode.sprintf("%04s", $noUrut);
			}
			return $id;
		}

		/**
		*
		*/
		public function add_pengajuan(){
			$this->model('Sub_kas_kecilModel');
			$this->model('Pengajuan_sub_kas_kecilModel');
			$id_pengajuan = $_POST['id_pengajuan'];
			$id_skc = $_POST['id'];

			echo json_encode(array(
				// generate id pengajuan
				'id_pengajuan' => $this->generate_id_pengajuan($id_pengajuan),
				// get saldo
				'saldo' => $this->Sub_kas_kecilModel->getSaldoById($id_skc)['saldo'],

				'status' => true
			));
		}

		/**
		*
		*/
		public function action_add_pengajuan(){
			$output = array(
		    	'status' => false,
    			'error' => ''
		  	);

	    	if ($this->status) {
	      		$this->model('Pengajuan_sub_kas_kecilModel');

       	 		$pengajuan = json_decode($_POST["pengajuan"]);
  				$detail_pengajuan = json_decode($_POST["detail_pengajuan"]);

	  			$data = array(
	  				'pengajuan' => $pengajuan,
	  				'detail_pengajuan' => $detail_pengajuan
	  			);

  				$query_sukses = $this->Pengajuan_sub_kas_kecilModel->insert($data);

  				if ($query_sukses) {
  					$output['status'] = true;
  				} else {
		    		$output = array(
		      			'status' => false,
  						'error' => $query_sukses,
		    		);
  				}
	    	}

			echo json_encode($output);
		}

		/**
		*
		*/
		public function detail_pengajuan(){
			$id_pengajuan = $_POST["id_pengajuan"];

			$this->model('Pengajuan_sub_kas_kecilModel');
			$dataDetail = $this->Pengajuan_sub_kas_kecilModel->getById_mobile(strtoupper($id_pengajuan));

			$output = array(
				'detail_pengajuan' => $dataDetail,
				'status' => $this->status,
			);

			echo json_encode($output);

			// $temp = 0;
			// for($i=1; $i<1000000; $i++){
			// 	$temp += $i;
			// }
		}

		/**
		*
		*/
		public function add_laporan(){

		}

		/**
		*
		*/
		public function proyek(){
			$page = (isset($_POST['page']) && !empty($_POST['page'])) ? $_POST['page'] : 1;

			$this->model('ProyekModel');

			$dataProyek = $this->ProyekModel->getAll_mobile($page);
			$totalData = $this->ProyekModel->get_recordTotal_mobile();
			$totalPage = ceil($totalData/10);

			$next = ($page < $totalPage) ? ($page + 1) : null;

			$output = array(
				'list_proyek' => $dataProyek,
				'next' => $next,
				'status' => $this->status,
				// 'page' => $page,
				// 'totalData' => $totalData,
				// 'totalPage' => $totalPage,
			);

			echo json_encode($output);
		}

		/**
		*
		*/
		public function profil(){

		}

		/**
		*
		*/
		public function mutasi(){
			$page = (isset($_POST['page']) && !empty($_POST['page'])) ? $_POST['page'] : 1;

			$this->model('Mutasi_saldo_sub_kas_kecilModel');

			$dataMutasi = $this->Mutasi_saldo_sub_kas_kecilModel->getAll_mobile($page);
			$totalData = $this->Mutasi_saldo_sub_kas_kecilModel->get_recordTotal_mobile();
			$totalPage = ceil($totalData/10);

			$next = ($page < $totalPage) ? ($page + 1) : null;

			$output = array(
				'list_mutasi' => $dataMutasi,
				'next' => $next,
				'status' => $this->status,
				// 'page' => $page,
				// 'totalData' => $totalData,
				// 'totalPage' => $totalPage,
			);

			echo json_encode($output);
		}

		/**
		*
		*/
		private function set_validation_pengajuan($data){
			// id
			$this->validation->set_rules($data['id'], 'ID Pengajuan', 'id', 'string | 1 | 255 | required');
			// id_sub_kas_kecil
			$this->validation->set_rules($data['id_sub_kas_kecil'], 'ID Sub Kas Kecil', 'id_sub_kas_kecil', 'string | 1 | 255 | required');
			// id_proyek
			$this->validation->set_rules($data['id_proyek'], 'ID Proyek', 'id', 'string | 1 | 255 | required');
			// total
			$this->validation->set_rules($data['total'], 'Total', 'total', 'nilai | 1 | 99999999 | required');

			return $this->validation->run();
		}

		/**
		*
		*/
		private function set_validation_pengajuan_detail($data){
			// id_pengajuan
			$this->validation->set_rules($data['id_pengajuan'], 'ID Pengajuan', 'id', 'string | 1 | 255 | required');
			// nama
			$this->validation->set_rules($data['nama'], 'Nama', 'nama', 'string | 1 | 255 | required');
			// jenis
			$this->validation->set_rules($data['jenis'], 'Jenis', 'jenis', 'string | 1 | 255 | required');
			// satuan
			$this->validation->set_rules($data['satuan'], 'Satuan', 'satuan', 'string | 1 | 255 | required');
			// qty
			$this->validation->set_rules($data['qty'], 'Qty', 'qty', 'angka | 1 | 5 | required');
			// harga
			$this->validation->set_rules($data['harga'], 'Total', 'total', 'nilai | 1 | 99999999 | required');
			// subtotal
			$this->validation->set_rules($data['subtotal'], 'Total', 'total', 'nilai | 1 | 99999999 | required');

			return $this->validation->run();
		}

	}
