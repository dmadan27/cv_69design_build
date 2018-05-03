<?php
	class Mutasi_saldo_kas_kecil extends Controller{

	public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
			$this->model('Mutasi_saldo_kas_kecilModel');
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
				'app/views/mutasi_saldo_kas_kecil/js/initList.js',
			);

			$config = array(
				'title' => array(
					'main' => 'Data Mutasi Saldo Kas Kecil',
					'sub' => 'Ini adalah halaman Data Mutasi Saldo Kas Kecil.',
				),
				'css' => $css,
				'js' => $js,
			);

			$data = $this->Mutasi_saldo_kas_kecilModel->getAll();
			
			$this->layout('mutasi_saldo_kas_kecil/list', $config, $data);
		}	


		public function form(){
			$id = isset($_GET['id']) ? $_GET['id'] : false;

			// cek jenis form
			if(!$id) $this->add();
			else $this->edit($id);
		}

}