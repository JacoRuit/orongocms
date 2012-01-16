<?php
/**
 * @author Jaco Ruit
 */

//Debug line
//TODO remove on release
$time_start = microtime(true);

require 'globals.php';

setCurrentPage('page');

$page = null;

if(!isset($_GET['id'])){
    header("Location: 404.php");
    exit;
}else{
    try{
        $page = new Page(mysql_escape_string($_GET['id']));
    }catch(Exception $e){
        if($e->getCode() == PAGE_NOT_EXIST){
            header("Location: 404.php");
        }else{
            header("Location: 503.php");
        }
    }
}



#handle orongo-id, orongo-session-id
$user = getUser();


$head = "<meta name=\"generator\" content=\"OrongoCMS r" . REVISION . "\" />";

$errors = "";
$website_name = Settings::getWebsiteName();
$website_url = Settings::getWebsiteURL();
$body = "<script src=\"" . $website_url . "js/widget.prettyAlert.js\" type=\"text/javascript\" charset=\"utf-8\"></script>";
$document_ready = "";
$pages = array();
$pages = @orongo_query('action=fetch&object=page&max=10000&order=page.id');
$menu = HTMLFactory::getMenuCode($pages);
$pluginHTML = null;

try{
    $plugins = getPlugins();
    $pluginHTML = handlePlugins($plugins);
}catch(Exception $e){
    $msgbox = new MessageBox();
    $msgbox->bindException($e);
    $errors.= $msgbox->toHTML();
}



$menu_bar = "";
if($user != null){
    $mb = new MenuBar($user);
    $menu_bar = $mb->toHTML();
}



#   Template


#       Assigns
   
    #General
    $smarty->assign("website_url", $website_url);
    $smarty->assign("website_name", $website_name);
    
    $smarty->assign("head", $head);
    $smarty->assign("head_title", $website_name . " - " . $page->getTitle());
    $smarty->assign("body", $body);
    
    $smarty->assign("document_ready", $document_ready);
    $smarty->assign("menu_bar", $menu_bar);
    $smarty->assign("menu", $menu);
    $smarty->assign("errors", $errors);
    
    if($user != null)
        $smarty->assign("user", $user);
    $smarty->assign("page", $page);
    
    #Plugins
    $smarty->assign("plugin_document_ready", $pluginHTML['javascript']['document_ready']);
    $smarty->assign("plugin_head", $pluginHTML['html']['head']);
    $smarty->assign("plugin_body", $pluginHTML['html']['body']);
    $smarty->assign("plugin_footer", $pluginHTML['html']['footer']);
    
#       Handle Style
$style->run($smarty);

#       Show
$smarty->display("header.orongo");
$smarty->display("page.orongo");
$smarty->display("footer.orongo");

//Debug lines
// TODO remove on release
$time_end = microtime(true);
$time = $time_end - $time_start;
echo "<br /><br />Execution time: " . $time;

?>
