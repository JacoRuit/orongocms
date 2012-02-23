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
    }   
    
    /**
     * @return MeekroDB
     */
    public function getMeekro(){
        return $this->meekrodb;
    }

}

?>
