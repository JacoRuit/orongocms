<?php

class ConfigFile{
    
    private $data;
    
    public function __construct(){
        $this->data = "";
    }
    
    
    public function writeConfigArray($paramArray){
        $this->data .= "/** OrongoCMS Configuration File written on " . time() . " by the ConfigFile class **/ ";
        $this->data .= "global \$_CONFIG; ";
        foreach($paramArray as $key => $val){
            if(is_array($val)){
                $arrayName = $key;
                $this->data .= "\$_CONFIG['" . $key . "'] = array(); ";
                foreach($val as $key=>$val2){
                    "\$_CONFIG['" . $arrayName . "']['" . $key . "'] = '" . $val2 . "'; ";
                }
            }else{
                $this->data .= "\$_CONFIG['" . $key . "'] = '" . $val . "'; ";
            }
        }
    }
    
    public function save(){
        $b = @file_put_contents("config.php", $this->data);
        if(!$b) throw new Exception("Error while writing config.php");
        
    }
}

if(file_exists("config.php")){
    
}

$con = new ConfigFile();

?>
