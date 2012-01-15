<?php

define("ORONGOAUTH_VERSION", 2);

/**
 * Main Class
 * @author Jaco Ruit
 */

class OrongoAuthMain implements IOrongoPlugin{
    
    private $settings;
    
    public function __construct(){
        //Getting stored things as of rev 32
        $this->settings = Plugin::getSettings();
        
        //Hook terminal plugin
        if(!isset($this->settings["use_terminal"]))
                $this->settings["use_terminal"] = true;
        
        if($this->settings["use_terminal"]){
            if(!file_exists("OrongoAuthTerminal.php"))
                throw new Exception("Terminal plugin missing!");
            Plugin::hookTerminalPlugin(new OrongoAuthTerminal($this->settings));
        }
    }
    
     public function injectHTMLOnWebPage(){
        return false;
    }
    
    
    public function getHTML(){
        return null;
    }
    

    public function getVersionNumber(){
        return ORONGOAUTH_VERSION;
    }
    
    public function onInstall(){
        //TODO create database
    }
    
    public function getSettings(){
        //This does nothing
        return null;
    }

}


?>
