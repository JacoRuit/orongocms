<?php
/**
 * Article frontend.
 *
 * @author Jaco Ruit
 */
class LoginFrontend extends OrongoFrontendObject {
    
    private $body;

    
    public function main($args){
        getDisplay()->setTemplateDir(ROOT . "/orongo-admin/theme/");
        if(!is_string($args['msg']) && $args['msg'] != null){
            $msgbox = new MessageBox("Can't render login frontend: wrong argument 'msg'!");
            die($msgbox->getImports() . $msgbox->toHTML());
        }
        $this->body = "<script src=\"" . Settings::getWebsiteURL() . "js/widget.prettyAlert.js\" type=\"text/javascript\" charset=\"utf-8\"></script>";
        if($args['msg'] != null) getDisplay()->addObject(new MessageBox($args['msg']));
    }
    
    
   
    public function render(){
        getDisplay()->setTitle(Settings::getWebsiteName() . " - Login");
        getDisplay()->setTemplateVariable("body", $this->body);
   
    
        getStyle()->run();

        getDisplay()->add("login");
        //getDisplay()->add("footer");
        getDisplay()->render();
    }
}

?>
