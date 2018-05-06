<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
	
	/**
	* Class Controller, Parent Class
	* Method Method Utama
	*/
	class Controller{

		/**
		* Fungsi untuk load Library Model.class.php
		*/
		final protected function model($modelName){
			require_once ROOT.DS.'app'.DS.'models'.DS.ucfirst($modelName).'.php';
			$class = ucfirst($modelName);
			$this->$modelName = new $class();
		}

		/**
		* Fungsi untuk load library Page.class.php
		*/
		final protected function page(){
			$view = new Page();
			return $view;
		}

		/**
		* Fungsi untuk load library Auth.class.php
		*/
		final protected function auth($auth = 'auth'){
			$this->$auth = new Auth();
		}

		/**
		* Fungsi untuk templating layout content, css, js, dan data
		* terdapat 3 parameter
		* $content => halaman/content yang ingin dipasang di template layout. contoh: list, test/list
		* $config => default nilai berupa null, jika diisi harus berupa array
		* $config = array(
			'title' => array(
				'main' => '',
				'sub' => '',
			),
			'css' => array(),
			'js' => array()
		)
		* $data => data yang ingin dilampirkan di layout, dapat berupa array ataupun var langsung
		*/
		final protected function layout($content, $config = null, $data = null){
			$view = $this->page();

			// set data
			if($data != null) $view->setData($data);

			if($config != null){
				// set title
				$view->setTitle($config['title']['main'], $config['title']['sub']);

				// set css
				foreach($config['css'] as $value){
					$view->addCSS($value);
				}

				// set js
				foreach($config['js'] as $value){
					$view->addJS($value);
				}
			}

			// set content
			$view->setContent($content);

			// set menu sidebar
			$view->setMenuSidebar();

			// get layout
			$view->render();
		}

		/**
		* Fungsi untuk load view secara langsung tanpa ada hubungan dengan layout
		*/
		final protected function view($view){
			$temp = explode('/', $view);
			if (count($temp) > 1){
				$view = $temp[0].DS.$temp[1];
			}
			
			require_once ROOT.DS.'app'.DS.'views'.DS.$view.'.php';
		}

		/**
		* Fungsi untuk redirect ke halaman tertentu
		* jika diisi kosong secara default mengarah ke home
		*/
		final public function redirect($url = BASE_URL){
			header("Location: ".$url);
			die();
		}
	}