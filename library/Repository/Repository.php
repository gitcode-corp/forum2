<?php

require_once '/../DatabaseConnection.php';

class Repository
{
    /**
     * @var \mysqli
     */
    private $dbConnection;
    
    public function __construct()
    {
        $this->dbConnection = DatabaseConnection::connect();
    }
    
    public function fetchAll($sql)
    {
        $result = $this->query($sql);
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        
        return array();
    }
    
    public function fetchOne($sql)
    {
        $result = $this->query($sql);
        if ($result) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    protected function _insert($sql)
    {
        $this->query($sql);

        return $this->dbConnection->insert_id;
    }
    
    protected function _update($sql)
    {
        return $this->query($sql);
    }
    
    protected function _delete($sql)
    {
        return $this->query($sql);
    }
    
    private function query($sql)
    {
        $result = $this->dbConnection->query($sql);

        if ($this->dbConnection->error) {
            throw new \Exception("SQL error: " . $this->dbConnection->error , 500);
        }
        
        return $result;
    }
    
    protected function escapeString($string)
    {
        return $this->dbConnection->real_escape_string($string);
    }
}