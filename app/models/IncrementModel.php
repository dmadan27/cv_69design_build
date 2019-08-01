<?php
    
Defined("BASE_PATH") or die(ACCESS_DENIED);

/**
 * Class IncrementModel
 */
class IncrementModel extends Database 
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
     * 
     */
    public function get_increment($name) {
        $increment = $this->lastIncrement($name);
        $mask = $this->getMask($name);

        if($increment['success']) {
            $result = array(
                'increment' => $increment['data']['increment'],
                'mask' => $mask['mask'],
                'success' => true,
                'error' => null
            );
        }
        else {
            $result = array(
                'success' => false,
                'error' => $increment['error']
            );
        }

        return $result;
    }

    /**
     * Method getIncrement
     * Get Last Increment
     * @param name {string} nama module/menu yang didaftarkan di increment
     */
    private function lastIncrement($name) {
        $query = "SELECT f_get_increment(:name) increment";
        try {
            $this->connection->beginTransaction();
            $statement = $this->connection->prepare($query);
            $statement->execute(
                array(
                    ':name' => $name
                )
            );
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $statement->closeCursor();
            $this->connection->commit();
            
            return array(
                'success' => true,
                'data' => $result,
                'error' => null
            );
        }
        catch(PDOException $e) {
            $this->connection->rollback();
            return array(
                'success' => false,
                'error' => $e->getMessage()
            );
        }
    }

    /**
     * 
     */
    private function getMask($name) {
        $query = "SELECT mask FROM v_increment WHERE table_name = :name";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':name', $name);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        
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