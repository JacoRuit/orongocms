<?php

/**
 * OrongoVariable OrongoClass
 *
 * @author Jaco Ruit
 */
class OrongoVariable {
    
    private $content;
    
    public function __construct($paramContent){
        $this->content = $paramContent;
    }
    
    public function get(){
        return $this->content;
    }
    
}

?>
