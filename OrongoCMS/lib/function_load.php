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
    define('REVISION', 61);
    //REGISTER NEW FUNCTIONS, INTERFACES, (ABSTRACT) CLASSES, EXCEPTIONS HERE! :D
    $orongo_functions = array('orongo_query', 'handleSessions', 'l' , 'setCurrentPage', 'setDatabase', 'setDisplay', 'setMenu', 'setPlugins', 'setStyle', 'setUser');
    $orongo_interfaces = array('IHTMLConvertable', 'IJSConvertable', 'IOrongoPlugin', 'IOrongoStyle', 'IOrongoTerminalPlugin', 'IStorable');
    $orongo_abstracts = array('OrongoDisplayableObject', 'OrongoPluggableObject', 'OrongoFrontendObject');
    $orongo_classes = array('AjaxLoadCommentsAction', 'AjaxPostCommentAction', 'Article', 'Cache', 'Comment', 'Database', 'Display', 'Image','Issue', 'IssueTracker', 'Mail', 'MailFactory', 'Menu', 'MenuBar', 'MessageBox', 'OrongoEvent', 'OrongoEventManager',  'OrongoQuery', 'OrongoQueryHandler', 'OrongoTerminal', 'Page', 'Plugin', 'Security', 'Session', 'Settings', 'Storage', 'Style', 'User');
    $orongo_exceptions = array('ClassLoadException', 'IllegalArgumentException', 'IllegalMemoryAccessException', 'OrongoScriptParseException', 'QueryException');
    $orongo_function_packages = array('Utils');
    $orongo_frontend_objects = array('AdminFrontend', 'ArchiveFrontend', 'PageFrontend', 'IndexFrontend', 'ArticleFrontend');
    $orongo_script_core = array('OrongoFunction', 'OrongoPackage', 'OrongoIfStatement', 'OrongoScriptParser', 'OrongoScriptRuntime', 'OrongoVariable');
    $raintpl_path = $paramLibFolder . '/rain.tpl.class.php';
    if(!file_exists($raintpl_path)) throw new Exception("Couldn't load RainTPL (" . $raintpl_path . " was missing!)");
    require $raintpl_path;
    $meekro_path = $paramLibFolder . '/meekrodb.2.0.class.php';
    if(!file_exists($meekro_path)) throw new Exception("Could't load MeekroDB (" . $meekro_path . " was missing!)");
    require $meekro_path;
    $ckeditor_path = $paramLibFolder . "/ckeditor/ckeditor_php5.php";
    if(!file_exists($ckeditor_path)) throw new Exception("Could't load CKEditor (" . $ckeditor_path . " was missing!)");
    require $ckeditor_path;
    $ckfinder_path = $paramLibFolder . "/ckfinder/core/ckfinder_php5.php";
    if(!file_exists($ckfinder_path)) throw new Exception("Could't load CKFinder (" . $ckfinder_path . " was missing!)");
    require $ckfinder_path;
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
    foreach($orongo_script_core as $os_core){
        if(!class_exists($os_core)){
            $path =  $paramLibFolder . '/OrongoScript/orongocore_' . $os_core . '.php';
            if(!file_exists($path)) throw new Exception("Couldn't load the OrongoScript core objects (" . $path . " was missing!)");
            require $path;
        }
    }
    OrongoEventManager::init();

}

?>
