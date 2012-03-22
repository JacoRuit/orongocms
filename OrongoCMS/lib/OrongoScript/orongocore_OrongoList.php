<?php
/**
 * OrongoList Class (OrongoVariable)
 *
 * @author Jaco Ruit
 */
class OrongoList extends OrongoVariable {
    
    private $list;
    
    /**
     *Create a new List
     * @param array $paramArray array to construct from (optional) 
     */
    public function __construct($paramArray = array()){
        if(!is_array($paramArray)) throw new IllegalArgumentException("Invalid argument, array expected.");
        foreach($paramArray as &$val){
            if(($val instanceof OrongoVariable) == false) $val = new OrongoVariable($val);
        }
        $this->list = $paramArray;
        parent::__construct($this);
    }
    
    /**
     * Gets the var
     * @param String $paramIndex index in the list 
     */
    public function getVar($paramIndex){
        if(!array_key_exists($paramIndex, $this->list)) 
                throw new Exception("Index doesn't exists in the list");
        $val = $this->list[$paramIndex];
        if(($val instanceof OrongoVariable) == false) $val = new OrongoVariable($val);
        return $val;
    }
    
    /**
     * Sets the var
     * @param String $paramIndex index in the list 
     * @param var $paramValue value to set
     */
    public function setVar($paramIndex, $paramValue){
        if(($paramValue instanceof OrongoVariable) == false) $paramValue = new OrongoVariable($paramValue);
        $this->list[$paramIndex] = $paramValue;
    }
    
    /**
     * Check if it the variable exists in the list 
     * @param String $paramIndex index in the list 
     */
     public function varIsSet($paramIndex){
         return array_key_exists($paramIndex, $this->list);
     }
     
     /**
      * Deletes the var in the list
      * @param String $paramIndex index in the list 
      */
     public function deleteVar($paramIndex){
         if(!array_key_exists($paramIndex, $this->list)) 
                throw new Exception("Index doesn't exists in the list");
         unset($this->list[$paramIndex]);
     }
     
     /**
      * Clears the list 
      */
     public function clear(){
         $this->list = array();
     }
     
     /**
      * Get the list as array 
      */
     public function getArray(){
         return $this->list;
     }
}

?>
