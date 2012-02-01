<?php
/**
 * Article frontend.
 *
 * @author Jaco Ruit
 */
class AdminFrontend extends OrongoFrontendObject {
    
    private $body;
    private $page;
    private $statics;
    private $pageTitle;
    
    public function main($args){
        getDisplay()->setTemplateDir(ROOT . "/orongo-admin/theme/");
        if(!isset($args['page'])){
            $msgbox = new MessageBox("Can't render admin frontend: missing argument 'page'!");
            die($msgbox->getImports() . $msgbox->toHTML());
        }
        if(!is_string($args['page'])){
            $msgbox = new MessageBox("Can't render admin frontend: wrong argument 'page'!");
            die($msgbox->getImports() . $msgbox->toHTML());
        }
        $this->body = "<script src=\"" . Settings::getWebsiteURL() . "js/widget.prettyAlert.js\" type=\"text/javascript\" charset=\"utf-8\"></script>";
        $this->page = $args['page'];
        $this->generateStatistics();
        switch($this->page){
            case "index":
                $this->pageTitle = "Dashboard";
                break;
            default:
                $msgbox = new MessageBox("Can't render admin frontend: wrong argument 'page'!");
                die($msgbox->getImports() . $msgbox->toHTML());
        }
    }
    
    private function generateStatistics(){
        /**
         *<p><b>Articles:</b> 2201</p>
					<p><b>Comments:</b> 17092</p>
					<p><b>Users:</b> 3788</p> 
         */
        $this->statics .= "<p><b>Users:</b>" . User::getUserCount() . "</p>";
        $this->statics .= "<p><b>Articles:</b>" . Article::getArticleCount() . "</p>";
        $this->statics .= "<p><b>Comments:</b>" . Comment::getCommentCount() ."</p>";
        $this->statics .= "<p><b>Pages:</b>" . Page::getPageCount() . "</p>";
        $this->statics .= "<p><b>Items in storage:</b>" . Storage::getStorageCount() ."</p>";
        $this->statics .= "<p><b>Plugins:</b>" . Plugin::getPluginCount() ."</p>";

    }
    
    
    public function render(){
        getDisplay()->setTitle(Settings::getWebsiteName() . " - " . $this->pageTitle );
        getDisplay()->setTemplateVariable("body", $this->body);
    

        getDisplay()->setTemplateVariable("statics", $this->statics);
        if(getUser() != null) getDisplay()->setTemplateVariable("user", getUser());
        else Security::promptAuth();
        
        getDisplay()->setTemplateVariable("style_url", Settings::getWebsiteURL() . "orongo-admin/theme/");
        
        getStyle()->run();

        getDisplay()->add("header");
        getDisplay()->add($this->page);
        getDisplay()->render();
    }
}

?>
