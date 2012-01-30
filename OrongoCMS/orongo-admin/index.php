<?php
/**
 * @author Jaco Ruit
 */

require '../startOrongo.php';
startOrongo();


setCurrentPage('admin_index');

Security::promptAuth();


$user = getUser();
 

$website_name = Settings::getWebsiteName();

#   content_block_1
$content_block_1 = "<h2>Stats</h2>";
$uCount = User::getUserCount();
$content_block_1 .= "<p>" . $uCount . " registered and activated user";
if($uCount > 1 || $uCount == 0) $content_block_1 .= "s</p>"; else $content_block_1 .= "</p>";
$pCount = Page::getPageCount();
$content_block_1 .= "<p>" . $pCount .= " published page";
if($pCount > 1 || $pCount == 0) $content_block_1 .= "s</p>"; else $content_block_1 .= "</p>";
$sCount = Storage::getStorageCount();
$content_block_1 .= "<p>" . $sCount . " item";
if($sCount > 1 || $sCount == 0) $content_block_1 .= "s"; 
$content_block_1 .= " stored in storage</p>";
$plCount = Plugin::getPluginCount();
$content_block_1 .= "<p>" . $plCount .= " activated plugin";
if($plCount > 1 || $plCount == 0) $content_block_1 .= "s</p>"; else $content_block_1 .= "</p>";


#   Template

getDisplay()->setTemplateVariable("head_title", $website_name . " - Administration - Logged in as " . $user->getName());
getDisplay()->setTemplateVariable("style", "style.interface");
getDisplay()->setTemplateVariable("document_ready", '');

getDisplay()->setTemplateVariable("content_block_1", $content_block_1);

getDisplay()->add("header.orongo");
getDisplay()->setTemplateVariable("inner", "inner_index");
getDisplay()->add("interface.orongo");
getDisplay()->add("footer.orongo");

getDisplay()->render();
?>
