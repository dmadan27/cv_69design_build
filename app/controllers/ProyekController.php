<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	* 
	*/
	class Proyek extends Controller{
		
		/**
		* 
		*/
		public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
			$this->model('ProyekModel');
		}

		/**
		* 
		*/
		public function index(){
			$this->list();
		}

		/**
		* 
		*/
		private function list(){
			$css = array('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css');
			$js = array(
				'assets/bower_components/datatables.net/js/jquery.dataTables.min.js', 
				'assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'app/views/proyek/js/initList.js',
			);

			$config = array(
				'title' => array(
					'main' => 'Data Proyek',
					'sub' => '',
				),
				'css' => $css,
				'js' => $js,
			);

			$data = array(
				'tokenCrsf' => password_hash($this->auth->getToken(), PASSWORD_BCRYPT),
			);

			$this->layout('proyek/list', $config, $data);
		}

		/**
		* 
		*/
		public function get_list(){

		}

		public function form(){

  			$css = array(
  				'assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
  				
  			);
			$js = array(
				'assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
				'app/views/proyek/js/initForm.js',
			);


			$config = array(
				'title' => array(
					'main' => 'Data Proyek',
					'sub' => '',
				),
				'css' => $css,
				'js' => $js,
			);

			$this->layout('proyek/form', $config);
		}
	}