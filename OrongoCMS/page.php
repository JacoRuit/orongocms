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

$website_name = Settings::getWebsiteName();
$website_url = Settings::getWebsiteURL();
$body = "<script src=\"" . $website_url . "js/widget.prettyAlert.js\" type=\"text/javascript\" charset=\"utf-8\"></script>";
$document_ready = "";
$pluginHTML = null;

try{
    $plugins = getPlugins();
    $pluginHTML = handlePlugins($plugins);
}catch(Exception $e){
    $msgbox = new MessageBox();
    $msgbox->bindException($e);
    getDisplay()->addObject($msgbox);
}



#   Template


#       Assigns
   
    #General   
    getDisplay()->setTemplateVariable("head_title", $website_name . " - " . $page->getTitle());
    getDisplay()->setTemplateVariable("body", $body);
    
    getDisplay()->setTemplateVariable("document_ready", $document_ready);
    
    getDisplay()->setTemplateVariable("page", $page);

    #Plugins
    getDisplay()->setTemplateVariable("plugin_document_ready", $pluginHTML['javascript']['document_ready']);
    getDisplay()->setTemplateVariable("plugin_head", $pluginHTML['html']['head']);
    getDisplay()->setTemplateVariable("plugin_body", $pluginHTML['html']['body']);
    getDisplay()->setTemplateVariable("plugin_footer", $pluginHTML['html']['footer']);
    
#       Handle Style
$style->run();

#       Show
getDisplay()->add("header.orongo");
getDisplay()->add("page.orongo");
getDisplay()->add("footer.orongo");

getDisplay()->render();

//Debug lines
// TODO remove on release
$time_end = microtime(true);
$time = $time_end - $time_start;
echo "<br /><br />Execution time: " . $time;

?>
