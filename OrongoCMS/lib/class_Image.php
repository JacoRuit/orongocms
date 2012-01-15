<?php

/**
 * Image Class
 *
 * @author Ruit
 */
class Image {
    
    //TODO USE FILESYSTEM INSTEAD OF MYSQL.
    /**
     * Stores the image in the database
     * @param String $paramKey Image key, to get the image later
     * @param binary $paramImg Image binary
     * @param int $paramImgSize Image size
     */
    public static function store($paramKey, $paramImg, $paramImgSize){
        $q = "INSERT INTO `images` (`key`,`img`,`size`) VALUES ('" . $paramKey . "', '" . $paramImg . "', '" . $paramImgSize . "')";
        getDatabase()->execQuery($q);
    }
    
    /**
     * Gets the image binary from database
     * @param String $paramKey Image key
     * @return binary $paramImg Image binary
     */
     public static function get($paramKey){
         $q = "SELECT `img` FROM `images` WHERE `key` = '" .$paramKey . "'";
         $result = getDatabase()->execQuery($q);
         $img = mysql_fetch_assoc($result);
         mysql_free_result($result);
         return $img['img'];
     }
}

?>
