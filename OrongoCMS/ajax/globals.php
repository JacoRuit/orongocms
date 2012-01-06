<?php
/**
 * @author Jaco Ruit
 */


session_start();

require '../lib/function_load.php';
try{ load('../lib'); }catch(Exception $e){ die($e->getMessage()); }

$db = new Database('../config.php');


define('RANK_ADMIN', 3);
define('RANK_WRITER', 2);
define('RANK_USER', 1);

define('ARTICLE_NOT_EXIST', 200);
define('PAGE_NOT_EXIST', 300);
define('USER_NOT_EXIST', 400);


?>
