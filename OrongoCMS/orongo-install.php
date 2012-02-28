<?php
session_start();

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

if(!isset($_GET['step'])) $step = 1;
else $step = $_GET['step'];


// Style for the installer :)
$style = 
"
#box{
    border-color:
";

// JS for the installer 
$js = 
"
$(document).ready(function(){
	$('#box').css({top:'50%',left:'50%',margin:'-'+($('#box').height() / 2)+'px 0 0 -'+($('#box').width() / 2)+'px'});
});
";


switch($step){
    case 1:
        ?>
<html>
    <head>
        <title>OrongoInstaller Step 1</title>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <style type="text/css"><?php echo $style; ?></style>
        <script type="text/javascript"><?php echo $js; ?></script>
    </head>
    <body>
        
    </body>
</html>
        <?php
        break;
    default: 
        die("Invalid step number :(");
        break;
}

?>
