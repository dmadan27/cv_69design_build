<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	 * 
	 */
	class UserModel extends Database implements ModelInterface 
	{
		
		private $koneksi;

		/**
		 * 
		 */
		public function __construct(){
			$this->koneksi = $this->openConnection();
		}

		/**
		 * 
		 */
		public function getAll(){
			
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
		public function getOwner($username){
			$query = "SELECT * FROM v_user_owner WHERE BINARY username = :username;";

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
			$query = "SELECT * FROM v_user_kas_besar WHERE BINARY username = :username;";

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
			$query = "SELECT * FROM v_user_kas_kecil WHERE BINARY username = :username;";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':username', $username);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

		/**
		 * 
		 */
		public function getById($id){
			$query = "SELECT * FROM user WHERE BINARY username = :username;";

			$statement = $this->koneksi->prepare($query);
			$statement->bindParam(':username', $id);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);

			return $result;
		}

		/**
		 * 
		 */
		public function insert($data){
			// $query = "INSERT INTO bank (nama, saldo, status) VALUES (:nama, :saldo, :status);";

			// $statement = $this->koneksi->prepare($query);
			// $statement->bindParam(':nama', $data['nama']);
			// $statement->bindParam(':saldo', $data['saldo']);
			// $statement->bindParam(':status', $data['status']);
			// $result = $statement->execute();

			// return $result;
		}

		/**
		 * 
		 */
		public function update($data){
			// $query = "UPDATE bank SET nama = :nama, status = :status WHERE id = :id;";

			// $statement = $this->koneksi->prepare($query);
			// $statement->bindParam(':nama', $data['nama']);
			// $statement->bindParam(':status', $data['status']);
			// $statement->bindParam(':id', $data['id']);
			// $result = $statement->execute();

			// return $result;
		}

		/**
		 * 
		 */
		public function updatePassword($data, $lupa_password = false){
			$query = "UPDATE user SET password = :password, modified_by = :modified_by WHERE username = :username";

			try{
				$this->koneksi->beginTransaction();
				
				$statement = $this->koneksi->prepare($query);
				$statement->execute(
					array(
						':password' => $data['password'],
						':username' => $data['username'],
						':modified_by' => $_SESSION['sess_email']
					)
				);
				$statement->closeCursor();

				if($lupa_password) $this->delete_lupa_password($data['username']);

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
		private function delete_lupa_password($user){
			$query = "DELETE FROM token_lupa_password WHERE username = :username";

			$statement = $this->koneksi->prepare($query);	
			$statement->execute(array(':username' => $user));

			$statement->closeCursor();
		}

		/**
		 * 
		 */
		public function delete($id){
			// $query = "DELETE FROM bank WHERE id = :id";
			
			// $statement = $this->koneksi->prepare($query);
			// $statement->bindParam(':id', $id);
			// $result = $statement->execute();

			// return $result;
		}		

		/**
		 * 
		 */
		public function __destruct(){
			$this->closeConnection($this->koneksi);
		}
	}