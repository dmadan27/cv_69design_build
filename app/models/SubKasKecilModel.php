<?php
class SubKasKecilModel extends Database{


	public function __construct(){
		// $this->openConnection();
	}


	public function getAll(){
			$data = array(
				array(
					'nama' => 'Ujang Jeprut',
					'umur' => 20,
				),
				array(
					'nama' => 'Ujang Jeprut',
					'umur' => 20,
				),
				array(
					'nama' => 'Ujang Jeprut',
					'umur' => 20,
				),
				array(
					'nama' => 'Ujang Jeprut',
					'umur' => 20,
				),
				array(
					'nama' => 'Ujang Jeprut',
					'umur' => 20,
				),
			);

			return $data;
		}


}