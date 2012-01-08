<?php

/**
 * User Class
 *
 * @author Jaco Ruit
 */
class User {
    private static $urlCharacters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVW';
    private static $urlLength = 20;
    
    #   variables
    private $id;
    private $rank;
    private $email;
    private $name;
    private $activateStatus;

    
    
    #   constructors
    /**
     * Create User Object
     * 
     * @param int $paramID ID of User or -1 to create General User Object
     * @author Jaco Ruit
     */
    public function __construct($paramID){
        $this->id = $paramID;
        $q = "SELECT `rank`,`email`,`name`,`activated`  FROM `users` WHERE `id` = '" . $this->id . "'";
        $result = @mysql_query($q);
        if(mysql_num_rows($result) < 1){
            throw new Exception('User doesnot exist!', USER_NOT_EXIST);
        }
        $row = mysql_fetch_assoc($result);
        $this->rank = $row['rank'];
        $this->email = $row['email'];
        $this->name = stripslashes($row['name']);
        $this->activateStatus = $row['activated'];
        mysql_free_result($result);
    }
    
    
    
    
    #   id
    /**
     * @return int User ID
     */
    public function getID(){
        return $this->id;
    }
    
    
    
    #   rank
    /**
     * @return int User Rank
     */
    public function getRank(){
        return $this->rank;
    }
    
    /**
     * Set User Rank
     * @param int $paramRank new User Rank
     */
    public function setRank($paramRank){
        $q = "UPDATE `users` SET `rank`='" . $paramRank . "' WHERE `id` = '" . $this->id ."'";
        @mysql_query($q);
        $this->rank = $paramRank;
    }
    
    
    
    #   email
    /**
     * @return String User Email Address
     */
    public function getEmail(){
        return $this->email;
    }
    
    /**
     * Set User Email Address
     * @param String $paramEmail new User Email Address
     */
    public function setEmail($paramEmail){
        $q = "UPDATE `users` SET `email`='" . $paramEmail . "' WHERE `id` = '" . $this->id ."'";
        @mysql_query($q);
        $this->email = $paramEmail;
    }
    
    
    #   name
    /**
     * @return String User Name
     */
    public function getName(){
        return $this->name;
    }
    
    /**
     * Set User Name
     * @param String $paramName new User Name
     */
    public function setName($paramName){
        $q = "UPDATE `users` SET `name`='" . addslashes($paramName) . "' WHERE `id` = '" . $this->id ."'";
        @mysql_query($q);
        $this->name = $paramName;
    }
    
    #   activateStatus
    /**
     * @return boolean indicating if user is activated
     */
    public function isActivated(){
        return $this->activateStatus == 1;
    }
    
    
    
    #   General Functions
    /**
     * Get Password of User
     * @param int $paramID ID of User to get password from
     * @return String Hashed password of User
     */
    private static function getPassword($paramID){
        $q = "SELECT `password` FROM `users` WHERE `id` = '" . $paramID . "'";
        $result = @mysql_query($q);
        $row = mysql_fetch_assoc($result);
        $pw = $row['password'];
        mysql_free_result($result);
        return $pw;
    }
    
    /**
     * Compares arguments with database
     * @param int    $paramID User ID to compare the password
     * @param String $paramPassword Hashed user password to compare 
     * @return boolean true if they match, false if they are wrong
     */
    public static function isGoodPassword($paramID, $paramPassword){
        return $paramPassword == self::getPassword($paramID);
    }
    
    
    /**
     * Checks if username exists in database
     * @param String $paramName Username to compare
     * @return boolean true if exists, false if it doesn't exist
     */
    public static function usernameExists($paramName){
        $q = "SELECT `id` FROM `users` WHERE `name` LIKE '" . addslashes($paramName) ."'";
        $result = @mysql_query($q);
        $noOfRows = mysql_num_rows($result);
        mysql_free_result($result);
        return $noOfRows >= 1; 
    }
    
    /**
     * Registers user
     * @param String $paramName Username of new user
     * @param String $paramEmail Email Address of new user
     * @param String $paramPassword Hashed password of new user
     * @param int    $paramRank Rank of new user
     * @return boolean true if registration was succesful else false
     */
    public static function registerUser ($paramName, $paramEmail, $paramPassword, $paramRank){
        $newID = self::getLastUserID() + 1;
        $q = "INSERT INTO `users` (`id`, `name`, `password`, `email`, `rank`, `activated`) VALUES ('" . $newID . "', '" . addslashes($paramName) . "', '" . $paramPassword . "', '" . $paramEmail . "', '" . $paramRank . "', '0')"; 
        @mysql_query($q);
        return $newID == self::getLastUserID();
    }
    
