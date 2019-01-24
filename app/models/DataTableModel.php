<?php
	Defined("BASE_PATH") or die(ACCESS_DENIED);

	/**
	 * Class DataTableModel
	 */
    class DataTableModel extends Database
    {

		protected $connection;
		protected $dataTable;

		/**
		 * Method __construct
		 * Open connection to DB
		 * Access library dataTable
		 */
		public function __construct(){
			$this->koneksi = $this->openConnection();
			$this->dataTable = new Datatable();
		}

		/**
         * Method getAllDataTable
         * @param config {array}
         * @return result {array}
         */
        public function getAllDataTable($config){
            $this->dataTable->set_config($config);
            $statement = $this->koneksi->prepare($this->dataTable->getDataTable());
            $statement->execute();
            $result = $statement->fetchAll();

            return $result;
        }

        /**
         * Method recordFilter
         * @return result {int}
         */
        public function recordFilter(){
            return $this->dataTable->recordFilter();

        }

        /**
         * Method recordTotal
         * @return result {int}
         */
        public function recordTotal(){
            return $this->dataTable->recordTotal();
        }

		/**
		 * Method __destruct
		 * Close connection to DB
		 */
		public function __destruct(){
			$this->closeConnection($this->koneksi);
		}

	}