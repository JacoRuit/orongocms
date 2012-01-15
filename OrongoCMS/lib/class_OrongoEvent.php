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
     * @param int $paramAction Any EVENT_*
     * @param array $paramEventArgs Event arguments
     */
    public function __construct($paramAction, $paramEventArgs){
        $this->action = $paramAction;
        $this->eventArgs = $paramEventArgs;
    }
    
    /**
     * @return int EVENT_*
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
