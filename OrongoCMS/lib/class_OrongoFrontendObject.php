<?php

/**
 * OrongoFrontendObject Class
 *
 * @author Jaco Ruit
 */
abstract class OrongoFrontendObject {
    
    abstract public function main($paramArgs);
    
    abstract public function render();
    
    public function __toString(){
        return "OrongoFrontendObject";
    }
}
?>
