<?php 
class Bank extends Controller{


	public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
			$this->model('BankModel');
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
				'app/views/bank/js/initList.js',
			);

			$config = array(
				'title' => array(
					'main' => 'Data Bank',
					'sub' => 'Ini adalah halaman Data Bank, yang mengandung data seluruh bank yang di miliki',
				),
				'css' => $css,
				'js' => $js,
			);

			$data = $this->BankModel->getAll();
			
			$this->layout('bank/list', $config, $data);
		}	


		public function form(){
			$id = isset($_GET['id']) ? $_GET['id'] : false;

			// cek jenis form
			if(!$id) $this->add();
			else $this->edit($id);
		}



}