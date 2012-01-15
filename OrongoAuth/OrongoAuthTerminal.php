<?php

/**
 * OrongoAuthTerminal Class
 *
 * @author Jaco Ruit
 */

class OrongoAuthTerminal implements IOrongoTerminalPlugin{
    
    private $settings;
    
    /**
     * @return User logged in user
     */
    private function userCheck(){
        $user = null;
        if(function_exists("terminalAuth"))
            $user = terminalAuth();
        if($user == null)
            throw new Exception("You're not logged in.");
        return $user;
    }
    
    public function __construct($settings){
        $this->settings = $settings;
    }
    
    public function getVersionNumber(){
        return "r" . ORONGOAUTH_VERSION;
    }
    
    public function orongoauthsettings(){
        return $this->settings;
    }
    
    public function orongoauthconfirm(){
        if(!isset($_SESSION["_orongoauth_confirm_uid"]) || !isset($_SESSION["_orongoauth_confirm_action"]) || !isset($_SESSION["_orongoauth_confirm_expire"])){
            $this->killConfirm();
            throw new Exception("Nothing to confirm!");
        }
        
        #expire
        if($_SESSION["_orongoauth_confirm_expire"] < time()){
            $this->killConfirm();
            throw new Exception("This action has been expired.");
        }
        
        $user = $this-userCheck();
        
        #uid
        if($_SESSION["_orongoauth_confirm_uid"] != $user->getID()){
            $this->killConfirm();
            throw new Exception("Invalid confirm user ID");
        }
        
        #action
        switch($_SESSION["_orongoauth_confirm_action"]){
            case 'update':
                break;
            default:
                $this->killConfirm();
                throw new Exception("Unknown confirm action!");
                break;
        }
    }
    
    /**
     * kills the confirm action
     */
    private function killConfirm(){
        $_SESSION["_orongoauth_confirm_uid"] = null;
        $_SESSION["_orongoauth_confirm_action"] = null;
        $_SESSION["_orongoauth_confirm_expire"] = null;
    }
    
    /**
     * registers a confirm action
     * @param String $paramAction action
     * @param int $paramExpire expire timestamp
     * @param User $paramUser user object to bind this confirm to
     */
    private function registerConfirm($paramAction,$paramExpire, $paramUser){
        $_SESSION["_orongoauth_confirm_expire"] = $paramExpire;
        $_SESSION["_orongoauth_confirm_action"] = $paramAction;
        $_SESSION["_orongoauth_confirm_uid"] = $paramUser->getID();
    }
    
    public function orongoauthupdate(){
        $user = $this->userCheck();
        
        $latest = OrongoAuthUpdater::getLatestVersion(REVISION);
        
        if($latest["version"] == ORONGOAUTH_VERSION)
            "You're currently running the latest version! (r" . $latest["version"] . ")";
        else{
            $return = "There is a new version: " . $latest["version"] . "\n";
            $return .= "Do you want to update now?\n";
            $this->registerConfirm('update', time() + 10000, $user);
            $return .= "To update execute command 'orongoauthconfirm'\n";
            return $return;
        }
    }
    
    public function orongoauthcheck(){
        $files = array(
          "OrongoAuthMain.php",
          "OrongoAuthTerminal.php",
          "OrongoAuthUpdater.php",
          "info.xml"
        );
        
        foreach($files as $file)
            if(!file_exists($file))
                throw new Exception($file . " doesn't exist!");
        
        return "OK! Your OrongoAuth installation has all the files!";
    }
}

?>
