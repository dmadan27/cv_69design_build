<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
	
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

		/**
		* Fungsi untuk set title
		* Main Title => Title utama
		* Sub Title => Sub Title
		*/
		public function setTitle($mainTitle = '', $subTitle = ''){
			$this->title['main'] = $mainTitle;
			$this->title['sub'] = $subTitle;
		}

		/**
		* Fungsi untuk set header
		*/
		public function setHeader(){
			ob_start();
			require_once ROOT.DS.'app'.DS.'views'.DS.'layout'.DS.'header.php';
			$this->header = ob_get_clean();
		}

		/**
		* Fungsi untuk set sidebar
		*/
		public function setSidebar(){
			ob_start();
			require_once ROOT.DS.'app'.DS.'views'.DS.'layout'.DS.'sidebar.php';
			$this->sidebar = ob_get_clean();
		}

		/**
		* Fungsi untuk set content
		*/
		public function setContent($content){
			$temp = explode('/', $content);
			if (count($temp) > 1){
				$content = $temp[0].DS.$temp[1];
			}

			ob_start();
			require_once ROOT.DS.'app'.DS.'views'.DS.$content.'.php';
			$this->content = ob_get_clean();
		}

		/**
		* Fungsi untuk set data
		*/
		public function setData($data){
			$this->data = $data;
		}

		/**
		* Fungsi untuk set footer
		*/
		public function setFooter(){
			ob_start();
			require_once ROOT.DS.'app'.DS.'views'.DS.'layout'.DS.'footer.php';
			$this->footer = ob_get_clean();
		}

		/**
		* Fungsi untuk set menu sidebar dinamis
		*/
		public function setMenuSidebar(){
			$level = isset($_SESSION['sess_level']) ? $_SESSION['sess_level'] : false;

			// cek jenis user
			if($level){
				switch (strtolower($level)) {
					// sidebar kas besar
					case 'kas besar':
						ob_start();
						require_once ROOT.DS.'app'.DS.'views'.DS.'layout'.DS.'sidebar'.DS.'sidebar_kas_besar.php';
						$this->menuSidebar = ob_get_clean();
						break;
					
					// sidebar kas kecil
					case 'kas kecil':
						ob_start();
						require_once ROOT.DS.'app'.DS.'views'.DS.'layout'.DS.'sidebar'.DS.'sidebar_kas_kecil.php';
						$this->menuSidebar = ob_get_clean();
						break;

					// sidebar owner
					default:
						ob_start();
						require_once ROOT.DS.'app'.DS.'views'.DS.'layout'.DS.'sidebar'.DS.'sidebar_owner.php';
						$this->menuSidebar = ob_get_clean();
						break;
				}
			}
			else $this->menuSidebar = "";
		}

		/**
		* Fungsi untuk menambah css custom kedalam layout
		*/
		public function addCSS($cssPath){
			$this->css[] = $cssPath;
		}

		/**
		* Fungsi untuk menambah js custom kecalam layout
		*/
		public function addJS($jsPath){
			$this->js[] = $jsPath;
		}

		/**
		* Fungsi untuk mencetak css yang telah di tambah custom ke dalam layout
		*/
		public function getCSS(){
			foreach ($this->css as $value) {
				echo '<link rel="stylesheet" href="'.BASE_URL.$value.'">'."\n";
			}
		}

		/**
		* Fungsi untuk mencetak js yang telah ditambah custom ke dalam layout
		*/
		public function getJS(){
			foreach ($this->js as $value) {
				echo '<script src="'.BASE_URL.$value.'"></script>'."\n";
			}
		}

		/**
		* Fungsi rendering layout yang telah di set komponen2nya sebelumnya
		*/
		public function render(){
			$this->setHeader();
			$this->setSidebar();
			$this->setFooter();		
			require_once ROOT.DS.'app'.DS.'views'.DS.'layout.php';
		}

	}