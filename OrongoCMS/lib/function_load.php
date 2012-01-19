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
    //REGISTER NEW FUNCTIONS, INTERFACES, (ABSTRACT) CLASSES, EXCEPTIONS HERE! :D
    $orongo_functions = array('orongo_query', 'handleSessions', 'handlePlugins', 'setCurrentPage', 'setDatabase', 'setDisplay', 'setPlugins', 'setStyle', 'setUser');
    $orongo_interfaces = array('IHTMLConvertable', 'IJSConvertable', 'IOrongoPlugin', 'IOrongoStyle', 'IOrongoTerminalPlugin', 'IStorable');
    $orongo_abstracts = array('OrongoDisplayableObject', 'OrongoPluggableObject', 'OrongoFrontendObject');
    $orongo_classes = array('AjaxLoadCommentsAction', 'AjaxPostCommentAction', 'Article', 'Cache', 'Comment', 'Database', 'Display', 'HTMLFactory', 'Image','Issue', 'IssueTracker', 'Mail', 'MailFactory', 'MenuBar', 'MessageBox', 'OrongoEvent', 'OrongoEventManager',  'OrongoQuery', 'OrongoQueryHandler', 'OrongoTerminal', 'Page', 'Plugin', 'Security', 'Session', 'Settings', 'Storage', 'Style', 'User');
    $orongo_exceptions = array('ClassLoadException', 'IllegalArgumentException', 'IllegalMemoryAccessException', 'QueryException');
    $orongo_function_packages = array('Utils');
    $orongo_frontend_objects = array('IndexFrontend', 'ArticleFrontend');
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
    foreach($orongo_abstracts as $abstract){
        if(!class_exists($abstract)){
            $path =  $paramLibFolder . '/class_' . $abstract . '.php';
            if(!file_exists($path)) throw new Exception("Couldn't load all classes (" . $path . " was missing!)");
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
    foreach($orongo_function_packages as $function_package){
        $path =  $paramLibFolder . '/function_package_' . $function_package. '.php';
        if(!file_exists($path)) throw new Exception("Couldn't load all function packages (" . $path . " was missing!)");
        require $path;
    }
    foreach($orongo_frontend_objects as $frontend_object){
        if(!class_exists($frontend_object)){
            $path =  $paramLibFolder . '/FO/frontend_' . $frontend_object . '.php';
            if(!file_exists($path)) throw new Exception("Couldn't load all frontend objects (" . $path . " was missing!)");
            require $path;
        }
    }
    
    OrongoEventManager::init();

}

?>
