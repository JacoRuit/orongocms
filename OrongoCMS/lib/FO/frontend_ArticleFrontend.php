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
                $msgbox = new MessageBox("The style didn't generate the HTML code for the comments, therefore the default generator was used. <br /><br />To hide this message open <br />" . getStyle()->getStylePath() . "info.xml<br /> and set <strong>own_comment_html</strong> to <strong>false</strong>.");
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

        $ajaxPC = new CommentPoster($this->article->getID());
        $ajaxPC->start();

        $ajaxLC = new CommentLoader($this->article->getID(), $LCID, count($comments));
        $ajaxLC->start();
    }
   
    public function render(){
        getDisplay()->setTitle(Settings::getWebsiteName() . " - " . $this->article->getTitle());
        getDisplay()->setTemplateVariable("body", $this->body);
    
        getDisplay()->addHTML('<meta name="keywords" content="' . $this->article->getTagsString() .'"/>', 'head');
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
