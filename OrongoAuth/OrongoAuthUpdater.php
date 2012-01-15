<?php

/**
 * OrongoAuthUpdater Class
 *
 * @author Jaco Ruit
 */
class OrongoAuthUpdater {
    
    /**
     * Updates OrongoAuth
     */
    public static function updateOrongoAuth(){
        $latest = self::getLatestVersion(REVISION);
        
        if($latest["version"] == ORONGOAUTH_VERSION)
            throw new Exception("The latest version is running already!");
        
        $fp = @fopen(dirname(__FILE__) . '/update.zip', 'w+');
        
        if($fp == false)
            throw new Exception("Couldn't open a write (w+) stream for " . __FILE__ . '/update.zip');
        
    }
    
    /**
     * Gets the info of the latest version
     * @param int $paramOrongoVersion Current OrongoCMS version
     * @return array containing result
     */
    public static function getLatestVersion($paramOrongoVersion){
        //build xml
        $xml = "<?xml version='1.0' encoding='UTF-8'?>";
        $xml .= "<versioncheck>";
        $xml .= "<orongocms_version>" . $paramOrongoVersion . "</orongocms_version>";
        $xml .= "</versioncheck>";
        
        $req = curl_init("http://www.orongocms.eu/orongoauth/checkversion.php");
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
        
        return $resarray;
    }
    
}

?>
