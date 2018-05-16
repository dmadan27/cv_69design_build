<?php
class Operasional_Proyek extends Crud_modalAbstract{

	public function __construct(){
			$this->auth();
			$this->auth->cekAuth();
			$this->model('BankModel');
			$this->helper();
			$this->validation();
	}

	public function index(){

	}

	public function list(){

	}

	public function action_add(){

	}

	public function get_list(){

	}

	public function edit(){

	}

	public function action_edit(){

	}

	public function detail(){

	}

	public function delete(){

	}

	public function export(){

	}

	private function set_validation(){
		
	}	

}