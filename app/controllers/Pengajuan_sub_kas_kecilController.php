<?php
class Pengajuan_sub_kas_kecil extends Controller{

	public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
			$this->model('Pengajuan_sub_kas_kecilModel');
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
				'app/views/pengajuan_sub_kas_kecil/js/initList.js',
			);

			$config = array(
				'title' => array(
					'main' => 'Data Pengajuan Sub Kas Kecil',
					'sub' => '',
				),
				'css' => $css,
				'js' => $js,
			);

			// $data = $this->Pengajuan_sub_kas_kecilModel->getAll();
			
			$this->layout('pengajuan_sub_kas_kecil/list', $config);
		}	


		public function form(){
			$id = isset($_GET['id']) ? $_GET['id'] : false;

			// cek jenis form
			if(!$id) $this->add();
			else $this->edit($id);
		}

}