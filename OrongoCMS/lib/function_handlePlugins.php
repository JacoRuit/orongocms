<?php
//TODO delete this is sooo outdated (since Jan 2012)
/**
 * handlePlugins function
 *
 * @author Jaco Ruit
 */

/**
* Puts all the plugin HTML arrays to one array
* @param array $paramPlugins plugin array
* @return array all the plugins' HTML
*/
function handlePlugins($paramPlugins){
    $pluginsHTML = array();
    $pluginsHTML['html']['head'] = "";
    $pluginsHTML['html']['body'] = "";
    $pluginsHTML['html']['footer'] = "";
    $pluginsHTML['javascript']['document_ready'] = "";
    foreach($paramPlugins as $plugin){
        $injectHTMLOnWebPage = $plugin->injectHTMLOnWebPage();
        if(is_bool($injectHTMLOnWebPage) == false) continue; 
        if($injectHTMLOnWebPage){
            $htmlArr = $plugin->getHTML();
            if(is_array($htmlArr)){
                if(isset($htmlArr['javascript']) && is_array($htmlArr['javascript'])){
                    foreach($htmlArr['javascript'] as $key=>$value){
                        switch($key){
                            case 'document_ready':
                                $pluginsHTML['javascript']['document_ready'] .= $value;
                                break;
                            default:
                                break;
                        }
                    }
                }
                if(isset($htmlArr['html']) && is_array($htmlArr['html'])){
                    foreach($htmlArr['html'] as $key=>$value){
                        switch($key){
                            case 'head':
                                $pluginsHTML['html']['head'] .= $value;
                                break;
                            case 'body':
                                $pluginsHTML['html']['body'] .= $value;
                                break;
                            case 'footer':
                                $pluginsHTML['html']['footer'] .= $value;
                                break;
                            default:
                                break;
                        }
                    }
                }
            } else continue;  
        } else continue;
    }
    return $pluginsHTML;
}

?>
