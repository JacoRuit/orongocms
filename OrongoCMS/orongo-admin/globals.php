<?php
/**
 * @author Jaco Ruit
 */

//FIXME same as ../globals.php

session_start();

require '../lib/function_load.php';
try{ load('../lib'); }catch(Exception $e){ die($e->getMessage()); }

global $_orongo_database;
$_orongo_database = new Database('../config.php');

$smarty = new Smarty();
$smarty->compile_dir = "../smarty/compile"; 
$smarty->cache_dir = "../smarty/cache"; 
$smarty->config_dir = "../smarty/config"; 
$smarty->template_dir = "style/"; 


define('RANK_ADMIN', 3);
define('RANK_WRITER', 2);
define('RANK_USER', 1);

define('ARTICLE_NOT_EXIST', 200);
define('PAGE_NOT_EXIST', 300);
define('USER_NOT_EXIST', 400);

function getDatabase(){
    return $GLOBALS["_orongo_database"];
}

?>
