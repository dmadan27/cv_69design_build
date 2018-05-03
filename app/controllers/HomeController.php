<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
	// namespace app\controllers;

	class Home extends Controller{

		public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
		}

		/**
		* 
		*/
		public function index(){
			// cek jenis user

			// arahkan ke beranda masing-masing
			$this->beranda_kasKecil();
		}

		/**
		*
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
		*
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
		*
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
