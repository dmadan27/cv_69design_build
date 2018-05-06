<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
	// namespace app\controllers;

	/**
	* Class home, default controller
	* load class auth
	* cek auth
	*/
	class Home extends Controller{

		/**
		* Load class auth, cek auth
		*/
		public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
		}

		/**
		* Fungsi index, cek session level dan arahkan beranda sesuai dengan session level
		*/
		public function index(){
			// cek jenis user
			switch (strtolower($_SESSION['sess_level'])) {
				// arahkan ke beranda masing-masing
				case 'kas besar':
					$this->beranda_kasBesar();
					break;
					
				case 'kas kecil':
					$this->beranda_kasKecil();
					break;

				case 'owner':
					$this->beranda_owner();
					break;

				default:
					die();
					break;
			}
		}

		/**
		* Beranda owner
		*/
		private function beranda_owner(){
			// config css-js
			$css = array();
			$js = array();

			$config = array(
				'title' => array(
					'main' => 'Beranda',
					'sub' => 'Dashboard Owner',
				),
				'css' => $css,
				'js' => $js,
			);

			$data = null;

			$this->layout('beranda/owner', $config, $data);
		}

		/**
		* Beranda Kas Besar
		*/
		private function beranda_kasBesar(){
			// config css-js
			$css = array();
			$js = array();

			$config = array(
				'title' => array(
					'main' => 'Beranda',
					'sub' => 'Dashboard Kas Besar',
				),
				'css' => $css,
				'js' => $js,
			);

			$data = null;

			$this->layout('beranda/kas_besar', $config, $data);
		}

		/**
		* Beranda Kas Kecil
		*/
		private function beranda_kasKecil(){
			// config css-js
			$css = array();
			$js = array();

			$config = array(
				'title' => array(
					'main' => 'Beranda',
					'sub' => 'Dashboard Kas Kecil',
				),
				'css' => $css,
				'js' => $js,
			);

			$data = null;

			$this->layout('beranda/kas_kecil', $config, $data);
		}
	}
