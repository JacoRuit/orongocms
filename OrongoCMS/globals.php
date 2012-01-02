<?php
/**
 * @author Jaco Ruit
 */

session_start();

require 'lib/class_Database.php';
require 'lib/Smarty/Smarty.class.php';
include 'lib/I/IOrongoPlugin.php';
include 'lib/I/IOrongoStyle.php';
include 'lib/I/IStorable.php';
include 'lib/I/IHTMLConvertable.php';
include 'lib/E/IllegalArgumentException.php';
include 'lib/E/IllegalMemoryAccessException.php';
include 'lib/E/ClassLoadException.php';
require 'lib/class_MessageBox.php';
require 'lib/class_Settings.php';
require 'lib/class_Storage.php';
require 'lib/class_Style.php';
require 'lib/class_Image.php';
require 'lib/class_Session.php';
require 'lib/function_HandleSessions.php';
require 'lib/class_User.php';
require 'lib/class_HTMLFactory.php';
include 'lib/class_Plugin.php';
include 'lib/class_Article.php';
include 'lib/class_Page.php';
include 'lib/class_Security.php';
include 'lib/class_MailFactory.php';
include 'lib/class_Mail.php';
include 'lib/function_HandlePlugins.php';


$db = new Database('config.php');
$smarty = new Smarty();

try{
    $style = Settings::getStyle('');
}catch(Exception $e){
    $msgbox = new MessageBox();
    $msgbox->bindException($e);
    die($msgbox->getImports() . $msgbox->toHTML());
}

$smarty->template_dir = "themes/" . $style->getStyleFolder(); 
$smarty->compile_dir = "smarty/compile"; 
$smarty->cache_dir = "smarty/cache"; 
$smarty->config_dir = "smarty/config"; 

define('RANK_ADMIN', 3);
define('RANK_WRITER', 2);
define('RANK_USER', 1);
define('VERSION_NUMBER', 0.1);

define('ARTICLE_NOT_EXIST', 200);
define('PAGE_NOT_EXIST', 300);
define('USER_NOT_EXIST', 400);

define('PAGE_PAGE', 600);
define('PAGE_INDEX', 700);
define('PAGE_ARTICLE', 800);

?>
