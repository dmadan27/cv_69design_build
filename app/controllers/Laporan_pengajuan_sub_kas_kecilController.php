<?php

	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung"); 

	class Laporan_pengajuan_sub_kas_kecil extends Controller{

	public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
			$this->model('Laporan_pengajuan_sub_kas_kecilModel');
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
				'app/views/laporan_pengajuan_sub_kas_kecil/js/initList.js',
			);

			$config = array(
				'title' => array(
					'main' => 'Data Laporan Pengajuan Sub Kas Kecil',
					'sub' => 'Ini adalah halaman Laporan Pengajuan Sub Kas Kecil, yang mengandung data laporan hasil pengajuan dari lapangan',
				),
				'css' => $css,
				'js' => $js,
			);

			$data = $this->Laporan_pengajuan_sub_kas_kecilModel->getAll();
			
			$this->layout('laporan_pengajuan_sub_kas_kecil/list', $config, $data);
		}	


		public function form(){
			$id = isset($_GET['id']) ? $_GET['id'] : false;

			// cek jenis form
			if(!$id) $this->add();
			else $this->edit($id);
		}

}