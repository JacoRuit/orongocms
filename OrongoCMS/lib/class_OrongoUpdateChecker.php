<?php


/**
 * OrongoUpdateChecker Class
 *
 * @author Jaco Ruit
 */
class OrongoUpdateChecker {
    
    /**
     * Fetches the latest version information from orongocms.eu
     * @return array containing info
     */
    public static function getLatestVersionInfo(){
        if(Cache::isStored("versioninfo")) return Cache::get("versioninfo");
        
        $result = file_get_contents("http://www.orongocms.eu/api/getVersionInfo.php");
        $resarray = @json_decode($result);

        if(!is_object($resarray))
            throw new Exception ("Unexpected output from version check server!");
        
        Cache::store("versioninfo", $resarray);
        
        return $resarray;
    }
    
    /**
     * get latest version from orongocms.eu
     * @return int latest version
     */
    public static function getLatestVersion(){
        $info = self::getLatestVersionInfo();
        if(!isset($info->latest_version))
            throw new Exception ("Unexpected output from version check server!");
        return $info->latest_version;
    }
    
    /**
     * checks if an update is available
     * @return boolean indicating if there is an update available
     */
    public static function isUpdateAvailable(){
        return self::getLatestVersion() > REVISION;
    }
    
   
}

?>
