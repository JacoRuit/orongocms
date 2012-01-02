<?php
/**
 * @author Jaco Ruit
 */


session_start();


require '../lib/class_Database.php';
require '../lib/class_Settings.php';
require '../lib/class_Session.php';
require '../lib/class_User.php';
include '../lib/class_Plugin.php';
include '../lib/class_Security.php';
include '../lib/class_MailFactory.php';
include '../lib/class_Mail.php';
include '../lib/I/IOrongoPlugin.php';
include '../lib/I/IOrongoStyle.php';
include '../lib/I/IStorable.php';
include '../lib/E/IllegalArgumentException.php';

$db = new Database('../config.php');


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
