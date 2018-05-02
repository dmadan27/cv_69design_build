<?php
	/**
	* Class Controller, Parent Class
	* Method Method Utama
	*/
	class Controller{
		// public function __construct(){

		// }

		final protected function page(){
			$view = new Page();
			return $view;
		}

		final protected function model($modelName){
			require_once ROOT.DS.'app'.DS.'models'.DS.ucfirst($modelName).'.php';
			$class = ucfirst($modelName);
			$this->$modelName = new $class();
		}

		final protected function auth($auth = 'auth'){
			$this->$auth = new Auth();
		}

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

			// get layout
			$view->render();
		}

		final protected function view($view){
			$temp = explode('/', $view);
			if (count($temp) > 1){
				$view = $temp[0].DS.$temp[1];
			}
			
			require_once ROOT.DS.'app'.DS.'views'.DS.$view.'.php';
		}

		final public function redirect($url){
			header("Location: ".$url);
			die();
		}
	}