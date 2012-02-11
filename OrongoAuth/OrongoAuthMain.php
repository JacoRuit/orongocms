<?php

define("ORONGOAUTH_VERSION", 2);

/**
 * Main Class
 * @author Jaco Ruit
 */

class OrongoAuthMain extends OrongoPluggableObject{
    
    private $settings;
    
    public function __construct($args){
        $authKey = $args['auth_key'];
        
        $this->settings = Plugin::getSettings($authKey);
        
        //Hook terminal plugin
        if(!isset($this->settings["use_terminal"]))
                $this->settings["use_terminal"] = true;
        
        if($this->settings["use_terminal"]){
            if(!file_exists("OrongoAuthTerminal.php"))
                throw new Exception("Terminal plugin missing!");
            Plugin::hookTerminalPlugin(new OrongoAuthTerminal($this->settings));
        }
        
        if(getCurrentPage() == "orongo-login" && isset($_SERVER['orongo-auth-app-name']) && isset($_SERVER['orongo-auth-app-desc']) && isset($_SERVER['orongo-auth-app-website']) && isset($_SERVER['orongo-auth-time'])){
 
        }
    }
   

    public function getVersionString(){
        return "v" . ORONGOAUTH_VERSION;
    }
    
    public function onInstall(){
        getDatbase()->query("CREATE TABLE `orongo_auth_keys` IF NOT EXISTS");
    }
    


}


?>
