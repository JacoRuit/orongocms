<?php
/**
 * Time OrongoScript Package
 *
 * @author Jaco Ruit
 */
class OrongoScriptTime extends OrongoPackage {
    
    public function __construct($runtime) {
        
    }
    public function getFunctions() {
        return array(new FuncGetUnixTime());
    }
}



/**
 * GetUnixTime OrongoScript function
 *
 * @author Jaco Ruit
 */
class FuncGetUnixTime extends OrongoFunction {
    

    public function __invoke($args) {
        return new OrongoVariable(time());
    }

    public function getShortname() {
        return "GetUnixTime";
    }
    
    public function getSpace(){
        return "Time";
    }
}

?>
