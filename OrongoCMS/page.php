<?php
/**
 * @author Jaco Ruit
 */

require 'globals.php';


if(!isset($_GET['id'])){   
    header('HTTP/1.1 404 Not Found');
    exit;
}else{
    if(!is_numeric($_GET['id'])){
       header('HTTP/1.1 404 Not Found');
       exit;
    }
    $id = Security::escapeSQL($_GET['id']);
    try{
      $page = new Page($id);
    }catch(Exception $e){
        if($e->getCode() == PAGE_NOT_EXIST){
            header('HTTP/1.1 404 Not Found');
            exit;
        }else{
            header('HTTP/1.1 500 Internal Server Error');
            exit;
        }
    }
}



$pluginHTML = "";
$plugins = Plugin::getActivatedPlugins('orongo-admin/');
Plugin::setCurrentPage(PAGE_PAGE);
    
#   Template
    
#       Handle Style
$style->run('', &$smarty);
    
#       Handle Plugins
foreach($plugins as $plugin){
    if($plugin->injectHTMLOnWebPage()){
        //verouderd!
        $pluginHTML .= '<!-- OrongoPlugin -->'; 
        $pluginHTML .= $plugin->getHTML();
    }
}
    
#       Assigns

#       Show
    

?>
