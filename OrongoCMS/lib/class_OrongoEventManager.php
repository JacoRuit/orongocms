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
     * @param String $paramAction any action string
     * @param object $paramClass ref to the class
     * @param string $paramMethod method to call (method needs 1 argument!)
     */
    public static function addEventHandler($paramAction, $paramClass, $paramMethod){
        if(!method_exists($paramClass, $paramMethod))
                throw new IllegalArgumentException("Invalid argument, valid method name expected!");
        if(!in_array($paramAction, self::$actions))
                throw new IllegalArgumentException("Invalid argument, valid action expected!");
      
        self::$eventHandlers[$paramAction][count(self::$eventHandlers[$paramAction])] = array($paramClass, $paramMethod);
    }
    
    /**
     * Calls all the registered event handlers of the event action
     * @param OrongoEvent $paramEvent event to raise 
     */
    public static function raiseEvent($paramEvent){
        if(($paramEvent instanceof OrongoEvent) == false)
            throw new IllegalArgumentException("Invalid argument, OrongoEvent object expected!");
        if(!isset(self::$eventHandlers[$paramEvent->getAction()]) || !is_array(self::$eventHandlers[$paramEvent->getAction()])) return;
        foreach(self::$eventHandlers[$paramEvent->getAction()] as $eventHandler){
            if(!is_array($eventHandler)) continue;
            @call_user_func($eventHandler, $paramEvent->getEventArgs());
        }
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
        
        self::$actions = array(
            'article_created',
            'page_created',
            'user_created',
            'comment_created',
            'article_deleted',
            'page_deleted',
            'user_deleted',
           //'comment_deleted',
            'article_edit',
            'page_edit',
            'user_edit'//,
           //'comment_edit'
        );
        
        
        foreach(self::$actions as $action){
            self::$eventHandlers[$action] = array();
        }
        
        self::$initted = true;
    }
}

?>
