<?php

/**
 * CommentPoster Class
 *
 * @author Jaco Ruit
 */
class CommentPoster extends AjaxAction{
    
    private $articleID;
    
    /**
     * Construct the object
     * Note:
     *  import prettyAlert
     *  style has to have form with id _orongo_comment_form including submit button and a field with name="_orongo_new_comment"
     * @param int $paramArticleID Article ID
     */
    public function __construct($paramArticleID){
        $this->articleID = $paramArticleID;
    }
    

    public function toJS() {
        $generatedJS = ' $("#_orongo_ajax_comment_form").submit(function(event) {';
        $generatedJS .= " event.preventDefault(); ";
        $generatedJS .= " postComment('" . Settings::getWebsiteURL() . "ajax/postComment.php', " . $this->articleID . ", $('textarea[name=_orongo_ajax_new_comment]').val(),'" . Settings::getWebsiteName() . "');";
        $generatedJS .= " $('textarea[name=_orongo_ajax_new_comment]').val('');";
        $generatedJS .= " return false; ";
        $generatedJS .= ' });';
        return $generatedJS;
    }
    
    public function doImports(){
        if(!getDisplay()->isImported(orongoURL('js/ajax.comments.js')))
            getDisplay()->import(orongoURL('js/ajax.comments.js'));
        
    }
}

?>
