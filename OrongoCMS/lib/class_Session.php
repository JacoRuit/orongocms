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
        $q = "SELECT `userID` FROM `sessions` WHERE `sessionID` = '" . $paramSessionID ."'";
        $result = getDatabase()->execQuery($q);
        $set = mysql_fetch_assoc($result);
        mysql_free_result($result);
        return $set['userID'];
    }
    
    /**
     * Creates a session in database
     * @param int $paramUserID ID of the User
     * @return String Session ID
     */
    public static function createSession($paramUserID){
        $sessionID = self::getRandomString();
        $q = "INSERT INTO `sessions` (`userID`,`sessionID`) VALUES ('" . $paramUserID . "', '" . $sessionID ."')";
        getDatabase()->execQuery($q);
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
        $q = "SELECT `userID` FROM `sessions` WHERE `sessionID`='" . $paramSessionID . "'";
        $result = getDatabase()->execQuery($q);
        $count = mysql_num_rows($result);
        mysql_free_result($result);
        return $count > 0;
    }
    
    /**
     * Deletes session
     * @param String $paramSessionID Session ID
     */
    public static function delete($paramSessionID){
        $q = "DELETE FROM `sessions` WHERE `sessionID`='" . $paramSessionID . "'";
        getDatabase()->execQuery($q);
    }
}

?>
