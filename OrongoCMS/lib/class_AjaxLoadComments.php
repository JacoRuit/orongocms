<?php

/**
 * AjaxLoadComments Class
 *
 * @author Jaco Ruit
 */
class AjaxLoadComments implements IHTMLConvertable, IJSConvertable{

    private $offset;
    private $lastCommentID;
    private $articleID;
    private $refreshInterval;
    
    public function __construct($paramArticleID, $paramLastCommentID, $paramOffset, $paramRefreshInterval = 10000){
        $this->offset = $paramOffset;
        $this->lastCommentID  = $paramLastCommentID;
        $this->articleID = $paramArticleID;
        $this->refreshInterval = $paramRefreshInterval;
    }
    
    public function toHTML() {
        return '<script type="text/javascript" src="'. Settings::getWebsiteURL(). 'js/ajax.loadComments.js"></script>';
    }
    
    public function toJS() {
        $generatedJS = " offset = " . $this->offset . "; ";
        $generatedJS .= " lastCommentID = " . $this->lastCommentID . "; ";
        $generatedJS .= " window.setInterval(function() {";
        //$generatedJS .= " try{";
        $generatedJS .= "   var returned = loadComments('" . Settings::getWebsiteURL() . "ajax/loadComments.php', " . $this->articleID . ", lastCommentID, offset);";
        $generatedJS .= "   offset = returned[0]; ";
        $generatedJS .= "   lastCommentID = returned[1]; ";
        //$generatedJS .= "}catch(err){ alert(err); }";
        $generatedJS .= " }, " . $this->refreshInterval . "); ";
        return $generatedJS;
    }
}

?>
