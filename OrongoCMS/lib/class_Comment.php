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

    
    /**
     * Construct Comment Object
     * 
     * @param int $paramID ID of comment 
     * @author Jaco Ruit
     */
    public function __construct($paramID){
        $this->id = $paramID;
        $row = getDatabase()->queryFirstRow("SELECT `id`,`content`,`authorID`,`articleID`,`timestamp` FROM `comments` WHERE `id` = %i", $this->id);
        if($row == null){
            throw new Exception('Comment does not exist', COMMENT_NOT_EXIST);
        }
        $this->content = htmlspecialchars($row['content']);
        $this->authorID = $row['authorID'];
        $this->timestamp = $row['timestamp'];
        $this->articleID = $row['articleID'];
        $this->id = $row['id'];
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
        getDatabase()->update("comments", array(
           "content" => $paramContent 
        ),"id=%i", $this->id);
        $this->content = $paramContent;
    }
    
    #   authorID
    /**
     * @return int Comment Author ID
     */
    public function getAuthorID(){
        return $this->authorID;
    }
    
    /**
     * @return string Comment Author Name
     */
    public function getAuthorName(){
        if($this->authorID == '00') $author_name = "Unknown"; else $author_name = User::getUserName($this->authorID); 
        return $author_name;
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
        getDatabase()->delete("comments","id=%i", $this->id);
        $by = getUser() == null ? -1 : getUser()->getID();
        raiseEvent('comment_deleted', array("comment_id" => $this->id, "by" => $by));
    }
    
    /**
     * Gets last comment ID in database
     * @return int comment ID
     */
    public static function getLastCommentID(){
        $row = getDatabase()->queryFirstRow('SELECT `id` FROM `comments` ORDER BY `id` DESC');
        return $row['id'];
    }
    
    public function toHTML(){
        $generatedHTML = "<div class=\"comment\">";
        $generatedHTML .= " <div class=\"comment-header\">";
        $generatedHTML .= "     <p id=\"author\">" . $this->getAuthorName()  . "</p>";
        $generatedHTML .= "     <p id=\"date\">" . date("Y-m-d H:i:s", $this->timestamp ) . "</p>";
        $generatedHTML .= " </div>";
        $generatedHTML .= " <p id=\"content\">" . $this->content . "</p>";
        $generatedHTML .= "</div>";
        return $generatedHTML;
    }
    
    /**
     * Creates a comment
     * @param int   $paramArticleID Article  ID
     * @param String $paramAuthor User object
     * @return Comment new comment object
     */
    public static function createComment($paramArticleID, $paramUser = null){
        $newID = self::getLastCommentID() + 1;
        if($paramUser != null && ($paramUser instanceof User) == false) throw new IllegalArgumentException("User object expected."); 
        if($paramUser == null ) $author_id = 00; else $author_id = $paramUser->getID(); 
        getDatabase()->insert("comments", array(
           "id" => $newID,
           "content" => "not_set_error",
           "authorID" => $author_id,
           "articleID" => $paramArticleID,
           "timestamp" => time()
        ));
        $by = getUser() == null ? -1 : getUser()->getID();
        raiseEvent('comment_created', array("comment_id" => $newID, "by" => $by));
        return new Comment($newID);
    }
    
    /**
     * Gets comments count
     * @return int comment count
     */
    public static function getCommentCount(){
        getDatabase()->query("SELECT `id` FROM `comments`");
        return getDatabase()->count();
    }
    

}

?>
