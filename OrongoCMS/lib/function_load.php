<?php

/**
 * load function
 *
 * @author Jaco Ruit
 */



/**
 * Loads all the functions,interfaces,classes
 * @param String $paramLibFolder path to lib folder
 */
function load($paramLibFolder){
    define('REVISION', 22);
    //REGISTER NEW FUNCTIONS, INTERFACES, CLASSES, EXCEPTIONS HERE! :D
    $orongo_functions = array('orongo_query', 'handleSessions', 'handlePlugins', 'setCurrentPage');
    $orongo_interfaces = array('IHTMLConvertable', 'IJSConvertable', 'IOrongoPlugin', 'IOrongoStyle', 'IStorable');
    $orongo_classes = array('AjaxLoadCommentsAction', 'AjaxPostCommentAction', 'Article', 'Cache', 'Comment', 'Database', 'HTMLFactory', 'Image','Issue', 'IssueTracker', 'Mail', 'MailFactory', 'MenuBar', 'MessageBox', 'OrongoQuery', 'OrongoQueryHandler', 'Page', 'Plugin', 'Security', 'Session', 'Settings', 'Storage', 'Style', 'User', 'Utils');
    $orongo_exceptions = array('ClassLoadException', 'IllegalArgumentException', 'IllegalMemoryAccessException', 'QueryException');
    $smarty_path = $paramLibFolder . '/Smarty/Smarty.class.php';
    if(!file_exists($smarty_path)) throw new Exception("Couldn't load smarty (" . $smarty_path . " was missing!)");
    require $smarty_path;
    foreach($orongo_interfaces as $interface){
        if(!interface_exists($interface)){ 
            $path = $paramLibFolder . '/I/' . $interface . '.php';
            if(!file_exists($path)) throw new Exception("Couldn't load all interfaces (" . $path . " was missing!)");
            require $path;
        }
    }
    foreach($orongo_exceptions as $exception){
        if(!class_exists($exception)){
            $path =  $paramLibFolder . '/E/' . $exception . '.php';
            if(!file_exists($path)) throw new Exception("Couldn't load all exceptions (" . $path . " was missing!)");
            require $path;
        }
    }
    foreach($orongo_classes as $class){
        if(!class_exists($class)){
            $path =  $paramLibFolder . '/class_' . $class. '.php';
            if(!file_exists($path)) throw new Exception("Couldn't load all classes (" . $path . " was missing!)");
            require $path;
        }
    }
    foreach($orongo_functions as $function){
        if(!function_exists($function)){
            $path =  $paramLibFolder . '/function_' . $function. '.php';
            if(!file_exists($path)) throw new Exception("Couldn't load all functions (" . $path . " was missing!)");
            require $path;
        }
    }
}

?>
