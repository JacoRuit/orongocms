<?php
/**
 * @author Jaco Ruit
 */
/**
 *Starts Orongo! :) 
 */
function startOrongo(){
    //DEBUG LINE
    //TODO change on release
    
    error_reporting(E_ALL);
    //error_reporting
    session_start();
    
    define("ROOT", dirname(__FILE__));
    define("LIB", ROOT . "/lib");
    define("ADMIN", ROOT . "/orongo-admin");
    define("CONFIG", ROOT . "/config.php");
    
    require_once(CONFIG);
    
    require LIB . '/function_load.php';
    
    try{ load(LIB); }catch(Exception $e){ die($e->getMessage()); }

    setDatabase(new Database(CONFIG));
   

    try{
        $style = Settings::getStyle();
    }catch(Exception $e){
        $msgbox = new MessageBox();
        $msgbox->bindException($e);
        die($msgbox->getImports() . $msgbox->toHTML());
    }


    setStyle($style);
    setDisplay(new Display($style->getStylePath()));
    setUser(handleSessions());

    try{
        setPlugins(Plugin::getActivatedPlugins('orongo-admin/'));
    }catch(Exception $e){
        $msgbox = new MessageBox();
        $msgbox->bindException($e);
        die($msgbox->getImports() . $msgbox->toHTML());
    }



    define('RANK_ADMIN', 3);
    define('RANK_WRITER', 2);
    define('RANK_USER', 1);

    define('ARTICLE_NOT_EXIST', 200);
    define('PAGE_NOT_EXIST', 300);
    define('USER_NOT_EXIST', 400);
    define('COMMENT_NOT_EXIST', 500);
}


?>
