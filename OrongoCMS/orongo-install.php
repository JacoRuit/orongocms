<?php
session_start();

function generateSalt(){
    $saltChars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVW';
    $string = '';    
    for ($i = 0; $i < 15; $i++) {
        $string .= $saltChars[mt_rand(0, 50)];
    }
    return $string;
}

class ConfigFile{
    
    private $data;
    
    public function __construct(){
        $this->data = "";
    }
    
    
    public function writeConfigArray($paramArray){
        $this->data .= "<?php   " ;
        $this->data .= "/** OrongoCMS Configuration File written on " . time() . " by the ConfigFile class **/ ";
        $this->data .= "global \$_CONFIG; ";
        foreach($paramArray as $key => $val){
            if(is_array($val)){
                $arrayName = $key;
                $this->data .= "\$_CONFIG['" . $key . "'] = array(); ";
                foreach($val as $key2=>$val2){
                    $this->data .= "\$_CONFIG['" . $arrayName . "']['" . $key2 . "'] = '" . $val2 . "'; ";
                }
            }else{
                $this->data .= "\$_CONFIG['" . $key . "'] = '" . $val . "'; ";
            }
        }
        $this->data .= "  ?>";
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
html{
    font-family: Sans-serif, Arial;
}
body{ 
    background: #FFFFFF;
    
    background: -moz-linear-gradient(top, #FFFFFF, #C0C0C0);

    background: -webkit-gradient(linear,
                left top, left bottom, from(#FFFFFF), to(#C0C0C0));
 
    filter: progid:DXImageTransform.Microsoft.Gradient(
                StartColorStr='#C0C0C0', EndColorStr='#000000', GradientType=0);
}
#box{
    border-color: #E54C0B;
    box-shadow: 0px 0px 50px #E54C0B, inset 0px 0px 20px #C0C0C0;
    -mox-box-shadow: 0px 0px 50px #E54C0B, inset 0px 0px 20px #C0C0C0;
    border-width:1px;
    border-style:solid;
    -moz-border-radius:25px;
    border-radius:25px;
    width:450px; 
    position:relative;
    height:570px; 
    top:300px;
    padding-left: 20px;
    padding-right:20px;
    padding-bottom:15px;
    background-color:white;
    text-align:center;
}
a{
    color:#E54C0B;
    text-decoration:none;
}
#logo{
    padding-top:15px;
    text-align:center;
    padding-bottom:30px;
}
#previous{
    padding-top:40px;
}
#next{
    color:#E54C0B;
    padding-top:40px;
}
fieldset{
    border:none;
}
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
        if(isset($_SESSION['mysql_creds'])) unset($_SESSION['mysql_creds']);
        ?>
<html>
    <head>
        <title>OrongoInstaller Step 1</title>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <style type="text/css"><?php echo $style; ?></style>
        <script type="text/javascript"><?php echo $js; ?></script>
    </head>
    <body>
        <div id="box">
            <div id="logo">
                <img src="orongo-admin/theme/logo.png" alt="OrongoCMS" />
            </div>
            <p>Thank you for choosing OrongoCMS!</p>
            <p>You are going to install RC1 of OrongoCMS (r89) using a beta version of the installer.</p>
            <br />
            <p>In order to install OrongoCMS, please follow the steps.</p>
            <br />
            <p>Before you proceed, make sure you're running PHP 5.3+ and your webserver has the mysqli library. (view <a href="orongo-install.php?step=php_info"> your PHP info</a>)</p>
            <div id="next"><a href="orongo-install.php?step=2">Next</a></div>
        </div>
    </body>
</html>
        <?php
        break;
    
    
    
    
    case 2:
        if(isset($_SESSION['mysql_creds'])) unset($_SESSION['mysql_creds']);
        $extra = file_exists("config.php") ? "" : "<p>Please create config.php</p>";
        ?>
<html>
    <head>
        <title>OrongoInstaller Step 2</title>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <style type="text/css"><?php echo $style; ?></style>
        <script type="text/javascript"><?php echo $js; ?></script>
    </head>
    <body>
        <div id="box">
            <div id="logo">
                <img src="orongo-admin/theme/logo.png" alt="OrongoCMS" />
            </div>
            <p>Please <strong>CHMOD</strong> tpl/tmp to <strong>777</strong></p>
            <p>Please <strong>CHMOD</strong> orongo-media/files to <strong>777</strong></p>
            <p>Please <strong>CHMOD</strong> orongo-media/flash to <strong>777</strong></p>
            <p>Please <strong>CHMOD</strong> orongo-media/images to <strong>777</strong></p>
            <p>Please <strong>CHMOD</strong> orongo-media/thumbs to <strong>777</strong></p>
            <p>Please <strong>CHMOD</strong> config.php to <strong>777</strong></p>
            <?php echo $extra; ?>
            <div id="previous"><a href="orongo-install.php?step=1">Previous</a></div>
            <div id="next"><a href="orongo-install.php?step=3">Next</a></div>
        </div>
    </body>
</html>
        <?php
        break;
    
    
    
    
    
