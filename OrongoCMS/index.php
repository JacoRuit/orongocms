<?php
/**
 * @author Jaco Ruit
 */

require 'globals.php';

#handle orongo-id, orongo-session-id
$user = handleSessions();


$head = "";
$website_name = Settings::getWebsiteName();
$website_url = Settings::getWebsiteURL();
$document_ready = "";
$pages = Page::getPages();
$menu = HTMLFactory::getMenuCode($pages);
$plugins = Plugin::getActivatedPlugins('orongo-admin/');
Plugin::setCurrentPage(PAGE_INDEX);

$menu_bar = "";
if($user != null){
    $menu_bar = '<script src="'. $website_url . 'js/interface.menu_effects.js"  type="text/javascript" charset="utf-8"></script>';
    $menu_bar .= '<link rel="stylesheet" href="'. $website_url . 'orongo-admin/style/style.menu.css" type="text/css"/>';
    $menu_bar .= '<div class="menu fixed hide"><div class="seperator right hide" style="padding-right: 100px"></div><div class="menu_text right hide">Settings</div><div class="icon_settings_small right hide"></div><div class="seperator right hide"></div><div class="menu_text right hide">Notifications</div><div class="icon_messages_small right hide"></div><div class="seperator right hide"></div><div class="menu_text right hide">Pages</div><div class="icon_pages_small right hide"></div><div class="seperator right hide"></div><div class="menu_text left hide" style="padding-left: 200px"><div class="icon_account_small left"></div> Logged in as ' . $user->getName() . ' | <a href="'. $website_url . 'orongo-logout.php">Logout</a></div></div>';
}

#   Generate HTML of the last 5 articles
$articles = array();
$lastID = Article::getLastArticleID();
$c = 0;
if($lastID > 0){
    for($i = 0; $i <= $lastID; $i++){
        try{
            //if 5 articles are put in the array, exit loop
            if($c >= 5) break;
            $articles[$i] = new Article($i);
            $c++;
        }catch(Exception $e){}
    }
}else{
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
    

    
#       Handle Plugins
$pluginHTML = handlePlugins($plugins);

#       Assigns
   
    #General
    $smarty->assign("website_url", $website_url);
    
    $smarty->assign("head", $head);
    $smarty->assign("head_title", $website_name .= " - Home");
    
    $smarty->assign("document_ready", $document_ready);
    $smarty->assign("menu_bar", $menu_bar);
    $smarty->assign("menu", $menu);
    
    $smarty->assign("articles", $articleHTML);
    
    #Plugins
    $smarty->assign("plugin_document_ready", $pluginHTML['javascript']['document_ready']);
    $smarty->assign("plugin_head", $pluginHTML['html']['head']);
    $smarty->assign("plugin_body", $pluginHTML['html']['body']);
        
#       Handle Style
$style->run($smarty);

#       Show
$smarty->display("header.orongo");
$smarty->display("index.orongo");

?>
