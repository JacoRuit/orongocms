<?php

/**
 * MessageBox OrongoScript Package
 *
 * @author Jaco Ruit
 */
class OrongoScriptMessageBox extends OrongoPackage {
    
    public function __construct() {
        
    }
    public function getFunctions() {
        require_once("functions/func_ShowMessageBox.php");
        require_once("functions/func_MakeMessageBox.php");
        return array(new FuncShowMessageBox(), new FuncMakeMessageBox());
    }
}

?>
