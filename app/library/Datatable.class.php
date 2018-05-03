<?php
	/**
	* 
	*/
	class Datatable extends Database{
		
		protected $tabel;
		protected $kolomOrder;
		protected $kolomCari;
		protected $orderBy;
		protected $kondisi;
		protected $query;

		public function __construct($config){
			// set tabel
			$this->table = $config['tabel'];
			// set kolom order
			$this->kolomOrder = $config['kolomOrder'];
			// set kolom cari
			$this->kolomCari = $config['kolomCari'];
			// set order by
			$this->orderBy = $config['orderBy'];
			// set kondisi
			$this->kondisi = $config['kondisi'];
		}

		final public function setDataTable(){
			$search = isset($_POST['search']['value']) ? $_POST['search']['value'] : false;
			$order = isset($_POST['order']) ? $_POST['order'] : false;

			$this->query = "SELECT * FROM $this->table ";

			if($this->kondisi === false){
				// jika ada request pencarian
				$qWhere = "";
				$i = 0;
				foreach($this->kolomCari as $cari){
					if($search){
						if($i === 0) $qWhere .= 'WHERE '.$cari.' LIKE "%'.$search.'%" ';
						else $qWhere .= 'OR '.$cari.' LIKE "%'.$search.'%"';
					}
					$i++;
				}
			}
			else{
				// jika ada request pencarian
				$qWhere = $this->kondisi;
				$i = 0;
				foreach($this->kolomCari as $cari){
					if($search){
						if($i === 0) $qWhere .= ' AND ('.$cari.' LIKE "%'.$search.'%" ';
						else $qWhere .= 'OR '.$cari.' LIKE "%'.$search.'%"';
					}
					$i++;
				}
				if($search) $qWhere .= " )";
			}

			// jika ada request order
			$qOrder = "";
			if($order) $qOrder = 'ORDER BY '.$this->kolomOrder[$order[0]['column']].' '.$order[0]['dir'].' ';
			else {
				if($this->orderBy === false) $qOrder = "";
				else $qOrder = 'ORDER BY '.key($this->orderBy).' '.$this->orderBy[key($this->orderBy)]; // order default
			}

			$this->query .= "$qWhere $qOrder ";
		}

		final public function getDataTable(){
			$this->setDataTable();
			$qLimit = "";
			if($_POST['length'] != -1) $qLimit .= 'LIMIT '.$_POST['start'].', '.$_POST['length'];
		
			return $this->query .= "$qLimit";
		}

		final public function recordFilter(){
			$koneksi = $this->openConnection();
			$statement = $koneksi->prepare($this->setDataTable(););
			$statement->execute();

			return $statement->rowCount();
		}

		final public function recordTotal(){
			$koneksi = $this->openConnection();

			if($this->kondisi === false) $statement = $koneksi->query("SELECT COUNT(*) FROM $this->tabel")->fetchColumn();
			else $statement = $koneksi->query("SELECT COUNT(*) FROM $this->tabel $this->kondisi")->fetchColumn();
			
			return $statement;
		}

	}