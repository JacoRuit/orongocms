<?php
/**
 * Index frontend.
 *
 * @author Jaco Ruit
 */
class IndexFrontend extends OrongoFrontendObject {
    
    private $body;
    private $articleHTML;
    
    public function main($args){
        $this->body = "<script src=\"" . Settings::getWebsiteURL() . "js/widget.prettyAlert.js\" type=\"text/javascript\" charset=\"utf-8\"></script>";
        $this->articleHTML = "";  
        $this->generateArticlesHTML();
    }

    
    private function generateArticlesHTML(){
        $articles = array();
        $q = "action=fetch&object=article&max=5&order=article.id,desc";
        try{
            $articles = orongo_query($q);
        }catch(Exception $e){
            $msgbox = new MessageBox();
            $msgbox->bindException($e);
            getDisplay()->addObject($msgbox);
        }
        if((count($articles) < 1)){
            try{
                $article = Article::createArticle("Hello World!");
                $article->setContent("<p>Thank you for installing OrongoCMS!</p><p>To edit this simply delete it and create a new article or change this article.</p><br /><p>The OrongoCMS team</p>");
                $articles[0] = $article;
            }catch(Exception $e){ }
        }
        if(getStyle()->doArticleHTML()){
            try{
                $this->articleHTML = getStyle()->getArticlesHTML($articles);
            }catch(Exception $e){
                $msgbox = new MessageBox("The style didn't generate the HTML code for the articles, therefore the default generator was used. <br /><br />To hide this message open <br />" . $style->getStylePath() . "info.xml<br /> and set <strong>own_article_html</strong> to <strong>false</strong>.");
                $msgbox->bindException($e);
                getDisplay()->addObject($msgbox);
                foreach($articles as $article){
                    $this->articleHTML .= $article->toShortHTML();
                }
            }
        }else{
            foreach($articles as $article){
                $this->articleHTML .= $article->toShortHTML();
            }
        }
    }
   
    public function render(){
        getDisplay()->setTitle(Settings::getWebsiteName() . " - " . l("HOME"));
        getDisplay()->setTemplateVariable("body", $this->body);
    
        getDisplay()->setTemplateVariable("articles", $this->articleHTML);

        getStyle()->run();

        getDisplay()->add("header");
        getDisplay()->add("index");
        getDisplay()->add("footer");
        getDisplay()->render();
    }
}

?>
