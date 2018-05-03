<?php
class Kas_besar extends Controller{

	public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
			$this->model('Kas_besarModel');
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
				'app/views/kas_besar/js/initList.js',
			);

			$config = array(
				'title' => array(
					'main' => 'Data Kas Besar',
					'sub' => 'Ini adalah halaman Kas Besar, yang mengandung data Kas Besar',
				),
				'css' => $css,
				'js' => $js,
			);

			$data = $this->Kas_besarModel->getAll();
			
			$this->layout('kas_besar/list', $config, $data);
		}	


		public function form(){
			$id = isset($_GET['id']) ? $_GET['id'] : false;

			// cek jenis form
			if(!$id) $this->add();
			else $this->edit($id);
		}

}