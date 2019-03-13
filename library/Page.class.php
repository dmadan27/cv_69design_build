<?php

Defined("BASE_PATH") or die(ACCESS_DENIED);

/**
 * Class Page
 * Proses untuk layouting header, sidebar, content dan footer
 * Menambah CSS, JS, Title, Content, dan set Data secara dinamis
 */
class Page
{

	private $title;
    private $propertyPage = array();
    private $header;
    private $sidebar;
    private $content;
    private $footer;
    private $css = array();
    private $js = array();
    private $data;

	/**
     * Method setTitle
     * Proses set title html
     * @param title {string}
     */
    public function setTitle($title) {
        $this->title = $title;
    }

	/**
     * Method setProperty
     * Proses set main title dan sub title di suatu page
     * @param property {array}
     */
    public function setProperty($property) {
        $this->propertyPage['main'] = $property['main'];
        $this->propertyPage['sub'] = $property['sub'];
    }

	/**
	 * Method setHeader
	 * Fungsi untuk set header
	 */
	public function setHeader(){
		ob_start();
		require_once ROOT.DS.'app'.DS.'views'.DS.'layout'.DS.'header.php';
		$this->header = ob_get_clean();
	}

	/**
	 * Method setSidebar
	 * Fungsi untuk set sidebar
	 */
	public function setSidebar(){
		ob_start();
		require_once ROOT.DS.'app'.DS.'views'.DS.'layout'.DS.'sidebar.php';
		$this->sidebar = ob_get_clean();
	}

	/**
     * 
     */
    public function getSidebar($type = 'side') {
        $sess_menu = isset($_SESSION['sess_menu']) ? $_SESSION['sess_menu'] : false;

        if($sess_menu) {
            if($type == 'side') {
                echo '<div class="main-sidebar sidebar-style-2">';
                echo '<aside id="sidebar-wrapper">';
                echo '<div class="sidebar-brand"><a href="<?= BASE_URL ?>">Timesheet</a></div>';
                echo '<div class="sidebar-brand sidebar-brand-sm"><a href="<?= BASE_URL ?>">TS</a></div>';
                echo '<ul class="sidebar-menu">';
                foreach($sess_menu as $menu) {
                    echo '<li class="'.$menu['class'].'">';
                    echo '<a href="'.BASE_URL.$menu['url'].'" class="nav-link"><i class="'.$menu['icon'].'"></i><span>'.$menu['menu_name'].'</span></a>';
                    echo '</li>';
                }
                echo '</ul></aside></div>';
            }
            else {
                echo '<nav class="navbar navbar-secondary navbar-expand-lg">';
                echo '<div class="container">';
                echo '<ul class="navbar-nav">';
                foreach($sess_menu as $menu) {
                    echo '<li class="nav-item '.$menu['class'].'">';
                    echo '<a href="'.BASE_URL.$menu['url'].'" class="nav-link"><i class="'.$menu['icon'].'"></i><span>'.$menu['menu_name'].'</span></a>';
                    echo '</li>';
                }
                echo '</ul></div></nav>';
            }
        }  
    }

	/**
	 * Method setContent
	 * Fungsi untuk set content
	 * @param content
	 */
	public function setContent($content){
		$contentExplode = explode('/', $content);
        
        $newContent = '';
        for($i=0; $i<count($contentExplode); $i++) {
            if((count($contentExplode)-$i!=1)) { $newContent .= $contentExplode[$i].DS; }
            else { $newContent .= $contentExplode[$i]; }
        }

        ob_start();
        require_once ROOT.DS.'app'.DS.'views'.DS.$newContent.'.php';
        $this->content = ob_get_clean();
	}

	/**
	 * Method setData
	 * Fungsi untuk set data
	 * @param data {array}
	 */
	public function setData($data){
		$this->data = $data;
	}

	/**
	 * Method setFooter
	 * Fungsi untuk set footer
	 */
	public function setFooter(){
		ob_start();
		require_once ROOT.DS.'app'.DS.'views'.DS.'layout'.DS.'footer.php';
		$this->footer = ob_get_clean();
	}

	/**
	 * Method addCSS
	 * Fungsi untuk menambah css custom kedalam layout
	 * @param cssPath {string}
	 */
	public function addCSS($cssPath){
		$this->css[] = $cssPath;
	}

	/**
	 * Method addJS
	 * Fungsi untuk menambah js custom kecalam layout
	 * @param jsPath {string}
	 */
	public function addJS($jsPath){
		$this->js[] = $jsPath;
	}

	/**
	 * Method getCSS
	 * Fungsi untuk mencetak css yang telah di tambah custom ke dalam layout
	 */
	public function getCSS(){
		foreach ($this->css as $value) {
			echo '<link rel="stylesheet" href="'.BASE_URL.$value.'">'."\n";
		}
	}

	/**
	 * Method getJS
	 * Fungsi untuk mencetak js yang telah ditambah custom ke dalam layout
	 */
	public function getJS(){
		foreach ($this->js as $value) {
			echo '<script src="'.BASE_URL.$value.'"></script>'."\n";
		}
	}

	/**
	 * Method render
	 * Fungsi rendering layout yang telah di set komponen2nya sebelumnya
	 */
	public function render(){
		$this->setHeader();
		$this->setSidebar();
		$this->setFooter();		
		require_once ROOT.DS.'app'.DS.'views'.DS.'layout.php';
	}

}