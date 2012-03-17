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
    
    
    #   events
    public static $EditEvent;
    public static $DeleteEvent;
    public static $CreateEvent;
    
    /**
     * Construct Page Object
     * 
     * @param int $paramID ID of page
     * @author Jaco Ruit
     */
    public function __construct($paramID){
        $this->id = $paramID;
        $row = getDatabase()->queryFirstRow("SELECT `id`, `title`,`content` FROM `pages` WHERE `id` = %i", $this->id);
        if($row == null){
            throw new Exception('Page does not exist', PAGE_NOT_EXIST);
        }
        $this->title = $row['title'];
        $this->content = $row['content'];
        $this->id = $row['id'];
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
        getDatabase()->update("pages", array(
            "title" => $paramTitle
        ), "`id`=%i", $this->id);
        $this->title = $paramTitle;
        $by = getUser() == null ? -1 : getUser()->getID();
        self::$EditEvent->invoke(array(array("page_id" => $this->id, "by" => $by)));
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
        getDatabase()->update("pages", array(
            "content" => $paramContent
        ), "`id`=%i", $this->id);
        $by = getUser() == null ? -1 : getUser()->getID();
        self::$EditEvent->invoke(array(array("page_id" => $this->id, "by" => $by)));
    }
    
    /**
     * Deletes the page from database.
     */
    public function delete(){
        getDatabase()->delete("pages", "id=%i", $this->id);
        $by = getUser() == null ? -1 : getUser()->getID();
        self::$DeleteEvent->invoke(array(array("page_id" => $this->id, "by" => $by)));
    }
    
    /**
     * Gets page count
     * @return int page count
     */
    public static function getPageCount(){
        getDatabase()->query("SELECT `id` FROM `pages`");
        return getDatabase()->count();
    }
    
    /**
     * Gets last page ID in database
     * @return int page ID
     */
    public static function getLastPageID(){
        $row = getDatabase()->queryFirstRow("SELECT `id` FROM `pages` ORDER BY `id` DESC");
        return $row['id'];
    }
    
    
    /**
     * Creates a page
     * @param String $paramName name of the page
     * @return Page new page object
     */
    public static function createPage($paramName){
        $newID = self::getLastPageID() + 1;
        getDatabase()->insert("pages", array(
            "id" => $newID,
            "title" => $paramName
        ));
        $by = getUser() == null ? -1 : getUser()->getID();
        self::$CreateEvent->invoke(array(array("page_id" => $newID, "by" => $by)));
        return new Page($newID);
    }
    
    /**
     * Gets the page ID of the title
     * @param String $paramTitle title
     * @return int page ID
     */
    public static function getPageID($paramTitle){
        $row = getDatabase()->queryFirstRow("SELECT `id` FROM `pages` WHERE `title` LIKE %s", $paramTitle);
        return $row['id'];
    }
    
    /**
     * Inits all the events 
     */
    public static function init(){
        self::$CreateEvent = new OrongoEvent(function($eventArgs){});
        self::$DeleteEvent = new OrongoEvent(function($eventArgs){});
        self::$EditEvent = new OrongoEvent(function($eventArgs){});
    }
}

?>
