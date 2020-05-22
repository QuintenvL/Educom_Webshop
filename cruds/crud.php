<?php
include_once 'ICrud.php';
class Crud implements ICrud{
    private $pdo = null;
    /**
     * Connect to the database.
     */
    private function connectToDb()
    {
        $dbHost = 'localhost';
        $dbUsername = 'Admin';
        $dbPassword = '5pGcaSsZ7tSHI8bs';
        $dbName = 'quinten_webshop';
        $this->pdo = new PDO('mysql:host='.$dbHost.';dbname='.$dbName, $dbUsername, $dbPassword);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }
    
    /**
     * Prepare the query and bind values to the query.
     * 
     * @param string $sql the SQL string with for example ':email' for parameters
     * @param array  $bindParameters associative array ( 'email' => 'joe@a.b' );
     * @param string $class (optional) the class of the returning objects.
     * @return object the PDO object.
     */
    private function prepareAndBind($sql, $bindParameters, $class = '')
    {
        if ($this->pdo == null){$this->connectToDb();}
        foreach ($bindParameters as $key => $param){
            if (is_array($param)){
                $commaSepartedString = "";
                foreach ($param as $paramKey => $paramValue){
                    $aString = $key."_".$paramKey;
                    $commaSepartedString.=":".$aString.",";
                    $bindParameters[$aString] = $paramValue;
                }
                $commaSepartedString = substr($commaSepartedString, 0, -1);
                $sql = str_replace(":ids", $commaSepartedString, $sql);              
                unset($bindParameters["ids"]);
            }
        }
        $stmt = $this->pdo->prepare($sql);
        
        foreach ($bindParameters as $param=>$value){
            $stmt->bindValue(":".$param,$value);
        }
        if (!empty($class)){
            $stmt->setFetchMode(PDO::FETCH_CLASS, $class);
        }
        else {
            $stmt->setFetchMode(PDO::FETCH_OBJ);
        }
        $stmt->execute();
        return $stmt;
    }
    
    /**
     * Insert a row of values into the database
     *
     * @param string $sql the SQL string with for example ':email' for parameters
     * @param array  $bindParameters associative array ( 'email' => 'joe@a.b' );
     *
     * @return int the inserted id or 0 if failed
     * @throws PDOException when failed to complete the insert for technical reasons
     */
    public function createRow($sql, $bindParameters)
    {
        $this -> prepareAndBind($sql, $bindParameters);
        $insertId = $this->pdo->lastInsertId();
        
        return $insertId;
    }
    
    /**
     * Read multiple rows from the database.
     * 
     * @param string $sql the SQL string with for example ':email' for parameters
     * @param array  $bindParameters associative array ( 'email' => 'joe@a.b' );
     * @param string $keyName the name of the key for the resulting array.
     * @param string $class (optional) the class of the returning objects.
     * @return array all founded objects with the $keyname as key.
     * 
     * {@inheritDoc}
     * @see ICrud::readMultiRows()
     */
    public function readMultiRows($sql, $bindParameters, $keyName, $class)
    {
        $stmt = $this -> prepareAndBind($sql, $bindParameters, $class);
        
        $resultingArray = array();
        while ($row = $stmt->fetch()){
            $resultingArray[$row->$keyName] = $row;
        }
        return $resultingArray;
        
        
    }
    
    /**
     * Read a single row from the database.
     * 
     * @param string $sql the SQL string with for example ':email' for parameters
     * @param array  $bindParameters associative array ( 'email' => 'joe@a.b' );
     * @param string $class (optional) the class of the returning objects.
     * @return object|null the object of the founded result. Null when multiple or none objects were found.
     * 
     * {@inheritDoc}
     * @see ICrud::readOneRow()
     */
    public function readOneRow($sql, $bindParameters, $class)
    {
        $stmt = $this -> prepareAndBind($sql, $bindParameters, $class);
        $numberOfRows = $stmt->rowCount();
        if ($numberOfRows == 1){
            return $stmt->fetch();
        }
        return null;
    }
    
    /**
     * Update a row in the database.
     * 
     * @param string $sql the SQL string with for example ':email' for parameters
     * @param array  $bindParameters associative array ( 'email' => 'joe@a.b' );
     * @return integer the number of affected rows. 
     * 
     * {@inheritDoc}
     * @see ICrud::updateRow()
     */
    public function updateRow($sql, $bindParameters)
    {
        $stmt = $this -> prepareAndBind($sql, $bindParameters);
        return $stmt->rowCount();
    }
    
    /**
     * Delete rows from the database.
     *
     * @param string $sql the SQL string with for example ':email' for parameters
     * @param array  $bindParameters associative array ( 'email' => 'joe@a.b' );
     * @return integer the number of affected rows.
     *
     * {@inheritDoc}
     * @see ICrud::deleteRows()
     */
    public function deleteRows($sql, $bindParameters)
    {
        $stmt = $this -> prepareAndBind($sql, $bindParameters);
        return $stmt->rowCount();
    }
}

?>