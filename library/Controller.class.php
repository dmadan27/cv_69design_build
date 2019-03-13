<?php

Defined("BASE_PATH") or die(ACCESS_DENIED);

/**
 * Class Controller
 * Library yang berperan sebagai Parent Class untuk setiap Controller
 */
class Controller
{

	/**
	 * Method model
	 * Proses untuk load Library Model.class.php
	 * @param modelName {string}
	 */
	final protected function model($modelName){
		require_once ROOT.DS.'app'.DS.'models'.DS.ucfirst($modelName).'.php';
		$class = ucfirst($modelName);
		$this->$modelName = new $class();
	}

	/**
	 * Method page
	 * Proses untuk load library Page.class.php
	 */
	final protected function page(){
		$view = new Page();
		return $view;
	}

	/**
	 * Method excel
	 * Proses untuk load library Excel.class.php
	 * @param excel {string} default excel
	 */
	final protected function excel($excel = 'excel') {					
		$this->$excel = new Excel();
	}

	/**
	 * Method excel v2
	 * Proses untuk load library Excel_v2.class.php
	 * @param excel {string} default excel_v2
	 */
	final protected function excel_v2($excel = 'excel_v2') {					
		$this->$excel = new Excel_v2();
	}

	/**
	 * Method auth
	 * Proses untuk load library Auth.class.php
	 * @param auth {string} default auth
	 */
	final protected function auth($auth = 'auth'){
		$this->$auth = new Auth();
	}

	/**
	 * Method helper
	 * Proses untuk load library Helper.class.php
	 * @param helper {string} default helper
	 */
	final protected function helper($helper = 'helper'){
		$this->$helper = new Helper();
	}

	/**
	 * Method validation
	 * Proses untuk load library Validation.class.php
	 * @param validation {string} default validation
	 */
	final protected function validation($validation = 'validation'){
		$this->$validation = new Validation();
	}

	/**
	 * Method layout
	 * Proses untuk templating layout content, css, js, dan data
	 * @param content {string} halaman/content yang ingin dipasang di template layout. contoh: list, test/list
	 * @param config {array} default berupa null, jika diisi harus berupa array
	 * 		$config = array(
	 * 			'title' => array(
	 * 				'main' => {string},
	 * 				'sub' => {string}
	 * 			),
	 * 			'css' => array(),
	 * 			'js' => array()
	 * 		)
	 * @param data {array} default null, data yang ingin diparsing ke template
	 */
	final protected function layout($content, $config = null, $data = null){
		$view = $this->page();

		// set data
		if($data != null) { $view->setData($data); }

		if($config != null) {
			// set title
			$view->setTitle($config['title']['main'], $config['title']['sub']);

			// set css
			foreach($config['css'] as $value) {
				$view->addCSS($value);
			}

			// set js
			foreach($config['js'] as $value) {
				$view->addJS($value);
			}
		}

		// set content
		$view->setContent($content);

		// get layout
		$view->render();
	}

	/**
	 * Method view
	 * Proses untuk load view secara langsung tanpa ada hubungan dengan layout
	 * @param view {string}
	 */
	final protected function view($view, $data = null) {
		$temp = explode('/', $view);
		
		$newView = '';
		for($i=0; $i<count($temp); $i++) {
			if((count($temp)-$i!=1)) { $newView .= $temp[$i].DS; }
			else { $newView .= $temp[$i]; }
		}
		
		require_once ROOT.DS.'app'.DS.'views'.DS.$newView.'.php';
		die();
	}

	/**
	 * Method redirect
	 * Proses untuk redirect ke halaman tertentu
	 * jika diisi kosong secara default mengarah ke home
	 * @param url {string}
	 */
	final public function redirect($url = BASE_URL){
		header("Location: ".$url);
		die();
	}
}