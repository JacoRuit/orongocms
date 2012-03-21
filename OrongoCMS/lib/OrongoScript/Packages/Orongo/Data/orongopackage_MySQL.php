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
        return array(new FuncQuery());
    }
}



/**
 * Query OrongoScript function
 *
 * @author Jaco Ruit
 */
class FuncQuery extends OrongoFunction {
    

    public function __invoke($args) {
        if(count($args) < 1) throw new OrongoScriptParseException("Arguments missing for MySQL.Query()");  
        $query = $args[0];
        unset($args[0]);
        $args = count($args) > 1 ? $args : end($args);
        $rows = getDatabase()->query($query, $args);  
        foreach($rows as &$row){
            $row = new OrongoVariable(end($row));
        }
        return $rows;
    }

    public function getShortname() {
        return "Query";
    }
    
    public function getSpace(){
        return "MySQL";
    }
}

?>