    case 3:
        if(isset($_SESSION['mysql_creds'])) unset($_SESSION['mysql_creds']);
        $msg = "";
        if(isset($_GET['msg'])){
            switch($_GET['msg']){
                case 0:
                    $msg = "Invalid MySQL Credentials.";
                    break;
                default:
                    break;
            }
        }
        ?>
<html>
    <head>
        <title>OrongoInstaller Step 3</title>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <style type="text/css"><?php echo $style; ?></style>
        <script type="text/javascript"><?php echo $js; ?></script>
    </head>
    <body>
        <div id="box">
            <div id="logo">
                <img src="orongo-admin/theme/logo.png" alt="OrongoCMS" />
            </div>
            <?php echo $msg; ?>
            <p>Please fill in your MySQL credentials</p>
            <form action="orongo-install.php?step=4" method="post">
                <fieldset>
                    <label>MySQL Host</label><br/>
                    <input type="text" name="mysql_host" value="localhost" />
                </fieldset>
                <fieldset>
                    <label>MySQL Username</label><br/>
                    <input type="text" name="mysql_username" />
                </fieldset>
                <fieldset>
                    <label>MySQL Password</label><br/>
                    <input type="password" name="mysql_password" />
                </fieldset>
                <fieldset>
                    <label>MySQL Database</label><br/>
                    <input type="text" name="mysql_database" />
                </fieldset>
                <input type="submit" value="Validate" />
            </form>
            <div id="previous"><a href="orongo-install.php?step=2">Previous</a></div>
        </div>
    </body>
</html>
        <?php
        break;
    
    
    
    
    
    case 4:
        if(isset($_SESSION['mysql_creds'])) unset($_SESSION['mysql_creds']);
        if(!isset($_POST['mysql_host']) || !isset($_POST['mysql_username']) || !isset($_POST['mysql_password']) || !isset($_POST['mysql_database'])){
            header("Location: orongo-install.php?step=3");
            exit;
        }
        $goodConnection = @mysql_connect($_POST['mysql_host'], $_POST['mysql_username'], $_POST['mysql_password']);
        if(!$goodConnection){ header("Location: orongo-install.php?step=3&msg=0"); exit; }
        $goodDatabase = @mysql_select_db($_POST['mysql_database']);
        if(!$goodDatabase){ header("Location: orongo-install.php?step=3&msg=0"); exit; }
        $_SESSION['mysql_creds'] = array(
            'host' => $_POST['mysql_host'],
            'username' => $_POST['mysql_username'],
            'password' => $_POST['mysql_password'],
            'database' => $_POST['mysql_database']
        );
        ?>
<html>
    <head>
        <title>OrongoInstaller Step 4</title>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <style type="text/css"><?php echo $style; ?></style>
        <script type="text/javascript"><?php echo $js; ?></script>
    </head>
    <body>
        <div id="box">
            <div id="logo">
                <img src="orongo-admin/theme/logo.png" alt="OrongoCMS" />
            </div>
            <p>Please fill in the info of your new website</p>
            <form action="orongo-install.php?step=5" method="post">
                <fieldset>
                    <label>Website Name</label><br/>
                    <input type="text" name="website_name" />
                </fieldset>
                <fieldset>
                    <label>Website URL</label><br/>
                    <input type="text" name="website_url" />
                </fieldset>
                <fieldset>
                    <label>Administrator Email Address</label><br/>
                    <input type="email" name="admin_email" />
                </fieldset>
                <fieldset>
                    <label>Admin Username</label><br />
                    <input type="text" name="username"/>
                </fieldset>
                <input type="submit" value="Validate" />
            </form>
            <div id="previous"><a href="orongo-install.php?step=3">Previous</a></div>
        </div>
    </body>
</html>
        <?php
        break;
        
        
        
