<?php
/**
 * Archive frontend.
 *
 * @author Jaco Ruit
 */
class ArchiveFrontend extends OrongoFrontendObject {
    
    private $body;
    private $articles;
    private $articleHTML;
    
    public function main($args){
        if($args['articles'] != null && !is_array($args['articles'])){
            $msgbox = new MessageBox("Can't render archive frontend: wrong argument 'articles'!");
            die($msgbox->getImports() . $msgbox->toHTML());
        }
        if($args['articles'] != null)
            $this->articles = &$args['articles'];
        $this->articleHTML = "";
        $this->body = "<script src=\"" . Settings::getWebsiteURL() . "js/widget.prettyAlert.js\" type=\"text/javascript\" charset=\"utf-8\"></script>";
        $this->generateArticleHTML();
    }
    
    private function generateArticleHTML(){
        if($this->articles == null) return;
        if(getStyle()->doArticleHTML()){
            try{
                $this->articleHTML = getStyle()->getArticlesHTML($this->articles);
            }catch(Exception $e){
                $msgbox = new MessageBox("The style didn't generate the HTML code for the articles, therefore the default generator was used. <br /><br />To hide this message open <br />" . $style->getStylePath() . "info.xml<br /> and set <strong>own_article_html</strong> to <strong>false</strong>.");
                $msgbox->bindException($e);
                getDisplay()->addObject($msgbox);
                foreach($this->articles as $article){
                    $this->articleHTML .= $article->toShortHTML();
                }
            }
        }else{
            foreach($this->articles as $article){
                $this->articleHTML .= $article->toShortHTML();
            }
        }
    }
    
   
    public function render(){
        getDisplay()->setTitle(Settings::getWebsiteName() . " - " . l("ARCHIVE"));
        getDisplay()->setTemplateVariable("body", $this->body);
    
        getDisplay()->setTemplateVariable("articles", $this->articleHTML);
        getStyle()->run();

        getDisplay()->add("header");
        getDisplay()->add("archive");
        getDisplay()->add("footer");
        getDisplay()->render();
    }
}

?>
