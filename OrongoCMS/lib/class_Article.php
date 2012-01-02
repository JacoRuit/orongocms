<?php
/**
 * Article Object
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
    
    #   constructors
    /**
     * Construct Article Object
     * 
     * @param int $paramID ID of article 
     * @author Jaco Ruit
     */
    public function __construct($paramID){
        $this->id = $paramID;
        $q = "SELECT `title`,`content`,`authorID` FROM `articles` WHERE `id` = '" . $this->id . "'";
        $result = @mysql_query($q);
        $row = mysql_fetch_assoc($result);
        $count = mysql_num_rows($result);
        if($count < 1){
            mysql_free_result($result);
            throw new Exception('Article does not exist', ARTICLE_NOT_EXIST);
            exit;
        }
        $this->title = $row['title'];
        $this->content = $row['content'];
        $this->authorID = $row['authorID'];
        $this->author = new User($this->authorID);
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
        @mysql_query($q);
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
        @mysql_query($q);
        $this->content = $paramContent;
    }
    
    
    #   authorID
    /**
     * @return String Article Author ID
     */
    public function getAuthorID(){
        return $this->authorID;
    }
    
    #   authorID
    /**
     * @return User Article Author 
     */
    public function getAuthor(){
        return $this->author;
    }
    
    /**
     * Deletes the article from database.
     */
    public function delete(){
        $q = "DELETE FROM `articles` WHERE `id` = '" . $this->id ."'";
        @mysql_query($q);
    }
    
    /**
     * @return array Article Information in Array
     */
    public function getArticleArray(){
        return array("id" => $this->id, "title" => $this->title, "contents" => $this->content, "authorID" => $this->authorID);
    }
    
    public function toHTML(){
        $generatedHTML = "<div class=\"article\">";
        $generatedHTML .= " <div class=\"article-header\">";
        $generatedHTML .= "     <p id=\"title\">" . $this->title . "</p>";
        $generatedHTML .= "     <p id=\"author\">" . $this->author->getName()  . "</p>";
        $generatedHTML .= "</div>";
        return $generatedHTML;
    }
    
    public function toShortHTML(){
        $generatedHTML = "<div class=\"article\">";
        $generatedHTML .= " <div class=\"article-header\">";
        $generatedHTML .= "     <p id=\"title\">" . $this->title . "</p>";
        $generatedHTML .= "     <p id=\"author\">" . $this->author->getName() . "</p>";       
        $generatedHTML .= "     <p id=\"content\">" . substr($this->content, 0, 100) . "</p>";
        $generatedHTML .= " </div>";
        $generatedHTML .= "</div>";
        return $generatedHTML;
    }
    
    /**
     * Gets last article ID in database
     * @return int article ID
     */
    public static function getLastArticleID(){
        $q = 'SELECT `id` FROM `articles` ORDER BY `id` DESC';
        $result = @mysql_query($q);
        $row = mysql_fetch_assoc($result);
        $lastID = $row['id'];
        mysql_free_result($result);
        return $lastID;
    }
    
    /**
     * Creates an article
     * @param String $paramArticle name of the article
     * @return Article new article object
     */
    public static function createArticle($paramName){
        $newID = self::getLastArticleID() + 1;
        $q = "INSERT INTO `articles` (`id`,`title`) VALUES ('" . $newID . "', '" . $paramName . "')";
        @mysql_query($q);
        return new Article($newID);
    }
}

?>
