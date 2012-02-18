<?php
/**
 * OrongoNotifier Class
 *
 * @author Jaco Ruit
 */

class OrongoNotifier extends AjaxAction {
    
    private $refreshInterval;
    /**
     * Dispatch a notification
     * @param OrongoNotification $paramNotification OrongoNotification 
     */
    public static function dispatchNotification($paramNotification){
        if(($paramNotification instanceof OrongoNotification) == false) throw new IllegalArgumentException("Invalid argument, OrongoNotification expected.");
        getDatabase()->insert("notifications", array(
           "id" => self::getLastNotificationID() + 1,
           "title" => $paramNotification->getTitle(),
           "text" => $paramNotification->getText(),
           "image" => $paramNotification->getImage(),
           "time" => $paramNotification->getTime(),
           "userID" => $paramNotification->getUser()->getID()
        ));
        
    }
    
    /**
     * Deletes a notification from database
     * @param User $paramUser User object to delete the notifications from
     */
    public static function deleteNotificationsByUser($paramUser){
       if(($paramUser instanceof User) == false) throw new IllegalArgumentException("Invalid argument, User object expected.");
       getDatabase()->delete("notifications", "userID=%i", $paramUser->getID());
    }
    
    /**
     * Fetch notifications for User
     * @param User $paramUser User object 
     */
    public static function fetchNotificationsByUser($paramUser){
        if(($paramUser instanceof User) == false) throw new IllegalArgumentException("Invalid argument, User object expected.");
        $notifications = array();
        $rows = getDatabase()->query("SELECT `id`, `title`, `text`, `image`, `time` FROM `notifications` WHERE `userID` = %i", $paramUser->getID());
        foreach($rows as $row){
            $notifications[count($notifications)] = new OrongoNotification($row['title'], $row['text'], $paramUser, $row['image'], $row['time']);
        }  
        return $notifications;
    }
    
    /**
     * Gets last notification ID in database
     * @return int notification ID
     */
    public static function getLastNotificationID(){
        $row = getDatabase()->queryFirstRow("SELECT `id` FROM `notifications` ORDER BY `id` DESC");
        return $row['id'];
    }
    
    /**
     * Init the OrongoNotifier AJAX
     * @param int $paramRefreshInterval the refresh interval (default 6000)
     */
    public function __construct($paramRefreshInterval = 1000){
        $this->refreshInterval = $paramRefreshInterval;
    }

    public function doImports() {
        if(!getDisplay()->isImported(orongoURL('orongo-admin/theme/gritter/css/jquery.gritter.css')))
            getDisplay()->import(orongoURL('orongo-admin/theme/gritter/css/jquery.gritter.css'));
        if(!getDisplay()->isImported(orongoURL('js/jquery.gritter.min.js')))
            getDisplay()->import(orongoURL('js/jquery.gritter.min.js'));
        if(!getDisplay()->isImported(orongoURL('js/ajax.notifications.js')))
            getDisplay()->import(orongoURL('js/ajax.notifications.js')); 
    }

    public function toJS() {
        $generatedJS = " window.setInterval(function() {";
        $generatedJS .= " try{";
        $generatedJS .= "   fetchNotifications('" . orongoURL("ajax/fetchNotifications.php") . "'); ";
        $generatedJS .= "}catch(err){ alert(err); }";
        $generatedJS .= " }, " . $this->refreshInterval . "); ";
        return $generatedJS;
    }
}

?>
