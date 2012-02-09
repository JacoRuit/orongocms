<?php
/**
 * Article Class
 *
 * @author Jaco Ruit
 */
class Article implements IHTMLConvertable {
    
    #   variables
    private $id;
    private $title;
    private $content;
    private $authorID;
    private $author;
    private $date;
    

    /**
     * Construct Article Object
     * 
     * @param int $paramID ID of article 
     * @author Jaco Ruit
     */
    public function __construct($paramID){
        $this->id = $paramID;
        //$q = "SELECT `title`,`content`,`authorID`,`date` FROM `articles` WHERE `id` = '" . $this->id . "'";
        $row = getDatabase()->queryFirstRow("SELECT `title`,`id`, `content`,`authorID`,`date` FROM `articles` WHERE `id` = %i", $paramID);
        if($row == null){
            throw new Exception('Article does not exist', ARTICLE_NOT_EXIST);
        }
        $this->title = $row['title'];
        $this->content = $row['content'];
        $this->authorID = $row['authorID'];
        $this->date = $row['date'];
        $this->id = $row['id'];
        try{
            $this->author = new User($this->authorID);
        }catch(Exception $e){ $this->author = null; }
        
    }
    

    
    #   id
    /**
     * @return int Article ID
     */
    public function getID(){
        return $this->id;
    }
    
    
    #   title
    /**
     * @return String Article Title
     */
    public function getTitle(){
        return $this->title;
    }
    
    /**
    * @param String $paramTitle new Article Title
    */
    public function setTitle($paramTitle){
        getDatabase()->update("articles", array(
            "title" => $paramTitle
        ), "`id` = %i", $this->id);
        $this->title = $paramTitle;
        $this->raiseArticleEvent('article_edit');
    }
    
    
    
    #   contents
    /**
     * @return String Article Content (HTML)
     */
    public function getContent(){
        return $this->content;
    }
    
    /**
    * @param String $paramContent new Article Content
    */
    public function setContent($paramContent){
        getDatabase()->update("articles", array("content" => $paramContent), "id=%i", $this->id);
        $this->content = $paramContent;
        $this->raiseArticleEvent('article_edit');
    }
    
    
    #   authorID
    /**
     * @return String Article Author ID
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
    
    /**
     * @return User Article Author 
     */
    public function getAuthor(){
        return $this->author;
    }
    
    #   date
    /**
     * @return String Date when article was written
     */
    public function getDate(){
        return $this->date;
    }
    
    /**
     * Deletes the article from database.
     */
    public function delete(){
        getDatabase()->delete("articles", "id=%i", $this->id);
        $this->raiseArticleEvent('article_deleted');
    }
    
    /**
     * Shortcut for raising article edit events:)
     * @param String $paramAction action string
     * @param int $paramArticleID article ID (default current ID)
     */
    private function raiseArticleEvent($paramAction, $paramArticleID = null){
        if($paramArticleID == null) $paramArticleID = $this->id;
        if(getUser() == null) $by = "admin";
        else $by = getUser()->getID();
        OrongoEventManager::raiseEvent(new OrongoEvent($paramAction, array("article_id" => $paramArticleID, "by" => $by)));
    }
    
    /**
     * Shortcut for raising article edit events:)
     * @param String $paramAction action string
     * @param int $paramArticleID article ID (default current ID)
     */
    private static function raiseArticleEventStatic($paramAction, $paramArticleID){
        if(getUser() == null) $by = "admin";
        else $by = getUser()->getID();
        OrongoEventManager::raiseEvent(new OrongoEvent($paramAction, array("article_id" => $paramArticleID, "by" => $by)));
    }
    
    /**
     * @return array Article Information in Array
     */
    public function toArray(){
        return array("id" => $this->id, "title" => $this->title, "contents" => $this->content, "authorID" => $this->authorID);
    }
    
    public function toHTML(){
        $generatedHTML = "<div class=\"article\">";
        $generatedHTML .= " <div class=\"article-header\">";
        $generatedHTML .= "     <p id=\"title\">" . $this->title . "</p>";
        if($this->author == null){ $author_name = "Unknown"; }else{ $author_name = $this->author->getName(); }
        $generatedHTML .= "     <p id=\"author\">" . $author_name  . "</p>";
        $generatedHTML .= "     <p id=\"date\"" . $this->date . "</p>";
        $generatedHTML .= " </div>";
        $generatedHTML .= " <p id=\"content\">" . $this->content . "</p>";
        $generatedHTML .= "</div>";
        return $generatedHTML;
    }
    
    public function toShortHTML(){
        $generatedHTML = "<div class=\"article\">";
        $generatedHTML .= " <div class=\"article-header\">";
        $generatedHTML .= "     <p id=\"title\">" . $this->title . "</p>";
        if($this->author == null){ $author_name = "Unknown"; }else{ $author_name = $this->author->getName(); }
        $generatedHTML .= "     <p id=\"author\">" . $author_name . "</p>"; 
        $generatedHTML .= "     <p id=\"date\"" . $this->date . "</p>";
        $generatedHTML .= " </div>";
        $generatedHTML .= " <p id=\"content\">" . substr(strip_tags($this->content), 0, 100) . "</p>";
        $generatedHTML .= "</div>";
        return $generatedHTML;
    }
    
    /**
     * @return int comments count
     */
    public function getCommentCount(){
        $count = 0;
        try{
            $count = orongo_query("action=count&object=comment&max=10000000&where=article.id:" . $this->id);
        }catch(Exception $e){}
        return $count;
    }
    
    /**
     * @return array comments
     */
    public function getComments(){
        $comments = array();
        try{
            $comments = orongo_query("action=fetch&object=comment&max=10000000&order=comment.id,desc&where=article.id:" . $this->id);
        }catch(Exception $e){}
        return $comments;
    }
    
    /**
     * Gets last article ID in database
     * @return int article ID
     */
    public static function getLastArticleID(){
        $row = getDatabase()->queryFirstRow("SELECT `id` FROM `articles` ORDER BY `id` DESC");
        $lastID = $row['id'];
        return $lastID;
    }
    
    /**
     * Creates an article
     * @param String $paramArticle name of the article
     * @param String $paramAuthor User object
     * @return Article new article object
     */
    public static function createArticle($paramName, $paramUser = null){
        $newID = self::getLastArticleID() + 1;
        if($paramUser != null && ($paramUser instanceof User) == false) throw new IllegalArgumentException("User object expected."); 
        if($paramUser == null ) $author_id = 00; else $author_id = $paramUser->getID(); 
        getDatabase()->insert("articles", array(
            "id" => $newID,
            "title" => $paramName,
            "authorID" => $author_id,
            "date" => getDatabase()->sqleval("CURDATE()")
        ));
        self::raiseArticleEventStatic('article_created', $newID);
        return new Article($newID);
    }
    
    /**
     * Gets the article ID of the title
     * @param String $paramTitle title
     * @return int article ID
     */
    public static function getArticleID($paramTitle){
        $row = getDatabase()->queryFirstRow("SELECT `id` FROM `articles` WHERE `title` LIKE %s", $paramTitle);
        return $row['id'];
    }
    
    /**
     * Gets article count
     * @return int article count
     */
    public static function getArticleCount(){
        getDatabase()->query("SELECT `id` FROM `articles`");
        return getDatabase()->count();
    }
}

?>
