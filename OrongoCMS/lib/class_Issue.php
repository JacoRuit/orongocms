<?php
/**
 * Issue Object
 *
 * @author Jaco Ruit
 */
class Issue {
    
    private $title;
    private $content;
    private $author;
    private $status;
    private $labels;
    
    private static $status_opts = array ("New", "Accepted", "Dangerous");
    
    /**
     * Create an issue object
     * @param String $paramTitle title of the issue
     */
    public function __construct($paramTitle){
        $this->title = $paramTitle;
        $this->status = "New";
        $this->labels = array();
    }
    
    /**
     * @param String $paramTitle new title
     */
    public function setTitle($paramTitle){
        if(!is_string($paramTitle)) throw new IllegalArgumentException("Invalid argument, string expected.");
        $this->title = $paramTitle;
    }
    
    /**
     * @return String the title
     */
    public function getTitle(){
        return $this->title;
    }
    
    /**
     * @param String $paramContent new content
     */
    public function setContent($paramContent){
        if(!is_string($paramContent)) throw new IllegalArgumentException("Invalid argument, string expected.");
        $this->content = $paramContent;
    }
    
    /**
     * @return String the content
     */
    public function getContent(){
        return $this->content;
    }
    
    /**
     * @param String $paramAuthor new author
     */
    public function setAuthor($paramAuthor){
        if(!is_string($paramAuthor)) throw new IllegalArgumentException("Invalid argument, string expected.");
        $this->author = $paramAuthor;
    }
    
    /**
     * @return String the author
     */
    public function getAuthor(){
        return $this->author;
    }
    
    /**
     * @param String $paramStatus new status
     */
    public function setStatus($paramStatus){
        if(!is_string($paramStatus)) throw new IllegalArgumentException("Invalid argument, string expected.");
        if(!in_array($paramStatus, self::$status_opts)) throw new Exception("Invalid status.");
        $this->status = $paramStatus;
    }
    
    /**
     * @return String the status
     */
    public function getStatus(){
        return $this->status;
    }
    
    /**
     * @param array $paramLabels new labels
     */
    public function setLabels($paramLabels){
        if(!is_array($paramLabels)) throw new IllegalArgumentException("Invalid argument, array expected.");
        $this->labels = $paramLabels;
    }
    
    /**
     * @return array the labels
     */
    public function getLabels(){
        return $this->labels;
    }
    
    /**
     * Generate XML representation of the issue
     * @return String XML (Atom)
     */
    public function toXML(){
        $xml = "<?xml version='1.0' encoding='UTF-8'?>";
        $xml .= "<entry xmlns='http://www.w3.org/2005/Atom' xmlns:issues='http://schemas.google.com/projecthosting/issues/2009'>";
        
        if(!is_string($this->title) || strlen($this->title) < 10) throw new Exception("Title too short.");
        $xml .= " <title>" . htmlspecialchars($this->title) . "</title>";
        
        if(!is_string($this->content) || strlen($this->content) < 20) throw new Exception("Content too short.");
        $xml .= " <content type='html'>" . htmlspecialchars($this->content) .  "</content>";
        
        if(!is_string($this->author) || strlen($this->author) < 3) throw new Exception("Author too short.");
        $xml .= " <author><name>" . htmlspecialchars($this->content) .  "</name></author>";
        
        if(!is_string($this->status) || strlen($this->status) < 3) throw new Exception("Status too short.");
        $xml .= " <issues:status>" . htmlspecialchars($this->status) .  "</issues:status>";
        
        if(!is_array($this->labels)) throw new Exception("Labels is not an array");
        foreach($this->labels as $label){
            if(!is_string($label)) continue;
            $xml .= " <issues:label>" . htmlspecialchars($label) .  "</issues:label>";
        }
        
        $xml .= " </entry>";
        
        return $xml;
    }
}

?>
