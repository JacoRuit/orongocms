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
        $row = getDatabase()->queryFirstRow("SELECT `value` FROM `settings` WHERE `setting` = 'website_url'");
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
        $row = getDatabase()->queryFirstRow("SELECT `value` FROM `settings` WHERE `setting` = 'website_name'");
        $name = $row['value'];
        Cache::store('website_name', $name);
        return $name;
    }
    
    /**
     * Returns the default style as a Style object -> class_Style.php
     * @param String $paramPrefix prefix for folder (starting from themes/)
     * @return Style Style Object
     */
    public static function getStyle(){
        $row = getDatabase()->queryFirstRow("SELECT `value` FROM `settings` WHERE `setting` = 'website_style'");
        $styleFolder = $row['value'];
        return new Style($styleFolder);
    }
    
    /**
     * Sets the style 
     * @param String $paramPrefix prefix for folder (starting from themes/)
     * @param String $paramStyle  path of the style folder
     */
    public static function setStyle($paramStyle){
        try{
            getDatabase()->query('TRUNCATE TABLE `style_data`');
            Style::install($paramStyle);
        }catch(Exception $e){ throw $e; }
        getDatabase()->update("settings", array(
            "value" => $paramStyle
        ), "setting = 'website_style'");
    }
    
    /**
     * Returns the email address of the administratior
     * @return String Email Address
     */
    public static function getEmail(){
        if(Cache::isStored('website_email')) return Cache::get('website_email'); 
        $row = getDatabase()->queryFirstRow("SELECT `value` FROM `settings` WHERE `setting` = 'website_email'");
        $address = $row['value'];
        Cache::store('website_email', $address);
        return $address;
    }
    
    
}

?>
