<?php
/**
 * Using the great terminal jQuery plugin: http://terminal.jcubic.pl. Also credits for the guy(s) who made it, it rocks!
 * @author Jaco Ruit
 */
require '../startOrongo.php';
startOrongo();

setCurrentPage('admin_terminal');

Security::promptAuth();

$user = getUser();
if($user == null)
    header("Location: ../orongo-login.php");
if($user->getRank() != RANK_ADMIN)
    header("Location: index.php");

$website_name = Settings::getWebsiteName();
$website_url = Settings::getWebsiteURL();

#   Template

$smarty->assign("head_title", $website_name . " - Terminal - Logged in as " . $user->getName());
$smarty->assign("website_url", $website_url);
$smarty->assign("head", '<script src="' . $website_url .'js/jquery.mousewheel-min.js"></script><script src="' . $website_url .'js/jquery.terminal-0.4.6.min.js"></script><link href="' . $website_url .'orongo-admin/style/jquery.terminal.css" rel="stylesheet"/>');

$smarty->display("terminal.orongo");

?>
