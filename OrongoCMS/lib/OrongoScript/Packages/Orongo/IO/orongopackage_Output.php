<?php

/**
 * Output OrongoScript Package
 *
 * @author Jaco Ruit
 */
class OrongoScriptOutput extends OrongoPackage {
    
    public function __construct() {
        
    }
    public function getFunctions() {
        require_once("functions/func_Echo.php");
        return array(new FuncEcho());
    }
}

?>
