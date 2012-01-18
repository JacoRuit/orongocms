<?php
/**
 * @author Jaco Ruit
 */

//Debug line
//TODO remove on release
$time_start = microtime(true);

require 'globals.php';

setCurrentPage('archive');


$user = getUser();
$date = false;
$username = false;
$userid = false;
if(isset($_GET['date'])){
    if(preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $_GET['date']))           
            $date = $_GET['date'];
    else{
        getDisplay()->addObject(new MessageBox("Invalid date."));      
    }
}
else if(isset($_GET['user']))
    $username = mysql_escape_string($_GET['user']);
else if(isset($_GET['userid']))
    $userid = mysql_escape_string($_GET['userid']);

$website_name = Settings::getWebsiteName();
$website_url = Settings::getWebsiteURL();
$body = "<script src=\"" . $website_url . "js/widget.prettyAlert.js\" type=\"text/javascript\" charset=\"utf-8\"></script>";
$document_ready = "";

$pages = array();
try{
    $pages = orongo_query('action=fetch&object=page&max=10000&order=page.id');
}catch(Exception $e){
    $msgbox = new MessageBox();
    $msgbox->bindException($e);
    getDisplay()->addObject($msgbox);
}

$menu = HTMLFactory::getMenuCode($pages);
$pluginHTML = null;

try{
    $plugins = getPlugins();
    $pluginHTML = handlePlugins($plugins);
}catch(Exception $e){
    $msgbox = new MessageBox();
    $msgbox->bindException($e);
    getDisplay()->addObject($msgbox);
}



$articles = array();
$c = 0;
$q = "action=fetch&object=article&max=1000000&order=article.id,desc";
if($date != false) $q .= "&where=article.date:" . $date;
if($username != false && is_string($username)) $q .= "&where=author.name:" . $username;
if($userid != false && is_numeric($userid)) $q .= "&where=author.id:" . $userid;
try{
    $articles = orongo_query($q);
}catch(Exception $e){
    $msgbox = new MessageBox("An error occured while fetching articles.");
    $msgbox->bindException($e);
    $errors .= $msgbox->toHTML();
}


$articleHTML = "";
if($style->doArticleHTML()){
    try{
        $articleHTML = $style->getArticlesHTML($articles);
    }catch(Exception $e){
        $msgbox = new MessageBox("The style didn't generate the HTML code for the articles, therefore the default generator was used. <br /><br />To hide this message open <br />" . $style->getStylePath() . "info.xml<br /> and set <strong>own_article_html</strong> to <strong>false</strong>.");
        $msgbox->bindException($e);
        getDisplay()->addObject($msgbox);
        foreach($articles as $article){
            $articleHTML .= $article->toShortHTML();
        }
    }
}else{
    foreach($articles as $article){
        $articleHTML .= $article->toShortHTML();
    }
}

#   Template


#       Assigns
   
    #General
    getDisplay()->setTemplateVariable("head_title", $website_name . " - Archive");
    getDisplay()->setTemplateVariable("body", $body);
    
    getDisplay()->setTemplateVariable("document_ready", $document_ready);

    getDisplay()->setTemplateVariable("articles", $articleHTML);
    
    #Plugins
    getDisplay()->setTemplateVariable("plugin_document_ready", $pluginHTML['javascript']['document_ready']);
    getDisplay()->setTemplateVariable("plugin_head", $pluginHTML['html']['head']);
    getDisplay()->setTemplateVariable("plugin_body", $pluginHTML['html']['body']);
    getDisplay()->setTemplateVariable("plugin_footer", $pluginHTML['html']['footer']);
    
#       Handle Style
$style->run();

#       Show
getDisplay()->add("header.orongo");
getDisplay()->add("archive.orongo");
getDisplay()->add("footer.orongo");

getDisplay()->render();

//Debug lines
// TODO remove on release
$time_end = microtime(true);
$time = $time_end - $time_start;
echo "<br /><br />Execution time: " . $time;

?>
