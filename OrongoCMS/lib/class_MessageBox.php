<?php

/**
 * MessageBox Object
 * @author Ruit
 */
class MessageBox implements IHTMLConvertable {
    
    private static $messageCount = 0;
    
    private $message = "";
    private $title = "";
    private $exception = null;
    
    /**
     * Construct MessageBox Object
     * 
     * @param String $paramMessage  Message to show (Optional)
     * @param String $paramTitle    Title of messagebox (Optional)
     */
    public function __construct($paramMessage = "An error occured while rendering the page", $paramTitle = "OrongoCMS"){
        $this->message = $paramMessage;
        $this->title = $paramTitle;
        self::$messageCount++;
    }
    
    /**
     * @return String title of the messagebox
     */
    public function getTitle(){
        return $this->title;
    }
    
    /**
     * @param String $paramTitle new title
     */
    public function setTitle($paramTitle){
        $this->title = $paramTitle;
    }
    
    /**
     * @return String message of the messagebox
     */
    public function getMessage(){
        return $this->message;
    }
    
    /**
     * @param String $paramMessage new message
     */
    public function setMessage($paramMessage){
        $this->message = $paramMessage;
    }
    
    /**
     * Binds the exception to the messagebox
     * 
     * @param Exception $paramException Exception to bind, can also be derived from Exception class
     */
    public function bindException($paramException){
        if(($paramException instanceof Exception) == false)
            throw new IllegalArgumentException("Can't bind exception: the object passed is not an Exception or derived from Exception");
        $this->exception = $paramException;
    }
    
    /**
     * Gets the imports needed to show the messagebox.
     * @return String HTML Code for imports
     */
    public function getImports(){
        $websiteURL = Settings::getWebsiteURL();
        $generatedHTML = 
        "<script src=\"http://code.jquery.com/jquery-latest.js\" type=\"text/javascript\"></script>
        <script src=\"https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js\" type=\"text/javascript\"></script>     
        <link rel=\"stylesheet\" href=\"" . $websiteURL . "orongo-admin/style/smoothness/jquery-ui-1.8.16.custom.css\" type=\"text/css\"/>";
        return $generatedHTML;
    }
    
    public function toHTML() {
        $generatedHTML = "<script type=\"text/javascript\">$(document).ready(function(){  "; 
        $generatedHTML .= "$('#_orongocms_msgbox_" . self::$messageCount . "').dialog();  });</script>";
        $generatedHTML .= "<div id=\"_orongocms_msgbox_" . self::$messageCount ."\" title=\"" . $this->title . "\">" . $this->message; 
        if($this->exception != null)
            $generatedHTML .= "<br /><br /><strong>" . get_class($this->exception) . ": </strong><br />" . $this->exception->getMessage();
        $generatedHTML .= "</div>";
        return $generatedHTML;
    }
}

?>
