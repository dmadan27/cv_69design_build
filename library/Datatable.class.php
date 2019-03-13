<?php

Defined("BASE_PATH") or die(ACCESS_DENIED);

/**
 * Class DataTable Server Side
 */
class Datatable extends Database
{
	
	private $table; // nama tabel
	private $orderBy; // kolom2 yg akan di order
	private $searchBy; // kolom2 yg akan dicari
	private $orderType; // jenis pengurutan data
	private $conditional; // where clause
	private $query; // query

	/**
	 * Method set_config
	 * @param config {array}
	 * 
	 * Hal pertama kali yang dijalankan adalah set property sesuai dengan $config yg dikirim
	 * format congig ada 5 poin penting
	 * tabel => berupa string, nama tabel atau view
	 * kolomOrder => berupa array, yang isinya harus disesuaikan dengan tabel yg dibuat di view
	 * kolomCari => berupa array, apa saja yang dapat dicari
	 * orderBy => berupa array dan memakai key, 
	 * key berupa apa yg di order, dan valuenya jenis order
	 * kondisi => berupa string, yaitu Where manual
	 */
	final public function set_config($config) {
		// set tabel
		$this->table = $config['tabel'];
		// set kolom order
		$this->orderBy = $config['kolomOrder'];
		// set kolom cari
		$this->searchBy = $config['kolomCari'];
		// set order by
		$this->orderType = $config['orderBy'];
		// set kondisi
		$this->conditional = $config['kondisi'];
	}

	/**
	 * Method setDataTable
	 * Proses set query awal default untuk datatable
	 * @return query {string}
	 */
	final private function setDataTable() {
		$search = isset($_POST['search']['value']) ? $_POST['search']['value'] : false;
		$order = isset($_POST['order']) ? $_POST['order'] : false;

		// $this->query = "SELECT * FROM $this->tabel ";
		$query = "SELECT * FROM $this->table ";

		if($this->conditional === false) {
			// jika ada request pencarian
			$qWhere = "";
			$i = 0;
			foreach($this->searchBy as $cari) {
				if($search) {
					if($i === 0) $qWhere .= 'WHERE '.$cari.' LIKE "%'.$search.'%" ';
					else $qWhere .= 'OR '.$cari.' LIKE "%'.$search.'%"';
				}
				$i++;
			}
		}
		else{
			// jika ada request pencarian
			$qWhere = $this->conditional;
			$i = 0;
			foreach($this->searchBy as $cari) {
				if($search) {
					if($i === 0) { $qWhere .= ' AND ('.$cari.' LIKE "%'.$search.'%" '; }
					else { $qWhere .= 'OR '.$cari.' LIKE "%'.$search.'%"'; }
				}
				$i++;
			}
			if($search) { $qWhere .= " )"; }
		}

		// jika ada request order
		$qOrder = "";
		if($order) { $qOrder = 'ORDER BY '.$this->orderBy[$order[0]['column']].' '.$order[0]['dir'].' '; }
		else {
			if($this->orderType === false) { $qOrder = ""; }
			else { $qOrder = 'ORDER BY '.key($this->orderType).' '.$this->orderType[key($this->orderType)]; } // order default
		}

		return $query .= "$qWhere $qOrder ";
	}

	/**
	 * Method getDataTable
	 * Proses untuk get query datatable full
	 * @return query {string}
	 */
	final public function getDataTable(){
		$this->query = $this->setDataTable();

		$qLimit = "";
		if($_POST['length'] != -1) { $qLimit .= 'LIMIT '.$_POST['start'].', '.$_POST['length']; }
		
		$this->query .= "$qLimit";
		
		return $this->query; 
	}

	/**
	 * Method recordFilter
	 * untuk mendapatkan filter record
	 * sebagai pendukung dalam pagenation datatable
	 * @return rowCount {int}
	 */
	final public function recordFilter(){
		$koneksi = $this->openConnection();

		// $statement = $koneksi->prepare($this->query);
		$statement = $koneksi->prepare($this->setDataTable());
		$statement->execute();

		return $statement->rowCount();
	}

	/**
	 * Method recordTotal
	 * untuk mendapatkan semua jumlah data
	 * sebagai pendukung dalam pagenation datatable
	 * @return recordTotal {int}
	 */
	final public function recordTotal(){
		$koneksi = $this->openConnection();

		if($this->conditional === false) $statement = $koneksi->query("SELECT COUNT(*) FROM $this->table")->fetchColumn();
		else $statement = $koneksi->query("SELECT COUNT(*) FROM $this->table $this->conditional")->fetchColumn();
		
		return $statement;
	}

}