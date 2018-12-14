<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	 * Interface untuk model
	 */
	interface ModelInterface{
		
		/**
		 * 
		 */
		public function getAll();

		/**
		 * 
		 */
		public function getById($id);

		/**
		 * 
		 */
		public function insert($data);

		/**
		 * 
		 */
		public function update($data);

		/**
		 * 
		 */
		public function delete($id);
		
	}