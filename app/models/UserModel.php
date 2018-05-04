<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	* 
	*/
	class UserModel extends Database{
		
		protected $koneksi;
		protected $dataTable;

		/**
		* 
		*/
		public function __construct(){
			$this->koneksi = $this->openConnection();
		}

		/**
		* 
		*/
		public function getUser($username){
			$query = "SELECT * FROM user WHERE BINARY username = :username";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':username', $username);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

		/**
		* 
		*/
		public function getKasBesar($username){
			$query = "SELECT kb.id, kb.nama, kb.alamat, kb.email, kb.foto, kb.status ";
			$query .= "FROM user u JOIN kas_besar kb ON kb.email = u.username WHERE u.username = :username";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':username', $username);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

		/**
		* 
		*/
		public function getKasKecil($username){
			$query = "SELECT kk.id, kk.nama, kk.alamat, kk.email, kk.foto, kk.saldo, kk.status ";
			$query .= "FROM user u JOIN kas_kecil kk ON kk.email = u.username WHERE u.username = :username";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':username', $username);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}		
	}