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
        if(!is_numeric(strpos($url, "http://"))){
            $url = 'http://' . $url;
        }
        #/ suffix
        if(substr($url, -1) != '/'){
            $url .= '/';
        }
        Cache::store('website_url', $url);
        return $url;
    }
    
    /**
     * Sets the website URL
     * @param $paramURL String new website URL
     */
    public static function setWebsiteURL($paramURL){
        getDatabase()->update("settings",array(
            "value" => $paramURL
        ), "setting = 'website_url'");
        Cache::store('website_url', $paramURL);
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
     * Sets the website name
     * @param $paramName String new website name 
     */
    public static function setWebsiteName($paramName){
        getDatabase()->update("settings",array(
            "value" => $paramName
        ), "setting = 'website_name'");
        Cache::store('website_name', $paramName);
    }
    
    /**
     * Returns the default style as a Style object -> class_Style.php
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
    
    /**
     * Returns the language name
     * @return String language name
     */
    public static function getLanguageName(){
       if(Cache::isStored('website_lang')) return Cache::get('website_lang'); 
       $row = getDatabase()->queryFirstRow("SELECT `value` FROM `settings` WHERE `setting` = 'website_lang'");
       Cache::store('website_lang', $row['value']);
       return $row['value'];
    }
    
    /**
     * Sets the language name
     * @param $paramLanguageName String language name 
     */
    public static function setLanguageName($paramLanguageName){
        getDatabase()->update("settings",array(
            "value" => $paramLanguageName
        ), "setting = 'website_lang'");
        Cache::store('website_lang', $paramLanguageName);
    }
    
    /**
     * Checks if archive should be shown in Menu
     * @return boolean indicating if it should 
     */
    public static function showArchive(){
        if(Cache::isStored('show_archive')) return Cache::get('show_archive');
        $row = getDatabase()->queryFirstRow("SELECT `value` FROM `settings` WHERE `setting` = 'show_archive'");
        $show = $row['value'] == "false" ? false : true;
        Cache::store('show_archive', $show);
        return $show;
    }
    
    /**
     * Sets the show archive bool
     * @param $paramBool indicating if archive should be shown in Menu 
     */
    public static function setShowArchive($paramBool){
        getDatabase()->update("settings",array(
            "value" => $paramBool
        ), "setting = 'show_archive'");
        Cache::store('show_archive', $paramBool);
    }
}

?>
