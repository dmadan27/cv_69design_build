<?php

Defined("BASE_PATH") or die(ACCESS_DENIED);

/**
 * Class Access_rightModel
 */
class Access_rightModel extends Database 
{

    private $connection;
    
    /**
     * Method __construct
     * Open connection to DB
     */
    public function __construct() {
        $this->connection = $this->openConnection();
    }
    
    /**
     * Method getAll_menu
     * Proses get semua menu yang tersedia di sistem
     * @return result {array}
     */
    public function getAll_menu() {
        $query = "SELECT name, url, icon FROM menu ORDER BY position ASC";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        return $result;
    }
    
    /**
     * 
     */
    public function getAll_menuByLevel($level) {
        $query = "SELECT * FROM v_access_menu WHERE level_name = :level AND position IS NOT NULL ORDER BY position ASC";
        $statement = $this->connection->prepare($query);
        $statement->execute(
            array(
                ':level' => $level
            )
        );
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        return $result;
    }

    /**
     * Method __destruct
     * Close connection to DB
     */
    public function __destruct() {
        $this->closeConnection($this->connection);
    }
}