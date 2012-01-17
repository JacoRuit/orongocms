<?php
/**
 * @author Jaco Ruit
 */

//Debug line
//TODO remove on release
$time_start = microtime(true);

require 'globals.php';

setCurrentPage('index');

#handle orongo-id, orongo-session-id
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



#   Generate HTML of the last 5 articles
$articles = array();
$c = 0;
$q = "action=fetch&object=article&max=5&order=article.id,desc";
try{
    $articles = orongo_query($q);
}catch(Exception $e){
    $msgbox = new MessageBox();
    $msgbox->bindException($e);
    getDisplay()->addMessageBox($msgbox);
}

if((count($articles) < 1)){
   try{
       $article = Article::createArticle("Hello World!");
       $article->setContent("<p>Thank you for installing OrongoCMS!</p><p>To edit this simply delete it and create a new article or change this article.</p><br /><p>The OrongoCMS team</p>");
       $articles[0] = $article;
   }catch(Exception $e){ }
}

$articleHTML = "";
if($style->doArticleHTML()){
    try{
        $articleHTML = $style->getArticlesHTML($articles);
    }catch(Exception $e){
        $msgbox = new MessageBox("The style didn't generate the HTML code for the articles, therefore the default generator was used. <br /><br />To hide this message open <br />" . $style->getStylePath() . "info.xml<br /> and set <strong>own_article_html</strong> to <strong>false</strong>.");
        $msgbox->bindException($e);
        getDisplay()->addMessageBox($msgbox);
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

    getDisplay()->setTemplateVariable("head_title", $website_name . " - Home");
    getDisplay()->setTemplateVariable("body", $body);
    
    getDisplay()->setTemplateVariable("document_ready", $document_ready);;
    getDisplay()->setTemplateVariable("menu", $menu);
    
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
getDisplay()->add("index.orongo");
getDisplay()->add("footer.orongo");

getDisplay()->render();

//Debug lines
// TODO remove on release
$time_end = microtime(true);
$time = $time_end - $time_start;
echo "<br /><br />Execution time: " . $time;

?>
