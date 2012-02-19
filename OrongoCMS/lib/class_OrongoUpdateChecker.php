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
        $xml = "<?xml version='1.0' encoding='UTF-8'?>";
        $xml .= "<versioncheck>";
        $xml .= "   <orongocms_version>" . $paramOrongoVersion . "</orongocms_version>";
        $xml .= "</versioncheck>";
        
        $req = curl_init("http://www.orongocms.eu/checkversion.php");
        curl_setopt($req, CURLOPT_POST, true);
        curl_setopt($req, CURLOPT_HEADER, true);
        curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($req, CURLOPT_POSTFIELDS , $xml);

        $result = curl_exec($req);
        curl_close($req);
        
        if(!is_string($result) || is_bool($result))
            throw new Exception ("Unexpected output from version check server!");
        
        $resxml = simplexml_load_string($result);
        $resjson = json_encode($resxml);
        $resarray = json_decode($resjson);
        if(!is_array($resarray))
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
        if(!isset($info["latest_version"]))
            throw new Exception ("Unexpected output from version check server!");
        return $info["latest_version"];
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
