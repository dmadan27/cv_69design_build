<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
	
	/**
	* 
	*/
	class Api extends Controller{
		
		/**
		* 
		*/		
		public function __construct(){
			$this->auth();
			$this->auth->mobileOnly();
		}

		/**
		* 
		*/
		public function index(){
			$this->list_pengajuan();
		}

		/**
		* 
		*/
		public function list_pengajuan(){
			if($this->auth->cekAuthMobile()){
				// $page = isset($_POST['page']) ? $_POST['page'] : false;

				// load model A
				$this->model('Pengajuan_sub_kas_kecilModel');
				$data = $this->Pengajuan_sub_kas_kecilModel->getAll_pending();

				// echo "<pre>";
				// var_dump($data);

				// get datanya

				// pembagian data berdasarkan request

				$status = true;
				$output = array(
					'status' => $status,
					'listPengajuan' => $data, // data
					'prev' => null, // prev page
					'next' => null, // next page
					'page' => null, // page
				);
			}
			else{
				$output = array(
					'status' => false,
				);
			}
				
			echo json_encode($output);
		}

		/**
		* 
		*/
		public function list_

	}