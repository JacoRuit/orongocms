<?php

/**
 * AjaxPostCommentAction Class
 *
 * @author Jaco Ruit
 */
class AjaxPostCommentAction implements IHTMLConvertable, IJSConvertable{
    
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
    

    public function toHTML() {
        return '<script type="text/javascript" src="'. Settings::getWebsiteURL(). 'js/ajax.postComment.js"></script><div id="_orongo_ajax_response"></div>';
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
}

?>
