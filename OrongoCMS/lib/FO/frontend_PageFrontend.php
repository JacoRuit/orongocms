<?php
/**
 * Page frontend.
 *
 * @author Jaco Ruit
 */
class PageFrontend extends OrongoFrontendObject {
    
    private $body;
    
    public function main($args){
        if(!isset($args['page'])){
            $msgbox = new MessageBox("Can't render page frontend: missing main argument 'page'!");
            die($msgbox->getImports() . $msgbox->toHTML());
        }
        if(($args['page'] instanceof Page) == false){
            $msgbox = new MessageBox("Can't render page frontend: wrong main argument 'page'!");
            die($msgbox->getImports() . $msgbox->toHTML());
        }
        $this->body = "<script src=\"" . Settings::getWebsiteURL() . "js/widget.prettyAlert.js\" type=\"text/javascript\" charset=\"utf-8\"></script>";
        $this->page = $args['page'];
    }

    
   
    public function render(){
        getDisplay()->setTemplateVariable("head_title", Settings::getWebsiteName() . " - " . $this->page->getTitle());
        getDisplay()->setTemplateVariable("body", $this->body);

        getDisplay()->setTemplateVariable("page", $this->page);

        getStyle()->run();

        getDisplay()->add("header");
        getDisplay()->add("page");
        getDisplay()->add("footer");

        getDisplay()->render();
    }
}

?>
