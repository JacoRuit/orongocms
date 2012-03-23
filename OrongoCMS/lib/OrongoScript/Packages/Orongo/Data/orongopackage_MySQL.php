<?php
/**
 * MySQL OrongoScript Package
 *
 * @author Jaco Ruit
 */
class OrongoScriptMySQL extends OrongoPackage {
    
    public function __construct($runtime) {
        
    }
    public function getFunctions() {
        return array(new FuncMySQLQuery());
    }
}



/**
 * Query OrongoScript function
 *
 * @author Jaco Ruit
 */
class FuncMySQLQuery extends OrongoFunction {
    

    public function __invoke($args) {
        if(count($args) < 1) throw new OrongoScriptParseException("Arguments missing for MySQL.Query()");  
        $query = $args[0];
        unset($args[0]);
        $args = count($args) > 1 ? $args : end($args);
        $rows = getDatabase()->query($query, $args);  
        foreach($rows as &$row){
            if(is_array($row)) $row = new OrongoList($row);
        }
        return new OrongoList($rows);
    }

    public function getShortname() {
        return "Query";
    }
    
    public function getSpace(){
        return "MySQL";
    }
}

?>
