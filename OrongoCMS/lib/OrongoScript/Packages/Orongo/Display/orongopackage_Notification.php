<?php

/**
 * Notification OrongoScript Package
 *
 * @author Jaco Ruit
 */
class OrongoScriptNotification extends OrongoPackage {
    
    public function __construct($runtime) {
        
    }
    public function getFunctions() {
        return array(new FuncNotificationNotify());
    }
}

/**
 * Show OrongoScript function
 *
 * @author Jaco Ruit
 */
class FuncNotificationNotify extends OrongoFunction {
    

    public function __invoke($args) {
        if(count($args) < 2)throw new OrongoScriptParseException("Arguments missing for Notification.Notify()");
        $title = isset($args[2]) ? $args[2] : "OrongoCMS";
        $image = isset($args[3]) ? $args[3] : null;
        $time = isset($args[4]) && is_numeric($args[4]) ? intval($args[4]) : 10000;
        $n = new OrongoNotification($title, $args[1], $image, $time);
        $n->dispatch(new User($args[0]));
    }

    public function getShortname() {
        return "Notify";
    }
    
    public function getSpace(){
        return "Notification";
    }
}

?>
