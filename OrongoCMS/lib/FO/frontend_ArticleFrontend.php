<?php
/**
 * Article frontend.
 *
 * @author Jaco Ruit
 */
class ArticleFrontend extends OrongoFrontendObject {
    
    private $body;
    private $commentsHTML;
    private $article;
    
    public function main($args){
        if(!isset($args['article'])){
            $msgbox = new MessageBox("Can't render article frontend: missing argument 'article'!");
            die($msgbox->getImports() . $msgbox->toHTML());
        }
        if(($args['article'] instanceof Article) == false){
            $msgbox = new MessageBox("Can't render article frontend: wrong argument 'article'!");
            die($msgbox->getImports() . $msgbox->toHTML());
        }
        $this->article = &$args['article'];
        $this->body = "<script src=\"" . Settings::getWebsiteURL() . "js/widget.prettyAlert.js\" type=\"text/javascript\" charset=\"utf-8\"></script>";
        $this->commentsHTML = "";  
        $this->generateCommentsHTML();
    }
    
    
    private function generateCommentsHTML(){
        $comments = $this->article->getComments();
        $this->commentHTML = "";
        if(getStyle()->doCommentHTML()){
            try{
                $this->commentHTML = getStyle()->getCommentsHTML($comments);
            }catch(Exception $e){
                $msgbox = new MessageBox("The style didn't generate the HTML code for the comments, therefore the default generator was used. <br /><br />To hide this message open <br />" . $style->getStylePath() . "info.xml<br /> and set <strong>own_article_html</strong> to <strong>false</strong>.");
                $msgbox->bindException($e);
                getDisplay()->addObject($msgbox);
                foreach($comments as $comment){
                    $this->commentHTML .= $comment->toHTML();
                }
            }
        }else{
            foreach($comments as $comment){
                $this->commentHTML .= $comment->toHTML();
            }
        }

        $LCID = 0;
        if(count($comments) != 0){
            $LCID = $comments[0]->getID();
        }

        $ajaxPC = new AjaxPostCommentAction($this->article->getID());
        getDisplay()->addJS($ajaxPC->toJS(), "document.ready");
        $this->body .= $ajaxPC->toHTML();

        $ajaxLC = new AjaxLoadCommentsAction($this->article->getID(), $LCID, count($comments));
        getDisplay()->addJS($ajaxLC->toJS(), "document.ready");
        $this->body .= $ajaxLC->toHTML();
    }
   
    public function render(){
        getDisplay()->setTitle(Settings::getWebsiteName() . " - " . $this->article->getTitle());
        getDisplay()->setTemplateVariable("body", $this->body);
    

        getDisplay()->setTemplateVariable("article", $this->article);
        getDisplay()->setTemplateVariable("comments", $this->commentHTML);
    
        getStyle()->run();

        getDisplay()->add("header");
        getDisplay()->add("article");
        getDisplay()->add("footer");
        getDisplay()->render();
    }
}

?>
