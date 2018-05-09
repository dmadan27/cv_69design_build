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
		public function add_pengajuan(){

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

		}

		/**
		* 
		*/
		

	}