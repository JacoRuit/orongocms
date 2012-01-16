<?php

/**
 * OrongoEventManager Class
 *
 * @author Ruit
 */

//FIXME replace ACTION_* defines with strings?
class OrongoEventManager {
    
    private static $eventHandlers;
    private static $initted = false;
    private static $actions;
    
    /**
     * Registers an event handler
     * @param int $paramAction any ACTION_* type
     * @param object $paramClass ref to the class
     * @param string $paramMethod method to call (method needs 1 argument!)
     */
    public static function addEventHandler($paramAction, $paramClass, $paramMethod){
        if(!method_exists($paramClass, $paramMethod))
                throw new IllegalArgumentException("Invalid argument, valid method name expected!");
        if(!in_array($paramAction, self::$actions))
                throw new IllegalArgumentException("Invalid argument, valid action expected!");
        
        self::$eventHandlers[$paramAction][count($eventHandlers[$paramEvent])] = array($paramClass, $paramMethod);
    }
    
    /**
     * Calls all the registered event handlers of the event action
     * @param OrongoEvent $paramEventObject event to raise 
     */
    public static function raiseEvent($paramEventObject){
        if(($paramEventObject instanceof OrongoEvent) == false)
            throw new IllegalArgumentExceptin("Invalid argument, OrongoEvent object expected!");
        if(!isset(self::$eventHandlers[$paramEventObject->getAction()]) || !is_array(self::$eventHandlers[$paramEventObject->getAction()])) return;
        foreach(self::$eventHandlers[$paramEventObject->getAction()] as $eventHandler){
            if(!is_array($eventHandler)) continue;
            @call_user_func($eventHandler, $paramEventObject->getEventArgs());
        }
    }
    
    /**
     * Get all possible actions
     * @return array Possible ACTION_*s
     */
    public static function getAllActions(){
        return self::$actions;
    }
    
    /**
     * Do all ACTION_* defines, init some vars
     */
    public static function init(){
        if(self::$initted)
            return;
        
        self::$eventHandlers = array();
        
        self::$actions = array(1,2,3,4,11,12,13);
        
        #CREATED
        define("ACTION_ARTICLE_CREATED", 1);
        define("ACTION_PAGE_CREATED", 2);
        define("ACTION_USER_CREATED", 3);
        define("ACTION_COMMENT_CREATED", 4);
        
        #EDIT
        define("ACTION_ARTICLE_EDIT" , 11);
        define("ACTION_PAGE_EDIT", 12);
        define("ACTION_USER_EDIT", 13);
        
        foreach(self::$actions as $action){
            self::$eventHandlers[$action] = array();
        }
        
        self::$initted = true;
    }
}

?>
