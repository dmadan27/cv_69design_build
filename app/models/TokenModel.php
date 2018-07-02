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
		public function getToken_lupa_password($user){
			$query = "SELECT t.token, t.tgl_exp FROM token_lupa_password t ";
			$query .= "JOIN user u ON u.username = t.username ";
			$query .= "WHERE u.username = :user";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':user', $user);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

		/**
		*
		*/
		public function setToken_lupa_password($data){
			try{
				$this->koneksi->beginTransaction();
				
				// hapus token lama
				$this->delete_lupa_password($data['username']);

				// tambah token baru
				$this->insert_lupa_password($data);

				$this->koneksi->commit();

				return true;
			}
			catch(PDOException $e){
				$this->koneksi->rollback();
				die($e->getMessage());
				// return false;
			}
		}

		/**
		* 
		*/
		private function insert_lupa_password($data){
			$query = "INSERT INTO token_lupa_password (username, token, tgl_buat, tgl_exp) ";
			$query .= "VALUES (:username, :token, :tgl_buat, :tgl_exp)";

			$statement = $this->koneksi->prepare($query);
			$statement->execute(
				array(
					':username' => $data['username'],
					':token' => $data['token'],
					':tgl_buat' => $data['tgl_buat'],
					':tgl_exp' => $data['tgl_exp'],
				)
			);
			
			$statement->closeCursor();
		}

		/**
		* 
		*/
		private function delete_lupa_password($user){
			$query = "DELETE FROM token_lupa_password WHERE username = :username";

			$statement = $this->koneksi->prepare($query);	
			$statement->execute(array(':username' => $user));

			$statement->closeCursor();
		}

		// ======================== mobile ========================= //

			/**
			* 
			*/
			public function getToken_mobile($user){
				$query = "SELECT t.token, t.tgl_exp FROM token_sub_kas_kecil t ";
				$query .= "JOIN sub_kas_kecil skc ON skc.id = t.id_sub_kas_kecil ";
				$query .= "WHERE skc.email = :user";

				$statement = $this->koneksi->prepare($query);
				$statement->bindParam(':user', $user);
				$statement->execute();
				$result = $statement->fetch(PDO::FETCH_ASSOC);

				return $result;
			}

			/**
			*
			*/
			public function setToken_mobile($data){
				try{
					$this->koneksi->beginTransaction();
					
					// hapus token lama
					$this->delete_mobile($data['username']);

					// tambah token baru
					$this->insert_mobile($data);

					$this->koneksi->commit();

					return true;
				}
				catch(PDOException $e){
					$this->koneksi->rollback();
					die($e->getMessage());
					// return false;
				}
			}

			/**
			* 
			*/
			private function insert_mobile($data){
				$query = "INSERT INTO token_mobile (username, token, tgl_buat, tgl_exp) ";
				$query .= "VALUES (:username, :token, :tgl_buat, :tgl_exp)";

				$statement = $this->koneksi->prepare($query);
				$statement->execute(
					array(
						':username' => $data['username'],
						':token' => $data['token'],
						':tgl_buat' => $data['tgl_buat'],
						':tgl_exp' => $data['tgl_exp'],
					)
				);
				
				$statement->closeCursor();
			}

			/**
			* 
			*/
			private function delete_mobile($user){
				$query = "DELETE FROM token_mobile WHERE username = :username";

				$statement = $this->koneksi->prepare($query);
				$statement->execute(array(':username' => $user));

				$statement->closeCursor();
			}

		// ========================================================= //

		/**
		* 
		*/
		public function __destruct(){
			$this->closeConnection($this->koneksi);
		}
	}
