<?php

class SubKasKecil extends Controller{



	public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
			$this->model('SubKasKecilModel');
	}


	public function index(){
			$this->list();
		}


	private function list(){
			// $this->auth->cekAuth();
			$css = array('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css');
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'app/views/sub_kas_kecil/js/initList.js',
			);

			$config = array(
				'title' => array(
					'main' => 'Data Sub Kas Kecil',
					'sub' => 'Ini adalah halaman sub kas kecil, yang mengadung data sub kas kecil',
				),
				'css' => $css,
				'js' => $js,
			);

			$data = $this->SubKasKecilModel->getAll();
			
			$this->layout('sub_kas_kecil/list', $config, $data);
		}	


		public function form(){
			$id = isset($_GET['id']) ? $_GET['id'] : false;

			// cek jenis form
			if(!$id) $this->add();
			else $this->edit($id);
		}	
}