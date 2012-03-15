<?php

/**
 * MessageBox OrongoScript Package
 *
 * @author Jaco Ruit
 */
class OrongoScriptMessageBox extends OrongoPackage {
    
    public function __construct($runtime) {
        
    }
    public function getFunctions() {
        return array(new FuncShowMessageBox(), new FuncMakeMessageBox());
    }
    
    
}

# ---FUNCTIONS---

/**
 * Show OrongoScript function
 *
 * @author Jaco Ruit
 */
class FuncShowMessageBox extends OrongoFunction {
    

    public function __invoke($args) {
        if(!isset($args[0]) || ($args[0] instanceof MessageBox) == false) throw new OrongoScriptParseException("Invalid arguments for show_msgbox()");
        getDisplay()->addObject($args[0]);
    }

    public function getShortname() {
        return "Show";
    }
    
    public function getSpace(){
        return "MessageBox";
    }
}


/**
 * Make OrongoScript function
 *
 * @author Jaco Ruit
 */
class FuncMakeMessageBox extends OrongoFunction {
    

    public function __invoke($args) {
        if(!isset($args[0]) || !is_string($args[0])) throw new OrongoScriptParseException("Invalid argument for make_msgbox()");
        if(isset($args[1]) && is_string($args[1])) $title = $args[1];
        else $title = "OrongoCMS";
       
        return new OrongoVariable(new MessageBox($args[0], $title));
    }

    public function getShortname() {
        return "Make";
    }
    
    public function getSpace(){
        return "MessageBox";
    }
}


# ---END FUNCTIONS---

?>
