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
        $row = getDatabase()->queryFirstRow("SELECT `id`,`rank`,`email`,`name`,`activated`  FROM `users` WHERE `id` = %i", $this->id);
        if($row == null){
            throw new Exception('User doesnot exist!', USER_NOT_EXIST);
        }
        $this->rank = $row['rank'];
        $this->email = $row['email'];
        $this->id = $row['id'];
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
     * @return String User Rank 
     */
    public function getRankString(){
        switch($this->getRank()){
                case 0:
                    $rank = l("Banned");
                    break;
                case 1:
                    $rank = l("User");
                    break;
                case 2:
                    $rank = l("Writer");
                    break;
                case 3:
                    $rank = l("Admin");
                    break;
                default:
                    $rank = "Unknown";
                    break;
       }
       return $rank;
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
           "email" => $paramEmail
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
    
    /**
     * Deletes the user from database.
     */
    public function delete(){
        getDatabase()->delete("users", "id=%i", $this->id);
    }
    
    
    #   General Functions
    /**
     * Get Password of User
     * @param int $paramID ID of User to get password from
     * @return String Hashed password of User
     */
    private static function getPassword($paramID){
        $row = getDatabase()->queryFirstRow("SELECT `password` FROM `users` WHERE `id` = %i", $paramID);
        return $row['password'];
    }
    
    /**
     * Set Password of User
     * @param int $paramID ID of User to set password 
     * @param String $paramPassword new hashed password
     */
    public static function setPassword($paramID, $paramPassword){
        getDatabase()->update("users", array(
            "password" => $paramPassword
        ), "id=%i", $paramID);
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
     * @return User new User object
     */
    public static function registerUser ($paramName, $paramEmail, $paramPassword, $paramRank){
        $newID = self::getLastUserID() + 1;
        if($paramRank != 1 && $paramRank != 2 && $paramRank != 3) throw new IllegalArgumentException("Invalid rank!");
        getDatabase()->insert("users", array(
            "id" => $newID,
            "name" => $paramName,
            "email" => $paramEmail,
            "password" => $paramPassword,
            "rank" => $paramRank,
            "activated" => 0
        ));
        if($newID != self::getLastUserID()) throw new Exception("Account not created!");
        return new User($newID);
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
        getDatabase()->insert("user_activations", array(
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
        $row = getDatabase()->queryFirstRow("SELECT `code` FROM `user_activations` WHERE `userID` = %i", $paramID);
        $url = orongoURL("orongo-activation.php?code=" . $row['code']);
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
        getDatabase()->query("SELECT `id` FROM `user_activations` WHERE `code` = %s", $paramCode);
        $c = getDatabase()->count();
        return $c == 1;
    }
    
    /**
     * Gets the user ID related to the activation code
     * @param String $paramCode Activation Code
     * @return int User ID
     */
    public static function getUserIDByActivationCode($paramCode){
        $row = getDatabase()->queryFirstRow("SELECT `userID` FROM `user_activations` WHERE `code` = %s", $paramCode);
        return $row['userID'];
    }
    
    /**
     * Deletes activation code
     * @param String $paramCode Activation Code
     */
    public static function deleteActivationCode($paramCode){
        getDatabase()->delete("user_activations", "`code`=%s", $paramCode);
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
