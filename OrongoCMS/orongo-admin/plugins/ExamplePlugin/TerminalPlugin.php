<?php


class TerminalPlugin implements IOrongoTerminalPlugin {
    
    public function getVersionNumber(){
        return '0.2.3';
    }
    
    public function say($hi ="hi"){
        return $hi;
    }
}

?>
