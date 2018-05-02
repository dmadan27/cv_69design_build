<?php
	/**
	* Class Route
	* untuk mengarahkan semua request ke controller
	*/
	class Route{
		
		private $__request;
		private $__controller;

		/**
		* fungsi untuk set properti request dengan request yg diisi oleh user
		* support method chaining
		*/
		public function setUri($request){
			// set $_request dari request yg di pinta
			$this->__request = $request;
			return $this;
		}

		/**
		* fungsi untuk load controller
		* mengecek request dan mengarahkan ke controller
		*/
		public function getController(){
			$uri = explode('/', $this->__request);
			$class = isset($uri[0]) && ($uri[0] != "") ? strtolower(ucfirst($uri[0])) : DEFAULT_CONTROLLER; // class
			$method = isset($uri[1]) ? strtolower($uri[1]) : 'index';	// method
			$param = isset($uri[2]) ? $uri[2] : false;	// param

			// set request controller - class
			$this->__controller = ROOT.DS.'app'.DS.'controllers'.DS.$class."Controller.php";

			// cek file controller
			if(file_exists($this->__controller)){
				// load controller dan class
				require_once $this->__controller;
				$obj = new $class();

				if(method_exists($obj, $method)){
					$obj->$method($param);
					// call_user_func_array(array($obj, $method), array());
				}
				else die($this->error('403')); // method tidak tersedia	
			}
			else die($this->error('404')); // class tidak tersedia
		}

		/**
		* fungsi untuk mengarahkan request yg tidak tersedia ke page error
		*/
		protected function error($error){
			switch ($error) {
				case '403':
					$pesanError = "FORBIDDEN ERROR !";
					break;
				
				case '404':
					$pesanError = "PAGE NOT FOUND !";
					break;

				case '500':
					$pesanError = "INTERNAL SERVER ERROR !";
					break;

				default:
					header('Location: '.BASE_URL);
					die();
					break;
			}
			
			require_once ROOT.DS.'app'.DS.'views'.DS.'error.php';
		}
	}