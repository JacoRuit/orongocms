<?php

/**
 * OrongoFrontendObject Class
 *
 * @author Jaco Ruit
 */
abstract class OrongoFrontendObject {
    
    /**
     *Runs the frontend 
     */
    abstract public function main($paramArgs);
    
    /**
     *Renders the frontend for the viewer 
     */
    abstract public function render();
    
    public function __toString(){
        return "OrongoFrontendObject";
    }
}
?>
