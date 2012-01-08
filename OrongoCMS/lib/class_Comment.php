<?php

/**
 * Comment Class
 *
 * @author Jaco Ruit
 */
class Comment implements IHTMLConvertable {
   
    #   variables
    private $id;
    private $content;
    private $articleID;
    private $authorID;
    private $timestamp;
    private $articleID;
    
    /**
     * Construct Comment Object
     * 
     * @param int $paramID ID of comment 
     * @author Jaco Ruit
     */
    public function __construct($paramID){
        $this->id = $paramID;
        $q = "SELECT `content`,`authorID`,`timestamp` FROM `comments` WHERE `id` = '" . $this->id . "'";
        $result = @mysql_query($q);
        $row = mysql_fetch_assoc($result);
        $count = mysql_num_rows($result);
        if($count < 1){
            mysql_free_result($result);
            throw new Exception('Comment does not exist', COMMENT_NOT_EXIST);
        }
        $this->title = $row['title'];
        $this->content = htmlspecialchars($row['content']);
        $this->authorID = $row['authorID'];
        $this->timestamp = $row['timestamp'];
        $this->articleID = $row['articleID'];
        mysql_free_result($result);
    }
    
    #   id
    /**
     * @return int Comment ID
     */
    public function getID(){
        return $this->id;
    }
    
    #   contents
    /**
     * @return String Comment Content ( filtered HTML )
     */
    public function getContent(){
        return $this->content;
    }
    
    /**
    * @param String $paramContent new Comment Content
    */
    public function setContent($paramContent){
        $q = "UPDATE `comments` SET `content`='" . $paramContent . "' WHERE `id` = '" . $this->id ."'";
        @mysql_query($q);
        $this->content = $paramContent;
    }
    
    #   authorID
    /**
     * @return int Comment Author ID
     */
    public function getAuthorID(){
        return $this->authorID;
    }
    
    #   articleID
    /**
     * @return int Comment Article ID
     */
    public function getArticleID(){
        return $this->articleID;
    }
    
    #   date
    /**
     * @return int Timestamp when comment was written
     */
    public function getTimestamp(){
        return $this->timestamp;
    }
    
    /**
     * Deletes the article from database.
     */
    public function delete(){
        $q = "DELETE FROM `comments` WHERE `id` = '" . $this->id ."'";
        @mysql_query($q);
    }
    
    /**
     * Gets last comment ID in database
     * @return int comment ID
     */
    public static function getLastCommentID(){
        $q = 'SELECT `id` FROM `comments` ORDER BY `id` DESC';
        $result = @mysql_query($q);
        $row = mysql_fetch_assoc($result);
        $lastID = $row['id'];
        mysql_free_result($result);
        return $lastID;
    }
    
    public function toHTML(){
        $generatedHTML = "<div class=\"comment\">";
        $generatedHTML .= " <div class=\"comment-header\">";
        if($this->authorID == '00'){ $author_name = "Unknown"; }else{ $author_name = User::getUserName($this->authorID); }
        $generatedHTML .= "     <p id=\"author\">" . $author_name  . "</p>";
        $generatedHTML .= "     <p id=\"date\"" . date("Y-m-d H:i:s", $this->timestamp ) . "</p>";
        $generatedHTML .= " </div>";
        $generatedHTML .= " <p id=\"content\">" . $this->content . "</p>";
        $generatedHTML .= "</div>";
        return $generatedHTML;
    }
    
    /**
     * Creates a comment
     * @param String $paramAuthor User object
     * @return Comment new comment object
     */
    public static function createComment($paramUser = null){
        $newID = self::getLastCommentID() + 1;
        if($paramUser != null && ($paramUser instanceof User) == false) throw new IllegalArgumentException("User object expected."); 
        if($paramUser == null ) $author_id = 00; else $author_id = $paramUser->getID(); 
        $q = "INSERT INTO `comments` (`id`,`content`,`authorID`,`timestamp`) VALUES ('" . $newID . "', 'not_set_error', '" . $author_id . " ', UNIX_TIMESTAMP())";
        @mysql_query($q);
        return new Comment($newID);
    }
}

?>
