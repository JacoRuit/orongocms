<?php
/**
 * Page Class
 *
 * @author Jaco Ruit
 */
class Page {
    
    #   variables
    private $id;
    private $title;
    private $content;
    
    #   constructors
    /**
     * Construct Page Object
     * 
     * @param int $paramID ID of page
     * @author Jaco Ruit
     */
    public function __construct($paramID){
        $this->id = $paramID;
        $q = "SELECT `title`,`content` FROM `pages` WHERE `id` = '" . $this->id . "'";
        $result = getDatabase()->execQuery($q);
        $row = mysql_fetch_assoc($result);
        $count = mysql_num_rows($result);
        if($count < 1){
            mysql_free_result($result);
            throw new Exception('Page does not exist', PAGE_NOT_EXIST);
        }
        $this->title = stripslashes($row['title']);
        $this->content = stripslashes($row['content']);
        mysql_free_result($result);
    }
    
    #   id
    /**
     * @return int Page ID
     */
    public function getID(){
        return $this->id;
    }
    
    
    #   title
    /**
     * @return String Page Title
     */
    public function getTitle(){
        return $this->title;
    }
    
    /**
    * @param String $paramTitle new Page Title
    */
    public function setTitle($paramTitle){
        $q = "UPDATE `pages` SET `title`='" . addslashes($paramTitle) . "' WHERE `id` = '" . $this->id ."'";
        getDatabase()->execQuery($q);
        $this->title = $paramTitle;
    }
    
    #   contents
    /**
     * @return String Page Content (HTML)
     */
    public function getContent(){
        return $this->content;
    }
    
    /**
    * @param String $paramContent new Page Content
    */
    public function setContent($paramContent){
        $q = "UPDATE `pages` SET `content`='" . addslashes($paramContent) . "' WHERE `id` = '" . $this->id ."'";
        getDatabase()->execQuery($q);
        $this->content = $paramContent;
    }
    
    /**
     * Gets page count
     * @return int page count
     */
    public static function getPageCount(){
        $q = 'SELECT `id` FROM `pages`';
        $result = getDatabase()->execQuery($q);
        $num = mysql_num_rows($result);
        mysql_free_result($result);
        return $num;
    }
    
    /**
     * Gets last page ID in database
     * @return int page ID
     */
    public static function getLastPageID(){
        $q = 'SELECT `id` FROM `pages` ORDER BY `id` DESC';
        $result = getDatabase()->execQuery($q);
        $row = mysql_fetch_assoc($result);
        $lastID = $row['id'];
        mysql_free_result($result);
        return $lastID;
    }
    
    
    /**
     * Creates a page
     * @param String $paramName name of the page
     * @return Page new page object
     */
    public static function createPage($paramName){
        $newID = self::getLastPageID() + 1;
        $q = "INSERT INTO `pages` (`id`,`title`) VALUES ('" . $newID . "', '" . $paramName . "')";
        getDatabase()->execQuery($q);
        return new Page($newID);
    }
    
    /**
     * Gets the page ID of the title
     * @param String $paramTitle title
     * @return int page ID
     */
    public static function getPageID($paramTitle){
        $q = "SELECT `id` FROM `pages` WHERE `title` LIKE '" . addslashes($paramTitle) . "'";
        $result = getDatabase()->execQuery($q);
        $row = mysql_fetch_assoc($result);
        mysql_free_result($result);
        return $row['id'];
    }
}

?>
