<?php


/**
 * Session Class
 *
 * @author Jaco Ruit
 */
class Session {
    private static $sessionCharacters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVW';
    private static $sessionLength = 40;
    /**
     * Returns the user ID of the Session ID
     * @param String $paramSessionID Session ID
     * @return int User ID
     */
    public static function getUserID($paramSessionID){
        $row = getDatabase()->queryFirstRow("SELECT `userID` FROM `sessions` WHERE `sessionID` = %s", $paramSessionID);
        return $row['userID'];
    }
    
    /**
     * Creates a session in database
     * @param int $paramUserID ID of the User
     * @return String Session ID
     */
    public static function createSession($paramUserID){
        $sessionID = self::getRandomString();
        getDatabase()->insert("sessions", array(
           "userID" => $paramUserID,
           "sessionID" => $sessionID
        ));
        return $sessionID;
    }
    
    /**
     * Generates a random string for sessions
     * @return String random string
     */
    public static function getRandomString(){
        $string = '';    
        for ($i = 0; $i < self::$sessionLength; $i++) {
            $string .= self::$sessionCharacters[mt_rand(0, 50)];
        }
        return $string;
    }
    
    /**
     * Checks if its a good Session ID
     * @param String $paramSessionID Session ID
     * @return boolean indicating if its a good session ID
     */
    public static function isGoodSessionID($paramSessionID){
        if(strlen($paramSessionID) != self::$sessionLength){
            return false;
        }
        getDatabase()->query("SELECT `userID` FROM `sessions` WHERE `sessionID`= %s", $paramSessionID);
        $count = getDatabase()->count();
        return $count > 0;
    }
    
    /**
     * Deletes session
     * @param String $paramSessionID Session ID
     */
    public static function delete($paramSessionID){
        getDatabase()->delete("sessions","`sessionID`=%s", $paramSessionID);
    }
}

?>
