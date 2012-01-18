<?php

/**
 * OrongoDisplayableObject Class
 *
 * @author Jaco Ruit
 */
abstract class OrongoDisplayableObject implements IHTMLConvertable {
    
    //abstract public function toHTML();
    
    final public function addToDisplay(){
        getDisplay()->addHTML($this->toHTML());
    }
    
    public function __toString(){
        return "OrongoDisplayableObject";
    }
}

?>
