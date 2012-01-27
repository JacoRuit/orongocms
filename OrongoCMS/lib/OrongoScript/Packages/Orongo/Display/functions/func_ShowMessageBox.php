<?php

/**
 * show_msgbox OrongoScript function
 *
 * @author Jaco Ruit
 */
class FuncShowMessageBox extends OrongoFunction {
    

    public function __invoke($args) {
        if(!isset($args[0]) || ($args[0] instanceof MessageBox) == false) throw new OrongoScriptParseException("Invalid arguments for show_msgbox()");
        getDisplay()->addObject($args[0]);
    }

    public function getShortname() {
        return "show_msgbox";
    }
}

?>
