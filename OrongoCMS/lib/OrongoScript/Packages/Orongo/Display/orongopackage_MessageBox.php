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
        return array(new FuncMessageBoxShow());
    }
    
    
}


/**
 * Show OrongoScript function
 *
 * @author Jaco Ruit
 */
class FuncMessageBoxShow extends OrongoFunction {
    

    public function __invoke($args) {
        if(count($args) < 1)throw new OrongoScriptParseException("Argument missing for MessageBox.Show()");
        if(isset($args[1]) && is_string($args[1])) $title = $args[1];
        else $title = "OrongoCMS";
        $box = new MessageBox($args[0], $title);
        getDisplay()->addObject($box);
    }

    public function getShortname() {
        return "Show";
    }
    
    public function getSpace(){
        return "MessageBox";
    }
}



?>
