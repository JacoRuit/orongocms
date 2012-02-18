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
            $msgbox = new MessageBox("Can't render page frontend: missing argument 'page'!");
            die($msgbox->getImports() . $msgbox->toHTML());
        }
        if(($args['page'] instanceof Page) == false){
            $msgbox = new MessageBox("Can't render page frontend: wrong argument 'page'!");
            die($msgbox->getImports() . $msgbox->toHTML());
        }
        $this->page = $args['page'];
    }

    
   
    public function render(){
        getDisplay()->setTitle(Settings::getWebsiteName() . " - " . $this->page->getTitle());
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
