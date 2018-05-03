<?php
class Kas_kecil extends Controller{

	public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
			$this->model('Kas_kecilModel');
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
				'app/views/kas_kecil/js/initList.js',
			);

			$config = array(
				'title' => array(
					'main' => 'Data Kas Kecil',
					'sub' => 'Ini adalah halaman Kas Kecil, yang mengandung data Kas Kecil',
				),
				'css' => $css,
				'js' => $js,
			);

			$data = $this->Kas_kecilModel->getAll();
			
			$this->layout('kas_kecil/list', $config, $data);
		}	


		public function form(){
			$id = isset($_GET['id']) ? $_GET['id'] : false;

			// cek jenis form
			if(!$id) $this->add();
			else $this->edit($id);
		}
}