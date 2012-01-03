<?php

/**
 * Database Object
 *
 * @author Jaco Ruit
 */
class Database {
    private $conn;
    
    /**
     * Establish MySQL Connection
     * @param String $paramConfigPath Path of the config file
     * @author Jaco Ruit
     */
    public function __construct($paramConfigPath){
        require$paramConfigPath;
        $this->conn = mysql_connect($_CONFIG['db']['server'], $_CONFIG['db']['username'], $_CONFIG['db']['password']) ||  die ('Could not establish a database connection');
        mysql_select_db($_CONFIG['db']['name']) ||  die ('Could not establish a database connection'); 
    }   
    
    /**
     * @return MySQL Connection
     */
    public function getConnection(){
        return $this->conn;
    }
}

?>
