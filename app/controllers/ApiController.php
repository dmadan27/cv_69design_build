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
		public function index() {
			echo json_encode(array(
				'status' => $this->status,
			));
		}

		/**
		*
		*/
		public function pengajuan() {
			$this->model('Pengajuan_sub_kas_kecilModel');

			$output = array();
			$output['status'] = $this->status;

			if ($this->status) {
				$page = (isset($_POST['page']) && !empty($_POST['page'])) ? $_POST['page'] : 1;

				$dataPengajuan = $this->Pengajuan_sub_kas_kecilModel->getAll_mobile($page);
				$totalData = $this->Pengajuan_sub_kas_kecilModel->get_recordTotal_mobile();
				$totalPage = ceil($totalData/10);

				$next = ($page < $totalPage) ? ($page + 1) : null;

				$output['list_pengajuan'] = $dataPengajuan;
				$output['next'] = $next;
			}
			echo json_encode($output);
		}

		/**
		*
		*/
		public function add_pengajuan(){
			$this->model('Sub_kas_kecilModel');
			// $this->model('Pengajuan_sub_kas_kecilModel');

			$output = array();
			$output['status'] = $this->status;

			$id_pengajuan = ((isset($_POST['id_pengajuan'])) && !empty($_POST['id_pengajuan'])) ? $_POST['id_pengajuan'] : false;
			$id_skk = ((isset($_POST['id'])) && !empty($_POST['id'])) ? $_POST['id'] : false;

			if ($this->status && ($id_pengajuan != false) && ($id_skk != false)) {

				$output['id_pengajuan'] = $this->generate_id_pengajuan($id_pengajuan);
				$output['saldo'] = $this->Sub_kas_kecilModel->getSaldoById($id_skk)['saldo'];
				$output['sisa_saldo'] = $this->Sub_kas_kecilModel->getSisaSaldoById($id_skk)['sisa_saldo'];
			} else {
				$output['status'] = false;
			}
			echo json_encode($output);
		}

		/**
		*
		*/
		public function action_add_pengajuan(){
			$this->model('Pengajuan_sub_kas_kecilModel');

			$output = array();
			$output['status'] = $this->status;

			$pengajuan = ((isset($_POST["pengajuan"])) && !empty($_POST["pengajuan"])) ? $_POST["pengajuan"] : false;
			$detail_pengajuan = ((isset($_POST["detail_pengajuan"])) && !empty($_POST["detail_pengajuan"])) ? $_POST["detail_pengajuan"] : false;

    		if ($this->status && ($pengajuan != false) && ($detail_pengajuan != false)) {

				$data = array(
					'pengajuan' => json_decode($pengajuan),
					'detail_pengajuan' => json_decode($detail_pengajuan)
				);

				$resultQuery = $this->Pengajuan_sub_kas_kecilModel->insert($data);

				if ($resultQuery === true) {
					$output['status'] = true;
				} else {
					$output['error'] = $resultQuery;
				}
			} else {
				$output['status'] = false;
			}
			echo json_encode($output);
		}

		/**
		*
		*/
		public function detail_pengajuan(){
			$this->model('Pengajuan_sub_kas_kecilModel');

			$output = array();
			$output['status'] = $this->status;

			$id_pengajuan = ((isset($_POST['id_pengajuan'])) && !empty($_POST['id_pengajuan'])) ? $_POST['id_pengajuan'] : false;

			if ($this->status && ($id_pengajuan != false)) {
				$dataDetail = $this->Pengajuan_sub_kas_kecilModel->getById_mobile(strtoupper($id_pengajuan));

				$output['detail_pengajuan'] = $dataDetail;
			} else {
				$output['status'] = false;
			}

			// $temp = 0;
			// for($i=1; $i<1000000; $i++){
			// 	$temp += $i;
			// }
			echo json_encode($output);
		}

		/**
		*
		*/
		public function add_laporan(){
			$this->model('Sub_kas_kecilModel');
			$this->model('Pengajuan_sub_kas_kecilModel');

			$output = array();
			$output['status'] = $this->status;

			$id_pengajuan = ((isset($_POST['id_pengajuan'])) && !empty($_POST['id_pengajuan'])) ? $_POST['id_pengajuan'] : false;
			$id_skk = ((isset($_POST['id'])) && !empty($_POST['id'])) ? $_POST['id'] : false;

			if ($this->status && ($id_pengajuan != false) && ($id_skk != false)) {
				$output['id_pengajuan'] = $this->generate_id_pengajuan($id_pengajuan);
				$output['saldo'] = $this->Sub_kas_kecilModel->getSaldoById($id_skk)['saldo'];
				$output['sisa_saldo'] = $this->Sub_kas_kecilModel->getSisaSaldoById($id_skk)['sisa_saldo'];
				$output['pengajuan'] = $this->Pengajuan_sub_kas_kecilModel->getById_mobile(strtoupper($id_pengajuan);
			} else {
				$output['status'] = false;
			}
			echo json_encode($output);	
		}

		/**
		*
		*/
		public function action_add_laporan(){
			$this->model('Pengajuan_sub_kas_kecilModel');

			$output = array();
			$output['status'] = $this->status;

			$pengajuan = ((isset($_POST["pengajuan"])) && !empty($_POST["pengajuan"])) ? $_POST["pengajuan"] : false;
			$detail_pengajuan = ((isset($_POST["detail_pengajuan"])) && !empty($_POST["detail_pengajuan"])) ? $_POST["detail_pengajuan"] : false;

    		if ($this->status && ($pengajuan != false) && ($detail_pengajuan != false)) {

				$data = array(
					'pengajuan' => json_decode($pengajuan),
					'detail_pengajuan' => json_decode($detail_pengajuan)
				);

				$resultQuery = $this->Pengajuan_sub_kas_kecilModel->insert($data);

				if ($resultQuery === true) {
					$output['status'] = true;
				} else {
					$output['error'] = $resultQuery;
				}
			} else {
				$output['status'] = false;
			}
			echo json_encode($output);	
		}

		/**
		*
		*/
		public function proyek(){
			$this->model('ProyekModel');

			$output = array();
			$output['status'] = $this->status;

			if ($this->status) {
				$page = (isset($_POST['page']) && !empty($_POST['page'])) ? $_POST['page'] : 1;

				$dataProyek = $this->ProyekModel->getAll_mobile($page);
				$totalData = $this->ProyekModel->get_recordTotal_mobile();
				$totalPage = ceil($totalData/10);

				$next = ($page < $totalPage) ? ($page + 1) : null;

				$output['list_proyek'] = $dataProyek;
				$output['next'] = $next;
			}
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
			$this->model('Mutasi_saldo_sub_kas_kecilModel');

			$output = array();
			$output['status'] = $this->status;

			if ($this->status) {
				$page = (isset($_POST['page']) && !empty($_POST['page'])) ? $_POST['page'] : 1;

				$dataMutasi = $this->Mutasi_saldo_sub_kas_kecilModel->getAll_mobile($page);
				$totalData = $this->Mutasi_saldo_sub_kas_kecilModel->get_recordTotal_mobile();
				$totalPage = ceil($totalData/10);

				$next = ($page < $totalPage) ? ($page + 1) : null;

				$output['list_mutasi'] = $dataMutasi;
				$output['next'] = $next;
			}
			echo json_encode($output);
		}

		/**
		*
		*/
		private function generate_id_pengajuan($id_pengajuan) {
			$this->model('Pengajuan_sub_kas_kecilModel');

			$data = !empty($this->Pengajuan_sub_kas_kecilModel->getLastID($id_pengajuan)['id']) ? $this->Pengajuan_sub_kas_kecilModel->getLastID($id_pengajuan)['id'] : false;

			if (!$data) {
				$id = $id_pengajuan.'0001';
			} else {
				$kode = $id_pengajuan;
				$noUrut = (int)substr($data, 21, 4);
				$noUrut++;

				$id = $kode.sprintf("%04s", $noUrut);
			}
			return $id;
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
