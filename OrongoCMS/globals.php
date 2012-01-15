<?php
/**
 * @author Jaco Ruit
 */

session_start();
require 'lib/function_load.php';
try{ load('lib'); }catch(Exception $e){ die($e->getMessage()); }

global $_orongo_database;
$_orongo_database = new Database('config.php');
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

define('ARTICLE_NOT_EXIST', 200);
define('PAGE_NOT_EXIST', 300);
define('USER_NOT_EXIST', 400);
define('COMMENT_NOT_EXIST', 500);

function getDatabase(){
    return $GLOBALS["_orongo_database"];
}

?>
