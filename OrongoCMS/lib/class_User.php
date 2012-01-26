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
        $row = getDatabase()->queryFirstRow("SELECT `rank`,`email`,`name`,`activated`  FROM `users` WHERE `id` = %i", $this->id);
        if($row == null){
            throw new Exception('User doesnot exist!', USER_NOT_EXIST);
        }
        $this->rank = $row['rank'];
        $this->email = $row['email'];
        $this->name = stripslashes($row['name']);
        $this->activateStatus = $row['activated'];
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
        getDatabase()->update("users", array(
           "rank" => $paramRank 
        ), "id = %i", $this->id);
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
        getDatabase()->update("users", array(
           "email" => $paramRank 
        ), "`id`=%i", $this->id);
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
        getDatabase()->update("users", array(
           "name" => addslashes($paramName) 
        ), "`id`=%i", $this->id);
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
        //$q = "SELECT `password` FROM `users` WHERE `id` = '" . $paramID . "'";
        $row = getDatabase()->queryFirstRow("SELECT `password` FROM `users` WHERE `id` = %i", $paramID);
        return $row['password'];
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
        getDatabase()->query("SELECT `id` FROM `users` WHERE `name` LIKE %s", addslashes($paramName));
        $noOfRows = getDatabase()->count();
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
        getDatabase()->insert("users", array(
            "id" => $newID,
            "name" => $paramName,
            "password" => $paramPassword,
            "rank" => $paramRank,
            "activated" => 0
        ));
        return $newID == self::getLastUserID();
    }
    
    /**
     * Gets user count
     * @return int user count
     */
    public static function getUserCount(){
        getDatabase()->query('SELECT `id` FROM `users` WHERE `activated`=1');
        $num = getDatabase()->count();
        return $num;
    }
    
    /**
     * Gets last user ID in database
     * @return int last user ID
     */
    public static function getLastUserID(){
        $row = getDatabase()->queryFirstRow('SELECT `id` FROM `users` ORDER BY `id` DESC');
        return $row['id'];
    }
    
    /**
     * Generates activation URL
     * @param String $paramID ID of User
     * @return String activation URL
     */
    public static function generateActivationURL($paramID){
        $websiteURL = Settings::getWebsiteURL();
        $activationCode = self::getRandomString();
        getDatabase()->insert("activations", array(
            "userID" => $paramID,
            "code" => $activationCode
        ));
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
        $row = getDatabase()->queryFirstRow("SELECT `code` FROM `activations` WHERE `userID` = %i", $paramID);
        $url = Settings::getWebsiteURL() . $row['code'];
        mysql_free_result($result);
        return $url;
    }
    
    /**
     * Activates user
     * @param int $paramID ID of User
     */
    public static function activateUser($paramID){
        getDatabase()->update("users",array(
            "activated" => 1
        ), "`id`=%i", $paramID);
    }
    
    /**
     * Checks if the activation code passed by the argument is good
     * @param String $paramCode Activation Code
     * @return boolean indicating if the activation code is good
     */
    public static function isGoodActivationCode($paramCode){
        getDatabase()->query("SELECT `id` FROM `activations` WHERE `code` = %s", $paramCode);
        $c = getDatabase()->count();
        return $c == 1;
    }
    
    /**
     * Gets the user ID related to the activation code
     * @param String $paramCode Activation Code
     * @return int User ID
     */
    public static function getUserIDByActivationCode($paramCode){
        $row = getDatabase()->queryFirstRow("SELECT `userID` FROM `activations` WHERE `code` = %s", $paramCode);
        return $row['userID'];
    }
    
    /**
     * Deletes activation code
     * @param String $paramCode Activation Code
     */
    public static function deleteActivationCode($paramCode){
        getDatabase()->delete("activations", "`code`=%s", $paramCode);
        @mysql_query($q);
    }
    
    /**
     * Gets the user ID of the username
     * @param String $paramName username
     * @return int user ID
     */
    public static function getUserID($paramName){
        $row = getDatabase()->queryFirstRow("SELECT `id` FROM `users` WHERE `name` LIKE %s", $paramName);
        return $row['id'];
    }
    
    /**
     * Gets the username of the user ID
     * @param int $paramID userID
     * @return String username
     */
    public static function getUserName($paramID){
        $row = getDatabase()->queryFirstRow("SELECT `name` FROM `users` WHERE `id` = %i", $paramID);
        return $row['name'];
    }
    
   
    /**
     * Checks if the user is activated
     * @param int $paramID User ID
     * @return boolean indicating if the user is activated
     */
    public static function userIsActivated($paramID){
        getDatabase()->query("SELECT `name` FROM `users` WHERE `activated` = '1' AND `id` = %i", $paramID);
        $count = getDatabase()->count();
        return $count > 0;
    }
}

?>