    /**
     * Gets user count
     * @return int user count
     */
    public static function getUserCount(){
        $q = 'SELECT `id` FROM `users` WHERE `activated`=1';
        $result = @mysql_query($q);
        $num = mysql_num_rows($result);
        mysql_free_result($result);
        return $num;
    }
    
    /**
     * Gets last user ID in database
     * @return int last user ID
     */
    public static function getLastUserID(){
        $q = 'SELECT `id` FROM `users` ORDER BY `id` DESC';
        $result = @mysql_query($q);
        $row = mysql_fetch_assoc($result);
        $lastID = $row['id'];
        mysql_free_result($result);
        return $lastID;
    }
    
    /**
     * Generates activation URL
     * @param String $paramID ID of User
     * @return String activation URL
     */
    public static function generateActivationURL($paramID){
        $websiteURL = Settings::getWebsiteURL();
        $activationCode = self::getRandomString();
        $q = "INSERT INTO `activations` (`userID`, `code`) VALUES ('" . $paramID  . "', '" . $activationCode . "')";
        @mysql_query($q);
        return $websiteURL . 'orongo-activation.php?code=' .  $activationCode;
    }
    
    /**
     * Generates a random string for activation urls
     * @return String random string
     */
    public static function getRandomString(){
        $string = '';    
        for ($i = 0; $i < self::$urlLength; $i++) {
            $string .= self::$urlCharacters[mt_rand(0, 50)];
        }
        return $string;
    }
    
    /**
     * Gets activation URL of User
     * @param int $paramID ID of User
     * @return String Activation URL
     */
    public static function getActivationURL($paramID){
        $q = "SELECT `code` FROM `activations` WHERE `userID` = '" . $paramID . "'";
        $result = @mysql_query($q);
        $row = mysql_fetch_assoc($result);
        $url = Settings::getWebsiteURL() . $row['code'];
        mysql_free_result($result);
        return $url;
    }
    
    /**
     * Activates user
     * @param int $paramID ID of User
     */
    public static function activateUser($paramID){
        $q = "UPDATE `users` SET `activated` = '1' WHERE `id` = '" . $paramID . "'";
        @mysql_query($q);
    }
    
    /**
     * Checks if the activation code passed by the argument is good
     * @param String $paramCode Activation Code
     * @return boolean indicating if the activation code is good
     */
    public static function isGoodActivationCode($paramCode){
        $q = "SELECT `id` FROM `activations` WHERE `code` = '" . $paramCode . "'";
        $result = @mysql_query($q);
        $c = mysql_num_rows($result);
        mysql_free_result($result);
        return $c == 1;
    }
    
    /**
     * Gets the user ID related to the activation code
     * @param String $paramCode Activation Code
     * @return int User ID
     */
    public static function getUserIDByActivationCode($paramCode){
        $q = "SELECT `userID` FROM `activations` WHERE `code` = '" . $paramCode . "'";
        $result = @mysql_query($q);
        $row = mysql_fetch_assoc($result);
        $userID = $row['userID'];
        mysql_free_result($result);
        return $userID;
    }
    
    /**
     * Deletes activation code
     * @param String $paramCode Activation Code
     */
    public static function deleteActivationCode($paramCode){
        $q = "DELETE FROM `activations` WHERE `code`='" . $paramCode ."'";
        @mysql_query($q);
    }
    
    /**
     * Gets the user ID of the username
     * @param String $paramName username
     * @return int user ID
     */
    public static function getUserID($paramName){
        $q = "SELECT `id` FROM `users` WHERE `name` LIKE '" . addslashes($paramName) . "'";
        $result = @mysql_query($q);
        $row = mysql_fetch_assoc($result);
        mysql_free_result($result);
        return $row['id'];
    }
    
    /**
     * Gets the username of the user ID
     * @param int $paramID userID
     * @return String username
     */
    public static function getUserName($paramID){
        $q = "SELECT `name` FROM `users` WHERE `id`='" . $paramID . "'";
        $result = @mysql_query($q);
        $row = mysql_fetch_assoc($result);
        mysql_free_result($result);
        return $row['name'];
    }
    
   
    /**
     * Checks if the user is activated
     * @param int $paramID User ID
     * @return boolean indicating if the user is activated
     */
    public static function userIsActivated($paramID){
        $q = "SELECT `name` FROM `users` WHERE `activated` = '1' AND `id` = '" . $paramID . "'";
        $result = @mysql_query($q);
        $count = mysql_num_rows($result);
        mysql_free_result($result);
        return $count > 0;
    }
}

?>
