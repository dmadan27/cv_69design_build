<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	* 
	*/
	class TokenModel extends Database{
		
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
		public function getToken($user){
			$query = "SELECT t.token, t.tgl_exp FROM token_sub_kas_kecil t ";
			$query .= "JOIN sub_kas_kecil skc ON skc.id = t.id_sub_kas_kecil ";
			$query .= "WHERE skc.email = :user";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':user', $user);
			$result = $statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

		/**
		* 
		*/
		public function insert($data){
			$query = "INSERT INTO token_sub_kas_kecil (id_sub_kas_kecil, token, tgl_buat, tgl_exp) ";
			$query .= "VALUES (:id_sub_kas_kecil, :token, :tgl_buat, :tgl_exp)";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id_sub_kas_kecil', $data['id_sub_kas_kecil']);
			$statement->bindParam(':token', $data['token']);
			$statement->bindParam(':tgl_buat', $data['tgl_buat']);
			$statement->bindParam(':tgl_exp', $data['tgl_exp']);	
			
			$result = $statement->execute();

			return $result;
		}

		/**
		* 
		*/
		public function delete($id){
			$query = "DELETE FROM token_sub_kas_kecil WHERE id_sub_kas_kecil = :id";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':id', $id);	
			
			$result = $statement->execute();

			return $result;
		}

		/**
		* 
		*/
		public function __destruct(){
			$this->closeConnection($this->koneksi);
		}
	}
