<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
	
	/**
	* Class DataTable Server Side
	*/
	class Datatable extends Database{
		
		protected $tabel; // nama tabel
		protected $kolomOrder; // kolom2 yg akan di order
		protected $kolomCari; // kolom2 yg akan dicari
		protected $orderBy; // jenis pengurutan data
		protected $kondisi; // where clause
		protected $query; // query

		/**
		* Hal pertama kali yang dijalankan adalah set property sesuai dengan $config yg dikirim
		* format congig ada 5 poin penting
		* tabel => berupa string, nama tabel atau view
		* kolomOrder => berupa array, yang isinya harus disesuaikan dengan tabel yg dibuat di view
		* kolomCari => berupa array, apa saja yang dapat dicari
		* orderBy => berupa array dan memakai key, 
		* key berupa apa yg di order, dan valuenya jenis order
		* kondisi => berupa string, yaitu Where manual
		*/
		final public function set_config($config){
			// set tabel
			$this->tabel = $config['tabel'];
			// set kolom order
			$this->kolomOrder = $config['kolomOrder'];
			// set kolom cari
			$this->kolomCari = $config['kolomCari'];
			// set order by
			$this->orderBy = $config['orderBy'];
			// set kondisi
			$this->kondisi = $config['kondisi'];
		}

		/**
		* Fungsi set query awal default untuk datatable
		*/
		final public function setDataTable(){
			$search = isset($_POST['search']['value']) ? $_POST['search']['value'] : false;
			$order = isset($_POST['order']) ? $_POST['order'] : false;

			// $this->query = "SELECT * FROM $this->tabel ";
			$query = "SELECT * FROM $this->tabel ";

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

			return $query .= "$qWhere $qOrder "; 
			// $this->query .= "$qWhere $qOrder ";
		}

		/**
		* fungsi untuk get query datatable komplit
		*/
		final public function getDataTable(){
			// $this->setDataTable();
			$this->query = $this->setDataTable();

			$qLimit = "";
			if($_POST['length'] != -1) $qLimit .= 'LIMIT '.$_POST['start'].', '.$_POST['length'];
			
			$this->query .= "$qLimit";
			
			return $this->query; 
		}

		/**
		* untuk mendapatkan filter record
		* sebagai pendukung dalam pagenation datatable
		*/
		final public function recordFilter(){
			$koneksi = $this->openConnection();

			// $statement = $koneksi->prepare($this->query);
			$statement = $koneksi->prepare($this->setDataTable());
			$statement->execute();

			return $statement->rowCount();
		}

		/**
		* untuk mendapatkan semua jumlah data
		* sebagai pendukung dalam pagenation datatable
		*/
		final public function recordTotal(){
			$koneksi = $this->openConnection();

			if($this->kondisi === false) $statement = $koneksi->query("SELECT COUNT(*) FROM $this->tabel")->fetchColumn();
			else $statement = $koneksi->query("SELECT COUNT(*) FROM $this->tabel $this->kondisi")->fetchColumn();
			
			return $statement;
		}

	}