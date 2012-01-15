<?php

/**
 * OrongoAuthTerminal Class
 *
 * @author Jaco Ruit
 */

class OrongoAuthTerminal implements IOrongoTerminalPlugin{
    
    private $settings;
    
    public function __construct($settings){
        $this->settings = $settings;
    }
    
    public function getVersionNumber(){
        
    }
    
    public function orongoauthsettings(){
        return $this->settings;
    }
}

?>
