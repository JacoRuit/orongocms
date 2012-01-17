<?php
/**
 * @author Jaco Ruit
 */

//Debug line
//TODO remove on release
$time_start = microtime(true);

require 'globals.php';

setCurrentPage('article');

$article = null;

if(!isset($_GET['id'])){
    header("Location: 404.php");
    exit;
}else{
    try{
        $article = new Article(mysql_escape_string($_GET['id']));
    }catch(Exception $e){
        if($e->getCode() == ARTICLE_NOT_EXIST){
            header("Location: 404.php");
        }else{
            header("Location: 503.php");
        }
    }
}


$user = getUser();

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
    getDisplay()->addMessageBox($msgbox);
}






#   Generate Comment HTML
$comments = $article->getComments();
$commentHTML = "";
if($style->doCommentHTML()){
    try{
       $commentHTML = $style->getCommentsHTML($comments);
    }catch(Exception $e){
        $msgbox = new MessageBox("The style didn't generate the HTML code for the comments, therefore the default generator was used. <br /><br />To hide this message open <br />" . $style->getStylePath() . "info.xml<br /> and set <strong>own_article_html</strong> to <strong>false</strong>.");
        $msgbox->bindException($e);
        getDisplay()->addMessageBox($msgbox);
        foreach($comments as $comment){
            $commentHTML .= $comment->toHTML();
        }
    }
}else{
    foreach($comments as $comment){
        $commentHTML .= $comment->toHTML();
    }
}

$LCID = 0;
if(count($comments) != 0){
    $LCID = $comments[0]->getID();
}

$ajaxPC = new AjaxPostCommentAction($article->getID());
$document_ready .= $ajaxPC->toJS();
$body .= $ajaxPC->toHTML();

$ajaxLC = new AjaxLoadCommentsAction($article->getID(), $LCID, count($comments));
$document_ready .= $ajaxLC->toJS();
$body .= $ajaxLC->toHTML();
#   Template


#       Assigns
   
    #General
    getDisplay()->setTemplateVariable("head_title", $website_name . " - " . $article->getTitle());
    getDisplay()->setTemplateVariable("body", $body);
    
    getDisplay()->setTemplateVariable("document_ready", $document_ready);
    getDisplay()->setTemplateVariable("menu", $menu);

    getDisplay()->setTemplateVariable("article", $article);
    getDisplay()->setTemplateVariable("comments", $commentHTML);
    
    #Plugins
    getDisplay()->setTemplateVariable("plugin_document_ready", $pluginHTML['javascript']['document_ready']);
    getDisplay()->setTemplateVariable("plugin_head", $pluginHTML['html']['head']);
    getDisplay()->setTemplateVariable("plugin_body", $pluginHTML['html']['body']);
    getDisplay()->setTemplateVariable("plugin_footer", $pluginHTML['html']['footer']);
    
#       Handle Style
$style->run();

#       Show
getDisplay()->add("header.orongo");
getDisplay()->add("article.orongo");
getDisplay()->add("footer.orongo");
getDisplay()->render();

//Debug lines
// TODO remove on release
$time_end = microtime(true);
$time = $time_end - $time_start;
echo "<br /><br />Execution time: " . $time;

?>
