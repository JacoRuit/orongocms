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
        $q = "SELECT `title`,`content`,`authorID`,`date` FROM `articles` WHERE `id` = '" . $this->id . "'";
        $result = getDatabase()->execQuery($q);
        $row = mysql_fetch_assoc($result);
        $count = mysql_num_rows($result);
        if($count < 1){
            mysql_free_result($result);
            throw new Exception('Article does not exist', ARTICLE_NOT_EXIST);
        }
        $this->title = $row['title'];
        $this->content = $row['content'];
        $this->authorID = $row['authorID'];
        $this->date = $row['date'];
        try{
            $this->author = new User($this->authorID);
        }catch(Exception $e){ $this->author = null; }
        mysql_free_result($result);
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
        $q = "UPDATE `articles` SET `title`='" . $paramTitle . "' WHERE `id` = '" . $this->id ."'";
        getDatabase()->execQuery($q);
        $this->title = $paramTitle;
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
        $q = "UPDATE `articles` SET `content`='" . $paramContent . "' WHERE `id` = '" . $this->id ."'";
        getDatabase()->execQuery($q);
        $this->content = $paramContent;
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
        $q = "DELETE FROM `articles` WHERE `id` = '" . $this->id ."'";
        getDatabase()->execQuery($q);
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
        $generatedHTML .= " <p id=\"content\">" . substr($this->content, 0, 100) . "</p>";
        $generatedHTML .= "</div>";
        return $generatedHTML;
    }
    
    /**
     * @return int comments count
     */
    public function getCommentCount(){
        $count = 0;
        $count = @orongo_query("action=count&object=comment&max=10000000&where=article.id:" . $this->id);
        return $count;
    }
    
    /**
     * @return array comments
     */
    public function getComments(){
        $comments = array();
        $comments = @orongo_query("action=fetch&object=comment&max=10000000&order=comment.id,desc&where=article.id:" . $this->id);
        return $comments;
    }
    
    /**
     * Gets last article ID in database
     * @return int article ID
     */
    public static function getLastArticleID(){
        $q = 'SELECT `id` FROM `articles` ORDER BY `id` DESC';
        $result = getDatabase()->execQuery($q);
        $row = mysql_fetch_assoc($result);
        $lastID = $row['id'];
        mysql_free_result($result);
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
        $q = "INSERT INTO `articles` (`id`,`title`,`authorID`,`date`) VALUES ('" . $newID . "', '" . $paramName . "', '" . $author_id . " ', CURDATE())";
        getDatabase()->execQuery($q);
        return new Article($newID);
    }
    
    /**
     * Gets the article ID of the title
     * @param String $paramTitle title
     * @return int article ID
     */
    public static function getArticleID($paramTitle){
        $q = "SELECT `id` FROM `articles` WHERE `title` LIKE '" . addslashes($paramTitle) . "'";
        $result = getDatabase()->execQuery($q);
        $row = mysql_fetch_assoc($result);
        mysql_free_result($result);
        return $row['id'];
    }
}

?>
