<?php
	/**
	* 
	*/
	class Api extends Controller{
		
		public function __construct(){
			$this->auth();
			$this->auth->cekAuthMobile();
		}

		

	}