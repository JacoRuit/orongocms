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
     * @param User $paramUser User to notify
     */
    public static function dispatchNotification($paramNotification, $paramUser){
        if(($paramNotification instanceof OrongoNotification) == false) throw new IllegalArgumentException("Invalid argument, OrongoNotification expected.");
        if(($paramUser instanceof User) == false) throw new IllegalArgumentException("Invalid argument, User object expected.");
        getDatabase()->insert("notifications", array(
           "id" => self::getLastNotificationID() + 1,
           "title" => $paramNotification->getTitle(),
           "text" => $paramNotification->getText(),
           "image" => $paramNotification->getImage(),
           "time" => $paramNotification->getTime(),
           "userID" => $paramUser->getID()
        ));
    }
    
    /**
     * Deletes a notification from database
     * @param int $paramID notification ID
     */
    public static function deleteNotification($paramID){
       getDatabase()->delete("notifications", "id=%i", $paramID);
    }
    
    /**
     * Fetch notifications for User
     * @param User $paramUser User object 
     * @return array Array with Notification & ID
     */
    public static function fetchNotificationsByUser($paramUser){
        if(($paramUser instanceof User) == false) throw new IllegalArgumentException("Invalid argument, User object expected.");
        $notifications = array();
        $rows = getDatabase()->query("SELECT `id`, `title`, `text`, `image`, `time` FROM `notifications` WHERE `userID` = %i", $paramUser->getID());
        foreach($rows as $row){
            $notifications[count($notifications)] = array(
                "notification" => new OrongoNotification($row['title'], $row['text'], $row['image'], $row['time']),
                "id" => $row['id']
            );
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

    public function doImports(){
        getDisplay()->addHTML("<link href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>");
        if(!getDisplay()->isImported(orongoURL('orongo-admin/theme/gritter/css/jquery.gritter.css')))
            getDisplay()->import(orongoURL('orongo-admin/theme/gritter/css/jquery.gritter.css'));
        if(!getDisplay()->isImported(orongoURL('js/jquery.gritter.min.js')))
            getDisplay()->import(orongoURL('js/jquery.gritter.min.js'));
        if(!getDisplay()->isImported(orongoURL('js/ajax.notifications.js')))
            getDisplay()->import(orongoURL('js/ajax.notifications.js')); 
    }

    public function toJS() {
        $generatedJS = " window.setInterval(function() {";
        //$generatedJS .= " try{";
        $generatedJS .= "   fetchNotifications('" . orongoURL("ajax/fetchNotifications.php") . "'); ";
        //$generatedJS .= "}catch(err){ alert(err); }";
        $generatedJS .= " }, " . $this->refreshInterval . "); ";
        return $generatedJS;
    }
}

?>
