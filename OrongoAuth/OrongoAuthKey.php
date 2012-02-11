<?php

/**
 * OrongoAuthKey Class
 *
 * @author Jaco Ruit
 */
class OrongoAuthKey {
    
    private $userID;
    private $user;
    private $expireTS;
    private $app;
    
    public function __construct($paramKeyCode){
       $row = getDatabase()->queryFirstRow("SELECT `key`, `user_id`,`app_name`,`app_desc`,`app_website`,`expire_ts` FROM `orongo_auth_keys` WHERE `key` = %s", $paramKeyCode);
       if($row ==null)
           throw new OrongoAuthException("Invalid key");
       $this->userID = $row['user_id'];
       $this->expireTS = $row['expire_ts'];
       $this->app = array(
           "name" => $row['app_name'],
           "desc" => $row['app_desc'],
           "website" => $row['app_website']
       );
       $this->key = $row['key'];
       if($this->isExpired()){
           $this->delete();
           throw new OrongoAuthException("Key expired");
       }
       try{
           $this->user = new User($this->userID);
       }catch(Exception $e){
           if($e->getCode() == USER_NOT_EXIST){
               $this->delete();
               throw new OrongoAuthException("User doesn't exist");
           }else{
               throw new OrongoAuthException("Internal error");
           }
       }
    }
    
    /**
     * @return boolean indicating if key is expired
     */
    public function isExpired(){
        return time() >= $this->expireTS;
    }
    
    /**
     * Delete key
     */
    public function delete(){
        getDatabase()->delete("orongo_auth_keys", "key=%s", $this->key);
    }
    
    /**
     * @return String random string
     */
    private function generateRandString(){
        
    }
    
    /**
     * Creates a key
     * @param int $paramUser the user
     * @param array $paramApp app info
     * @return String the new key code
     */
    public static function createKey($paramUser, $paramApp){
        if(!is_array($paramApp)) throw new IllegalArgumentException("Invalid argument, array expected");
        if(!array_key_exists("name", $paramApp) || !array_key_exists("desc", $paramApp) || !array_key_exists("website", $paramApp))
                throw new IllegalArugmentException("App info missing!");
        getDatabase()->insert("orongo_auth_keys", array(
            "key" => "",
            "userID" => $paramUser->getID(),
            "expire_ts" => "",
            "app_name" => $paramApp['name'],
            "app_desc" => $paramApp['desc'],
            "app_website" => $paramApp['website']
        ));
    }
}

?>
