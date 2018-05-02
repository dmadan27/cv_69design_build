<?php
	/**
	* Class Page
	* Berfungsi untuk merender halaman layout
	* Menambah Header, Sidebar, Content, dan Footer
	* Menambah CSS, JS, title content, set data
	*/
	class Page{
		private $title = array();
		private $header;
		private $sidebar;
		private $menuSidebar;
		private $content;
		private $footer;
		private $css = array();
		private $js = array();
		private $data;

		/*
			Fungsi untuk set title
			Main Title => Title utama
			Sub Title => Sub Title
		*/
		public function setTitle($mainTitle = '', $subTitle = ''){
			$this->title['main'] = $mainTitle;
			$this->title['sub'] = $subTitle;
		}

		public function setHeader(){
			ob_start();
			require_once ROOT.DS.'app'.DS.'views'.DS.'layout'.DS.'header.php';
			$this->header = ob_get_clean();
		}

		public function setSidebar(){
			ob_start();
			require_once ROOT.DS.'app'.DS.'views'.DS.'layout'.DS.'sidebar.php';
			$this->sidebar = ob_get_clean();
		}

		public function setContent($content){
			$temp = explode('/', $content);
			if (count($temp) > 1){
				$content = $temp[0].DS.$temp[1];
			}

			ob_start();
			require_once ROOT.DS.'app'.DS.'views'.DS.$content.'.php';
			$this->content = ob_get_clean();
		}

		public function setData($data){
			$this->data = $data;
		}

		public function setFooter(){
			ob_start();
			require_once ROOT.DS.'app'.DS.'views'.DS.'layout'.DS.'footer.php';
			$this->footer = ob_get_clean();
		}

		public function addCSS($cssPath){
			$this->css[] = $cssPath;
		}

		public function addJS($jsPath){
			$this->js[] = $jsPath;
		}

		public function getCSS(){
			foreach ($this->css as $value) {
				echo '<link rel="stylesheet" href="'.BASE_URL.$value.'">'."\n";
			}
		}

		public function getJS(){
			foreach ($this->js as $value) {
				echo '<script src="'.BASE_URL.$value.'"></script>'."\n";
			}
		}

		public function render(){
			$this->setHeader();
			$this->setSidebar();
			$this->setFooter();		
			require_once ROOT.DS.'app'.DS.'views'.DS.'layout.php';
		}

	}