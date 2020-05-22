<?php
interface ICrud{
    /**
     * Create a row in the database.
     * 
     * @param string $query the query to execute.
     * @param array $parameters associative array with the query parameters.
     */
    public function createRow($query, $parameters);
    /**
     * Read multiple rows.
     * 
     * @param string $query the query to execute.
     * @param array $parameters associative array with the query parameters.
     * @param string $keyName the name of the key for the resulting array.
     * @param string $class (optional) the class of the returning objects.
     */
    public function readMultiRows($query, $parameters, $keyName, $class);
    /**
     * Read a single row.
     * @param string $query the query to execute.
     * @param array $parameters associative array with the query parameters.
     * @param string $class (optional) the class of the returning objects.
     */
    public function readOneRow($query, $parameters, $class);
    /**
     * Update a value in a row.
     * 
     * @param string $query the query to execute.
     * @param array $parameters associative array with the query parameters.
     */
    public function updateRow($query, $parameters);
    /**
     * Delete a row.
     * 
     * @param string $query the query to execute.
     * @param array $parameters associative array with the query parameters.
     */
    public function deleteRows($query, $parameters);
}



?>