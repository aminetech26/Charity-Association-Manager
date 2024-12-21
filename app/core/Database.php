<?php 
//defined('ROOTPATH') OR exit('Access Denied!');
Trait Database
{
    private static $connection = null;

    private function connect()
    {
        if(self::$connection === null) {
            $string = "mysql:hostname=".DBHOST.";dbname=".DBNAME;
            self::$connection = new PDO($string, DBUSER, DBPASS);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$connection;
    }

    public function query($query, $data = [])
    {
        $con = $this->connect();
        $stm = $con->prepare($query);

        $check = $stm->execute($data);
        if($check)
        {
            $result = $stm->fetchAll(PDO::FETCH_OBJ);
            if(is_array($result) && count($result))
            {
                return $result;
            }
        }

        return false;
    }

    public function get_row($query, $data = [])
    {
        $con = $this->connect();
        $stm = $con->prepare($query);

        $check = $stm->execute($data);
        if($check)
        {
            $result = $stm->fetchAll(PDO::FETCH_OBJ);
            if(is_array($result) && count($result))
            {
                return $result[0];
            }
        }

        return false;
    }

    public function beginTransaction() {
        $con = $this->connect();
        if (!$con->inTransaction()) {
            $con->beginTransaction();
        }
    }
    
    public function commit() {
        $con = $this->connect();
        if ($con->inTransaction()) {
            $con->commit();
        }
    }
    
    public function rollback() {
        $con = $this->connect();
        if ($con->inTransaction()) {
            $con->rollBack();
        }
    }
}