<?php

/**
 * AjaxLoadCommentsAction Class
 *
 * @author Jaco Ruit
 */
class AjaxLoadCommentsAction implements IHTMLConvertable, IJSConvertable{

    private $offset;
    private $lastCommentID;
    private $articleID;
    private $refreshInterval;
    
    /**
     * Construct the object
     * @param int $paramArticleID Article ID
     * @param int $paramLastCommentID Last fetched Comment ID
     * @param int $paramOffset Offset (count of loaded comments)
     * @param int $paramRefreshInterval refresh interval (default=10000)
     */
    public function __construct($paramArticleID, $paramLastCommentID, $paramOffset, $paramRefreshInterval = 7000){
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
