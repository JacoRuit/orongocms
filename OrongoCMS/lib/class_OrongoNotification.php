<?php

/**
 * OrongoNotification Class
 * 
 * @author Jaco Ruit
 */
class OrongoNotification {
    
    private $title;
    private $text;
    private $image;
    private $time;
    private $user;
    
    /**
     * Init the object
     * @param String $paramTitle title of notification
     * @param String $paramText text of notification
     * @param User $paramUser User to who the notification has to be send
     * @param String $paramImage image url for notification [optional]
     * @param int $paramTime duration of notification (default = 10000 ms)
     */
    public function __construct($paramTitle, $paramText, $paramUser, $paramImage = null , $paramTime = 10000){
        $this->title = $paramTitle;
        $this->text = $paramText;
        $this->image = $paramImage;
        $this->time = $paramTime;
        $this->user = $paramUser;
    }
    
    /**
     * Dispatches the Notification 
     */
    public function dispatch(){
        OrongoNotifier::dispatchNotification($this);
    }
    
    /**
     * @param String $paramTitle new title 
     */
    public function setTitle($paramTitle){
        $this->title = $paramTitle;
    }
    
    /**
     * @return String title of notification 
     */
    public function getTitle(){
        return $this->title;
    }
    
    /**
     * @param String $paramText new text 
     */
    public function setText($paramText){
        $this->text = $paramText;
    }
    
    /**
     * @return String text of notification 
     */
    public function getText(){
        return $this->text;
    }
    
    /**
     * @param String $paramImage new image url 
     */
    public function setImage($paramImage){
        $this->image = $paramImage;
    }
    
    /**
     * @return String image url of notificaiton 
     */
    public function getImage(){
        return $this->image;
    }
    
    /**
     * @param int $paramTime new duration time 
     */
    public function setTime($paramTime){
        $this->time = $paramTime;
    }
    
    /**
     * @return int duration time of notification 
     */
    public function getTime(){
        return $this->time;
    }
    
    /**
     * @return User user target of notification 
     */
    public function getUser(){
        return $this->user;
    }
}

?>
