<?php

/**
 * Error frontend
 * 
 * @author Jaco Ruit
 */
class ErrorFrontend extends OrongoFrontendObject{
    
    private $errorCode;
    
    public function main($args){
        if(!isset($args['error_code'])){
            $msgbox = new MessageBox("Can't render error frontend: missing argument 'error_code'!");
            die($msgbox->getImports() . $msgbox->toHTML());
        }
        if(!is_numeric($args['error_code'])){
            $msgbox = new MessageBox("Can't render article frontend: wrong argument 'error_code'!");
            die($msgbox->getImports() . $msgbox->toHTML());
        }
        $this->errorCode = $args['error_code'];
    }

    public function render() {
        getDisplay()->setTitle(Settings::getWebsiteName() . " - " . l("Error") . " " . $this->errorCode);
        getDisplay()->setTemplateVariable("error_code", $this->errorCode);
        getStyle()->run();
        getDisplay()->add("header");
        getDisplay()->add("error");
        getDisplay()->add("footer");
        getDisplay()->render();
    }
    
}

?>
