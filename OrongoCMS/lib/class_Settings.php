<?php

/**
 * Settings Class
 *
 * @author Jaco Ruit
 */
class Settings {
    
    /**
     * Returns URL of the website (root of CMS installation) with / suffix & http:// prefix
     * @return String Website URL
     */
    public static function getWebsiteURL(){
        if(Cache::isStored('website_url'))return Cache::get('website_url'); 
        $q = "SELECT `value` FROM `settings` WHERE `setting` = 'url'";
        $result = getDatabase()->execQuery($q);
        $row = mysql_fetch_assoc($result);
        $url = $row['value'];
        #http:// prefix
       // if(strpos($url, "http://")==false){
       //     $url = 'http://' . $url;
       // }
        #/ suffix
        if(substr($url, -1) != '/'){
            $url .= '/';
        }
        Cache::store('website_url', $url);
        return $url;
    }
    
    /**
     * Returns name of the website
     * @return String Website name
     */
    public static function getWebsiteName(){
        if(Cache::isStored('website_name'))return Cache::get('website_name'); 
        $q = "SELECT `value` FROM `settings` WHERE `setting` = 'name'";
        $result = getDatabase()->execQuery($q);
        $row = mysql_fetch_assoc($result);
        $name = $row['value'];
        mysql_free_result($result);
        Cache::store('website_name', $name);
        return $name;
    }
    
    /**
     * Returns the default style as a Style object -> class_Style.php
     * @param String $paramPrefix prefix for folder (starting from themes/)
     * @return Style Style Object
     */
    public static function getStyle($paramPrefix){
        $q = "SELECT `value` FROM `settings` WHERE `setting` = 'style'";
        $result = getDatabase()->execQuery($q);
        $row = mysql_fetch_assoc($result);
        $styleFolder = $row['value'];
        mysql_free_result($result);
        return new Style($paramPrefix, $styleFolder);
    }
    
    /**
     * Sets the style 
     * @param String $paramPrefix prefix for folder (starting from themes/)
     * @param String $paramStyle  name of the style folder
     */
    public static function setStyle($paramPrefix, $paramStyle){
        try{
            $q = 'TRUNCATE TABLE `styles`'; 
            getDatabase()->execQuery($q);
            Style::install($paramPrefix, $paramStyle);
        }catch(Exception $e){ throw $e; }
        $q = "UPDATE `settings` SET `value`='" . $paramStyle . "' WHERE `setting` = 'style'";
        getDatabase()->execQuery($q);
    }
    
    /**
     * Returns the email address of the administratior
     * @return String Email Address
     */
    public static function getEmail(){
        if(Cache::isStored('website_email')) return Cache::get('website_email'); 
        $q = "SELECT `value` FROM `settings` WHERE `setting` = 'email'";
        $result = getDatabase()->execQuery($q);
        $row = mysql_fetch_assoc($result);
        $address = $row['value'];
        mysql_free_result($result);
        Cache::store('website_email', $address);
        return $address;
    }
    
    
}

?>
