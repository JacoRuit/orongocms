<?php

/**
 * TestIStorable
 *
 * @author Jaco Ruit
 */
class TestStorable implements IStorable {
    
     private $privateString = "";
     
     public function toStorageSyntax(){
         $return = array();
         $return['privateString'] = $this->privateString;
         return $return;
     }
    
 
    public function __construct($paramArray){
        $this->privateString = $paramArray['privateString'];
    }
    
    public function getString(){
        return $this->privateString;
    }
}

?>
