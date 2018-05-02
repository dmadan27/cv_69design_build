<?php
	/**
	* 
	*/
	class Api extends Controller{
		
		public function __construct(){
			$this->auth();
			$this->auth->cekAuthMobile();
		}

		public function index(){

		}

		private function list_pengajuan(){
			$page = isset($_POST['page']) ? $_POST['page'] : false;

			// load model A

			// get datanya
			$data = array(
				array(
					'nama' => 'Ujang Jeprut',
					'umur' => 20,
				),
				array(
					'nama' => 'Ujang Jeprut',
					'umur' => 20,
				),
				array(
					'nama' => 'Ujang Jeprut',
					'umur' => 20,
				),
				array(
					'nama' => 'Ujang Jeprut',
					'umur' => 20,
				),
				array(
					'nama' => 'Ujang Jeprut',
					'umur' => 20,
				),
				array(
					'nama' => 'Ujang Jeprut',
					'umur' => 20,
				),
				array(
					'nama' => 'Ujang Jeprut',
					'umur' => 20,
				),
				array(
					'nama' => 'Ujang Jeprut',
					'umur' => 20,
				),
				array(
					'nama' => 'Ujang Jeprut',
					'umur' => 20,
				),
				array(
					'nama' => 'Ujang Jeprut',
					'umur' => 20,
				),
			);

			// pembagian data berdasarkan request

			$output = array(
				'listPengajuan' => $data, // data
				'prev' => null, // prev page
				'next' => null, // next page
				'page' => $page, // page
			);

			echo json_encode($output);
		}

	}