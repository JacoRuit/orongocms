<?php

/**
 * make_msgbox OrongoScript function
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
        return "make_msgbox";
    }
}

?>
