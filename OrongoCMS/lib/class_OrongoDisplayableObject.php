<?php

/**
 * OrongoDisplayableObject Class
 *
 * @author Jaco Ruit
 */
abstract class OrongoDisplayableObject implements IHTMLConvertable {
    
    
    public function addToDisplay(){
        getDisplay()->addHTML($this->toHTML());
    }
    
    public function __toString(){
        return "OrongoDisplayableObject";
    }
}

?>
