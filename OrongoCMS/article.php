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



#handle orongo-id, orongo-session-id
$user = handleSessions();


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
    $plugins = Plugin::getActivatedPlugins('orongo-admin/');
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

#   Generate Article HTML
$articleHTML = "";
if($style->doArticleHTML()){
    try{
        $articleHTML = $style->getArticleHTML($article);
    }catch(Exception $e){
        $msgbox = new MessageBox("The style didn't generate the HTML code for the article, therefore the default generator was used. <br /><br />To hide this message open <br />" . $style->getStylePath() . "info.xml<br /> and set <strong>own_article_html</strong> to <strong>false</strong>.");
        $msgbox->bindException($e);
        $errors .= $msgbox->toHTML();
        $articleHTML = $article->toHTML();
    }
}else{
    $articleHTML = $article->toHTML();
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
        $errors .= $msgbox->toHTML();
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
    $smarty->assign("website_url", $website_url);
    $smarty->assign("website_name", $website_name);
    
    $smarty->assign("head", $head);
    $smarty->assign("head_title", $website_name . " - " . $article->getTitle());
    $smarty->assign("body", $body);
    
    $smarty->assign("document_ready", $document_ready);
    $smarty->assign("menu_bar", $menu_bar);
    $smarty->assign("menu", $menu);
    $smarty->assign("errors", $errors);
    
    $smarty->assign("article", $articleHTML);
    $smarty->assign("comments", $commentHTML);
    
    #Plugins
    $smarty->assign("plugin_document_ready", $pluginHTML['javascript']['document_ready']);
    $smarty->assign("plugin_head", $pluginHTML['html']['head']);
    $smarty->assign("plugin_body", $pluginHTML['html']['body']);
    $smarty->assign("plugin_footer", $pluginHTML['html']['footer']);
    
#       Handle Style
$style->run($smarty);

#       Show
$smarty->display("header.orongo");
$smarty->display("article.orongo");
$smarty->display("footer.orongo");

//Debug lines
// TODO remove on release
$time_end = microtime(true);
$time = $time_end - $time_start;
echo "<br /><br />Execution time: " . $time;

?>
