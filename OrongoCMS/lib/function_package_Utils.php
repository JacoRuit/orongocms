<?php

/**
 * Shortcut for Settings::getWebsite() . *STRING*
 * @param String $paramFile string to add before Settings::getWebsite();
 * @return String
 */
function orongoURL($paramFile){
    $website_url = Settings::getWebsiteURL();
    $url = $website_url . $paramFile;
    if(substr($website_url, 0 ,1) == '/'){
        if(substr($paramFile, 0, 1) == '/'){
            $url = $website_url . substr($paramFile, 1);
        }
    }
    return $url;
}

/**
 * Shoftcut for raising events 
 * @param String $paramAction Any action
 * @param array $paramEventArgs Event arguments
 */
function raiseEvent($paramAction, $paramEventArgs){
    OrongoEventManager::raiseEvent(new OrongoEvent($paramAction, $paramEventArgs));
}


?>
