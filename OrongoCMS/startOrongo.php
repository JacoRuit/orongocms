<?php
/**
 * @author Jaco Ruit
 */
/**
 *Starts Orongo! :) 
 */
function startOrongo(){

    session_start();
 
    define("ROOT", dirname(__FILE__));
    define("LIB", ROOT . "/lib");
    define("ADMIN", ROOT . "/orongo-admin");
    define("CONFIG", ROOT . "/config.php");
    define('RANK_ADMIN', 3);
    define('RANK_WRITER', 2);
    define('RANK_USER', 1);
    
    define('ARTICLE_NOT_EXIST', 2100);
    define('PAGE_NOT_EXIST', 3100);
    define('USER_NOT_EXIST', 4100);
    define('COMMENT_NOT_EXIST', 5100);
    
  
    //TODO uncomment on release
    //if(file_exists("orongo-install.php")) die("If you didn't install OrongoCMS yet, proceed to the <a href='orongo-install.php'>installer</a><br/>If you installed it, please delete orongo-install.php");
    if(!file_exists(CONFIG)) die("config.php (" . CONFIG . ") was missing!");
    
    require_once(CONFIG);
    
    require LIB . '/function_load.php';
    
    try{ load(LIB); }catch(Exception $e){ die($e->getMessage()); }
    
    setDatabase(new Database(CONFIG));
   
    try{
        setLanguage(new Language(ADMIN . '/lang/' . Settings::getLanguageName()));
    }catch(Exception $e){
        $msgbox = new MessageBox();
        $msgbox->bindException($e);
        die($msgbox->getImports() . $msgbox->toHTML());
    }
    
    $style = null;
    
    try{
        $style = Settings::getStyle();
    }catch(Exception $e){
        $msgbox = new MessageBox();
        $msgbox->bindException($e);
        die($msgbox->getImports() . $msgbox->toHTML());
    }

    setMenu(new Menu());
    setStyle($style);
    setDisplay(new Display($style->getStylePath()));
    setUser(handleSessions());

    if(defined('HACK_PLUGINS') && HACK_PLUGINS == true)
        Plugin::hackKeys();
    try{
        setPlugins(Plugin::getActivatedPlugins('orongo-admin/'));
    }catch(Exception $e){
        $msgbox = new MessageBox();
        $msgbox->bindException($e);
        getDisplay()->addObject($msgbox);
    }
    
    //getLanguage()->setTempLanguage(ADMIN . '/lang/en_US');

    OrongoDefaultEventHandlers::init();
    
    $p = new OrongoScriptParser(file_get_contents(LIB . "/OrongoScript/Tests/test.osc"));
    $p->startParser();
    
}


?>
