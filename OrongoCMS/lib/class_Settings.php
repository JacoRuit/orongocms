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
        $q = "SELECT `value` FROM `settings` WHERE `setting` = 'url'";
        $result = @mysql_query($q);
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
        return $url;
    }
    
    /**
     * Returns name of the website
     * @return String Website name
     */
    public static function getWebsiteName(){
        $q = "SELECT `value` FROM `settings` WHERE `setting` = 'name'";
        $result = @mysql_query($q);
        $row = mysql_fetch_assoc($result);
        $name = $row['value'];
        mysql_free_result($result);
        return $name;
    }
    
    /**
     * Returns the default style as a Style object -> class_Style.php
     * @param String $paramPrefix prefix for folder
     * @return Style Style Object
     */
    public static function getStyle($paramPrefix){
        $q = "SELECT `value` FROM `settings` WHERE `setting` = 'style'";
        $result = @mysql_query($q);
        $row = mysql_fetch_assoc($result);
        $styleFolder = $row['value'];
        mysql_free_result($result);
        return new Style($paramPrefix, $styleFolder);
    }
    
    /**
     * Returns the email address of the administratior
     * @return String Email Address
     */
    public static function getEmail(){
        $q = "SELECT `value` FROM `settings` WHERE `setting` = 'email'";
        $result = @mysql_query($q);
        $row = mysql_fetch_assoc($result);
        $address = $row['value'];
        mysql_free_result($result);
        return $address;
    }
    
    
}

?>
