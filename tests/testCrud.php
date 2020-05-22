<?php
include_once __DIR__.'/../cruds/ICrud.php';
class TestCrud implements ICrud {
    public $arrayToReturn = array();
    public $objToReturn = NULL;
    public $sqlQueries = array();
    public $bindValues = array();
    
    function createRow($query, $parameters){
        array_push($this->sqlQueries, $query);
        array_push($this->bindValues, $parameters);
        return 2;
    }
    function readMultiRows($query, $parameters, $keyName, $class){
        array_push($this->sqlQueries, $query);
        array_push($this->bindValues, $parameters);
        return $this->arrayToReturn;
    }
    function readOneRow($query, $parameters, $class){
        array_push($this->sqlQueries, $query);
        array_push($this->bindValues, $parameters);
        return $this->objToReturn;
    }
    function updateRow($query, $parameters){
        array_push($this->sqlQueries, $query);
        array_push($this->bindValues, $parameters);
        return 1;
    }
    function deleteRows($query, $parameters){
        array_push($this->sqlQueries, $query);
        array_push($this->bindValues, $parameters);
        return 1;
    }
    
    
}
?>