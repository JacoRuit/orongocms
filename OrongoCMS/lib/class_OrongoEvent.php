<?php


/**
 * Event Class
 *
 * @author Ruit
 */
class OrongoEvent {
    
    private $action;
    private $eventArgs;
    
    /**
     * @param String $paramAction Any action
     * @param array $paramEventArgs Event arguments
     */
    public function __construct($paramAction, $paramEventArgs){
        if(!in_array($paramAction, OrongoEventManager::getAllActions()))
            throw new IllegalArgumentException("Invalid argument, valid action expected!");
        if(!is_array($paramEventArgs))
            throw new IllegalArgumentException("Invalid argument, array expected!");
        
        $this->action = $paramAction;
        $this->eventArgs = $paramEventArgs;
    }
    
    /**
     * @return String action string
     */
    public function getAction(){
        return $this->action;
    }
    
    /**
     * @return array event arguments
     */
    public function getEventArgs(){
        return $this->eventArgs;
    }
    
    
}

?>
