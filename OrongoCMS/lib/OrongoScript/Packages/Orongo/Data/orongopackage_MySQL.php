<?php

/**
 * LoadMySQL Class
 *
 * @author Jaco Ruit
 */
class MySQLPackage extends OrongoPackage {
    
    public function __construct() {
        
    }
    
    public function getFunctions() {
        require_once('func_MySQLQuery.php');
        return array(new FuncMySQLQuery());
    }
}

?>
