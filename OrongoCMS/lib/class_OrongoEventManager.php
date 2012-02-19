<?php

/**
 * OrongoEventManager Class
 *
 * @author Ruit
 */


class OrongoEventManager {
    
    private static $eventHandlers;
    private static $bigEventHandlers;
    private static $initted = false;
    private static $actions;
    private static $called;
    
    /**
     * Registers an event handler
     * @param String $paramAction any event action string
     * @param object $paramClass ref to the class
     * @param string $paramMethod method to call (method needs 1 argument!)
     */
    public static function addEventHandler($paramAction, $paramClass, $paramMethod){
        if(!method_exists($paramClass, $paramMethod))
                throw new IllegalArgumentException("Invalid argument, valid method name expected! " . $paramMethod . " is invalid!");
        if(!in_array($paramAction, self::$actions))
                throw new IllegalArgumentException("Invalid argument, valid action expected!");
      
        self::$eventHandlers[$paramAction][count(self::$eventHandlers[$paramAction])] = array($paramClass, $paramMethod);
    }
    
    /**
     * Registers event handlers, so Orongo doesn't have to store thousands of copies of a class
     * @param array $paramHandlers Event action as key method as value
     * @param object $paramClass ref to the class
     */
    public static function addEventHandlers($paramHandlers, $paramClass){
        $new = count(self::$bigEventHandlers);
        self::$bigEventHandlers[$new] = array();
        self::$bigEventHandlers[$new]["__class"] = $paramClass;
        foreach($paramHandlers as $action=>$method){
             if(!in_array($action, self::$actions))
                throw new IllegalArgumentException("Invalid argument, valid action expected! (" . $action . ")");
             if(!method_exists($paramClass, $method))
                throw new IllegalArgumentException("Invalid argument, valid method name expected! " . $paramMethod . " is invalid!");
             self::$bigEventHandlers[$new][$action] = $method;
        }
    }
    
    /**
     * Calls all the registered event handlers of the event action
     * @param OrongoEvent $paramEvent event to raise 
     */
    public static function raiseEvent($paramEvent){
        if(($paramEvent instanceof OrongoEvent) == false)
            throw new IllegalArgumentException("Invalid argument, OrongoEvent object expected!");
        if(in_array($paramEvent->getAction(), self::$called)) return;
        if(isset(self::$eventHandlers[$paramEvent->getAction()]) && is_array(self::$eventHandlers[$paramEvent->getAction()])){
            foreach(self::$eventHandlers[$paramEvent->getAction()] as $eventHandler){
                if(!is_array($eventHandler)) continue;
                @call_user_func($eventHandler, $paramEvent->getEventArgs());
            }
        }
        foreach(self::$bigEventHandlers as $bigEventHandler){
            if(empty($bigEventHandler)) continue;
            if(!isset($bigEventHandler["__class"])) continue;
            if(!isset($bigEventHandler[$paramEvent->getAction()])) continue;
            @call_user_func(array($bigEventHandler["__class"], $bigEventHandler[$paramEvent->getAction()]), $paramEvent->getEventArgs());
        }
        self::$called[count(self::$called)] = $paramEvent->getAction();
    }
    
    /**
     * Get all possible actions
     * @return array Possible action strings
     */
    public static function getAllActions(){
        return self::$actions;
    }
    
    /**
     * init some vars
     */
    public static function init(){
        if(self::$initted)
            return;
        
        self::$eventHandlers = array();
        self::$bigEventHandlers = array();
        self::$called = array();
        self::$actions = array(
            'article_created',
            'page_created',
            'user_created',
            'comment_created',
            'article_deleted',
            'page_deleted',
            'user_deleted',
           'comment_deleted',
            'article_edit',
            'page_edit',
            'user_edit'
        );
        
        
        foreach(self::$actions as $action){
            self::$eventHandlers[$action] = array();
        }
        
        self::$initted = true;
    }
}

?>
