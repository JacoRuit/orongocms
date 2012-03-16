<?php
/**
 * MySQL OrongoScript Package
 *
 * @author Jaco Ruit
 */
class OrongoScriptCrypto extends OrongoPackage {
    
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
        getDatabase()->query($args[0], $args[1]);
        if(count($args) < 1) throw new OrongoScriptParseException("Argument missing for Crypto.MD5()");     
        return new OrongoVariable(md5($args[0]));
    }

    public function getShortname() {
        return "Query";
    }
    
    public function getSpace(){
        return "MySQL";
    }
}

?>