   case 5:
       if(!isset($_SESSION['mysql_creds'])){ header("Location: orongo-install.php?step=3"); exit; }
       if(!isset($_POST['website_name']) || !isset($_POST['website_url']) || !isset($_POST['admin_email']) && !isset($_POST['username'])){
           header("Location: orongo-install.php?step=4");
           exit;
       }
       $meekro = "lib/meekrodb.2.0.class.php";
       if(!file_exists($meekro)) die("MeekroDB (" . $meekro . ") was missing in the lib folder :(");
       require $meekro;
       $queries = array();
       $queries[0] = "
CREATE TABLE IF NOT EXISTS `activated_plugins` (
  `plugin_xml_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
";

$queries[1] ="
CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `content` mediumtext NOT NULL,
  `tags` text NOT NULL,
  `authorID` int(11) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;
";

$queries[2] ="
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `articleID` int(10) unsigned NOT NULL,
  `authorID` int(10) unsigned NOT NULL,
  `timestamp` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;
";

$queries[3] ="
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `text` text NOT NULL,
  `image` text,
  `time` int(11) NOT NULL,
  `userID` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=65 ;
";

$queries[4] ="
CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` mediumtext NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
";

$queries[5] ="
CREATE TABLE IF NOT EXISTS `plugin_data` (
  `access_key` text NOT NULL,
  `setting` text NOT NULL,
  `setting_type` varchar(255) NOT NULL,
  `setting_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
";

$queries[6] ="
CREATE TABLE IF NOT EXISTS `sessions` (
  `userID` int(11) NOT NULL,
  `sessionID` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
";

$queries[7] ="
CREATE TABLE IF NOT EXISTS `settings` (
  `setting` tinytext NOT NULL,
  `value` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
";

$queries[8] ="
CREATE TABLE IF NOT EXISTS `storage` (
  `key` text NOT NULL,
  `var` text NOT NULL,
  `is_object` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
";

$queries[9] ="
CREATE TABLE IF NOT EXISTS `style_data` (
  `style_main_class` text NOT NULL,
  `setting` text NOT NULL,
  `setting_type` varchar(255) NOT NULL,
  `setting_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
";

$queries[10] ="
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `password` text NOT NULL,
  `email` varchar(80) NOT NULL,
  `rank` tinyint(1) NOT NULL,
  `activated` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;
";

$queries[11] ="
CREATE TABLE IF NOT EXISTS `user_activations` (
  `userID` int(11) NOT NULL,
  `code` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
";
       $creds = $_SESSION['mysql_creds'];
       $db = new MeekroDB($creds['host'], $creds['username'], $creds['password'], $creds['database']);
       foreach($queries as $query){
           $db->query($query);
       }
       unset($_SESSION['mysql_creds']);
       $db->insert("settings", array(
           "setting" => "website_url",
           "value" => $_POST['website_url']
       ));
       $db->insert("settings", array(
           "setting" => "website_name",
           "value" => $_POST['website_name']
       ));
       $db->insert("settings", array(
           "setting" => "website_style",
           "value" => "monk"
       ));
       $db->insert("settings", array(
           "setting" => "website_email",
           "value" => $_POST['admin_email']
       ));
       $db->insert("settings", array(
           "setting" => "website_lang",
           "value" => "en_US"
       ));
       $db->insert("settings", array(
           "setting" => "show_archive",
           "value" => "true"
       ));
       $conf = new ConfigFile();
       $config = array();
       $config['db'] = array();
       $config['security'] = array();
       $config['db']['name'] = $creds['database'];
       $config['db']['username'] = $creds['username'];
       $config['db']['password'] = $creds['password'];
       $config['db']['server'] = $creds['host'];
       $config['security']['salt_1'] = generateSalt();
       $config['security']['salt_2'] = generateSalt();
       $config['security']['salt_3'] = generateSalt();
       $conf->writeConfigArray($config);
       $conf->save();
       $adminPW = generateSalt();
       $hash = sha1($config['security']['salt_2']. $adminPW . $config['security']['salt_3'] . $config['security']['salt_1']);
       $db->insert("users", array(
            "id" => 1,
            "name" => $_POST['username'],
            "email" => $_POST['admin_email'],
            "password" => $hash,
            "rank" => 3,
            "activated" => 1
       ));
       ?>
<html>
    <head>
        <title>OrongoInstaller Finished</title>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <style type="text/css"><?php echo $style; ?></style>
        <script type="text/javascript"><?php echo $js; ?></script>
    </head>
    <body>
        <div id="box">
            <div id="logo">
                <img src="orongo-admin/theme/logo.png" alt="OrongoCMS" />
            </div>
            <p>OrongoCMS has been installed succesfully!</p>
            <p>Your password is <strong style="font-size:30px;"><?php echo $adminPW; ?></strong></p>
            <p>Save this password carefully, you may change it later in the admin panel</p>
            <p>Please delete orongo-install.php & <strong>CHMOD</strong> config.php to <strong>644</strong></p>
            <br/>
            <p>You may proceed to the <a href="orongo-admin/">login</a></p>
            <p>Please help the development of OrongoCMS by <a href="orongo-admin/post-issue.php" target="_blank">posting bugs</a></p>
        </div>
    </body>
</html>
       <?php
       break;
       
       
       
       
   case "php_info":
        die(phpinfo());
       break;
        
        
    default: 
        die("Invalid step number :(");
        break;
}

?>
