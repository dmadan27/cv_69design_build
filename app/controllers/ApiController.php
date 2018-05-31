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
		public function get_id_pengajuan() {
			// $id = $_POST['id'];
			$id = 'PGSKC-PRY002-LOG001-';

			$this->model('Pengajuan_sub_kas_kecilModel');
			$id_proyek = $this->Pengajuan_sub_kas_kecilModel->getLastID(strtoupper($id_pengajuan));

			echo json_encode($id_proyek);
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
			$this->model('Pengajuan_sub_kas_kecilModel');
			$id_pengajuan = $_POST['id_pengajuan'];

			echo json_encode(array(
				// generate id pengajuan
				'list_pengajuan' => $this->generate_id_pengajuan($id_pengajuan),

				// get saldo 


				'status' => true
			));
		}

		/**
		*
		*/
		public function action_add_pengajuan(){
			// $data = isset($_POST) ? $_POST : false;

			// $this->model('Pengajuan_sub_kas_kecilModel');

			// $data = array(
			// 	'pengajuan' => array(),
			// 	'detail' => array(
			// 		array(),
			// 		array(),
			// 		array(),
			// 	),
			// );

			// insert pengajuan
			// if($this->Pengajuan_sub_kas_kecilModel->insert($dataPengajuan)){
			// 	foreach($dataDetail as $index => $array){
			// 		foreach($value as $row){

			// 		}
			// 	}
			// }

			// insert detail

			// echo json_encode($data);

			$detail_pengajuan = json_decode($_POST['detail_pengajuan']);
			$pengajuan = json_decode($_POST['pengajuan']);

			// echo json_encode(array(
			// 	'detail_pengajuan' => $detail_pengajuan,
			// 	'pengajuan' => $pengajuan,
			// 	'status' => true
			// ));
		}

		/**
		*
		*/
		public function detail_pengajuan($id_pengajuan){
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

			$this->model('ProyekModel');

			$dataProyek = $this->ProyekModel->getAll_mobile($page);
			$totalData = $this->ProyekModel->get_recordTotal_mobile();
			$totalPage = ceil($totalData/10);

			$next = ($page < $totalPage) ? ($page + 1) : null;

			$output = array(
				'list_pengajuan' => $dataProyek,
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


	}
