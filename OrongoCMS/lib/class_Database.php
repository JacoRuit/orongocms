<?php

/**
 * Database Class
 *
 * @author Jaco Ruit
 */

function dbErrorHandler($args){
    $msgb = new MessageBox("SQL Error - " . $args['error'] . " <br/><br /><strong>Query</strong><br />" . $args['query']);
    die($msgb->getImports() . $msgb->toHTML());
}

class Database {
    
    private $meekrodb = null;
    
    /**
     * Establish MySQL Connection
     * @param String $paramConfigPath Path of the config file
     * @author Jaco Ruit
     */
    public function __construct($paramConfigPath){
        require$paramConfigPath;
        $this->meekrodb = new MeekroDB($_CONFIG['db']['server'], $_CONFIG['db']['username'], $_CONFIG['db']['password'], $_CONFIG['db']['name']);
        $this->meekrodb->error_handler = "dbErrorHandler";
        /** $this->conn = mysql_connect($_CONFIG['db']['server'], $_CONFIG['db']['username'], $_CONFIG['db']['password']) ||  die ('Could not establish a database connection');
        mysql_select_db($_CONFIG['db']['name']) ||  die ('Could not establish a database connection'); **/
    }   
    
    public function getMeekro(){
        return $this->meekrodb;
    }
    
    
    /**
    /**
     * @param String $paramQuery MySQL Query String
     * @return resources Result from MySQL
     */
   /** public function execQuery($paramQuery){
        self::$queryCount++;
        $res = @mysql_query($paramQuery);
        if(!$res){
            $msgbox = new MessageBox("Couldn't execute a mysql query: " . mysql_error() . "<br/><br/><strong>Query</strong><br />" . $paramQuery);
            if(!function_exists('getDisplay')) throw new Exception("Couldn't execute a mysql query: " . mysql_error());
            getDisplay()->addObject($msgbox);
        }
        return $res;
    }
    
    /**
     * @param String $paramTable table name
     * @param array $paramFields fields in database
     */
   /** public function createTable($paramTable, $paramFields){
        //TODO make this.
    }**/
    
}

?>
