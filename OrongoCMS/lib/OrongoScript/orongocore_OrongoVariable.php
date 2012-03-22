<?php

/**
 * OrongoVariable Class
 *
 * @author Jaco Ruit
 */
class OrongoVariable {
    
    private $content;
    
    /**
     * Construct the var
     */
    public function __construct($paramContent){
        $this->content = $paramContent;
    }
    
    /**
     * Get the content
     * @return var
     */
    public function get(){
        return $this->content;
    }

}

?>
