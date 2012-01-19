<?php
/**
 * Plugin Class
 *
 * @author Jaco Ruit
 */
class Plugin {
    
    private static $tPlugins = array();
    private static $requiresDone = false;
    private static $authKeys;
    
    /**
     * Installs database for the plugin
     * @param String $paramInfoXML path of info.xml
     */
    public static function install($paramInfoXML){
        if(file_exists($paramInfoXML) == false) throw new Exception("The plugin's info.xml doesn't exist!");
        $xml = @simplexml_load_file($paramInfoXML);
        $json = @json_encode($xml);
        $info = @json_decode($json, true);
        $setting = '';
        $typeSetting= '';
        if(!isset($info['plugin']['access_key'])) throw new Exception("The plugin's access key wasn't found");
        $accessKey = $info['plugin']['access_key'];
        $q = "DELETE FROM `plugins` WHERE `access_key` = '" . $accessKey . "'";
        getDatabase()->execQuery($q);
        foreach($info['plugin']['settings'] as $key=>$value){
            $setting = $key;
            foreach($info['plugin']['settings'][$key] as $key=>$value){
                if($key == 'type'){
                    $typeSetting = $value;
                    self::installSetting($accessKey , $setting, $typeSetting);
                }else if($key == 'default'){
                    $default = $value;
                    self::setSetting($accessKey, $setting, $default);
                }
            }
        }
        $q = "INSERT INTO `activated_plugins` (`plugin_xml_path`) VALUES ('" . $paramInfoXML . "')";
        getDatabase()->execQuery($q);
    }
    
    /**
     * Installs a setting
     * @param String $paramAccessKey Plugin access key
     * @param String $paramSetting     Setting name
     * @param String $paramSettingType Setting type
     */
    private static function installSetting($paramAccessKey, $paramSetting, $paramSettingType){
        $q = "INSERT INTO `plugins` (`access_key`, `setting`, `setting_type`, `setting_value`) VALUES ('" . $paramAccessKey . "', '" .$paramSetting . "', '" . $paramSettingType . "', '')";
        getDatabase()->execQuery($q);  
    }
    
    /**
     * Gets the plugin settings
     * @param String $paramAuthKey Plugin auth key 
     * @return array Settings of plugin
     */
    public static function getSettings($paramAuthKey){
        if(!isset(self::$authKeys[$paramAuthKey])) throw new IllegalMemortyAccessException("Invalid auth key!");
        else $accessKey = self::$authKeys[$paramAuthKey];
        $q = "SELECT `setting_value`, `setting`, `setting_type` FROM `plugins` WHERE `access_key` = '" . $accessKey . "'";
        $result = getDatabase()->execQuery($q);
        $settings = array();
        while($row = mysql_fetch_assoc($result)){
            if($row['setting_type'] == 'boolean'){
                if($row['setting_value'] == 'true'){
                    $settings[$row['setting']] = true;
                }else{
                    $settings[$row['setting']] = false;
                }
            }else{
                $settings[$row['setting']] = $row['setting_value'];
            }
        }
        mysql_free_result($result);
        return $settings;
    }
    
    /**
     * Sets a plugin setting
     * @param String $paramAuthKey Plugin auth key 
     * @param String $paramSetting      The setting to edit
     * @param String $paramValue        New value of settings
     */
    public static function setSetting($paramAuthKey, $paramSetting, $paramValue){
        if(!isset(self::$authKeys[$paramAuthKey])) throw new IllegalMemortyAccessException("Invalid auth key!");
        else $accessKey = self::$authKeys[$paramAuthKey];
        $paramSetting =  mysql_escape_string($paramSetting);
        $paramValue =  mysql_escape_string($paramValue);
        $q2 = "UPDATE `plugins` SET `setting_value` = '" . $paramValue . "' WHERE `access_key` = '" . $accessKey . "' AND `setting` = '" . $paramSetting . "'";
        getDatabase()->execQuery($q2);
    }
    
    /**
     * Gets the plugin main_class
     * @param String $paramInfoXML path of info.xml
     * @return String Main class of plugin
     */
    public static function getMainClass($paramInfoXML){
        $xml = @simplexml_load_file($paramInfoXML);
        $json = @json_encode($xml);
        $info = @json_decode($json, true);
        return $info['plugin']['main_class'];
    }
    
    /**
     * Gets the plugin description
     * @param String $paramInfoXML path of info.xml
     * @return String Description of plugin
     */
    public static function getDescription($paramInfoXML){
        if(empty($paramInfoXML) || !file_exists($paramInfoXML)) return "";
        $xml = @simplexml_load_file($paramInfoXML);
        $json = @json_encode($xml);
        $info = @json_decode($json, true);
        return $info['plugin']['description'];
    }
    
   /**
    * Gets the plugin name
    * @param String $paramInfoXML path of info.xml
    * @return String name of plugin
    */
    public static function getName($paramInfoXML){
        if(empty($paramInfoXML) || !file_exists($paramInfoXML)) return "";
        $xml = @simplexml_load_file($paramInfoXML);
        $json = @json_encode($xml);
        $info = @json_decode($json, true);
        return $info['plugin']['name'];
    }
    
    /**
     * Gets author info
     * @param String $paramInfoXML path of info.xml
     * @return array author info
     */
    public static function getAuthorInfo($paramInfoXML){
        if(empty($paramInfoXML) || !file_exists($paramInfoXML)) return "";
        $xml = @simplexml_load_file($paramInfoXML);
        $json = @json_encode($xml);
        $info = @json_decode($json, true);
        return $info['plugin']['author'];
    }
    
    /**
     * @param String $paramInfoXML path of info.xml
     * @return String PHP path of plugin
     */
    public static function getPHPFile($paramInfoXML){
        $xml = @simplexml_load_file($paramInfoXML);
        $json = @json_encode($xml);
        $info = @json_decode($json, true);
        $path = dirname($paramInfoXML) . '/' . $info['plugin']['php_file'] . '.php';
        if(file_exists($path)) return $path;
        else if (file_exists('../' . $path)) return '../' . $path;
        else if (file_exists(str_replace("orongo-admin/", "", $path))) return str_replace("orongo-admin/", "", $path);
        else throw new Exception("Couldn't find the PHP file (info.xml: " . $paramInfoXML . ")");
    }
    
    /**
     * Gets plugin access key
     * @param String $paramInfoXML path of info.xml
     * @return array author info
     */
    private static function getAccessKey($paramInfoXML){
        if(empty($paramInfoXML) || !file_exists($paramInfoXML)) return "";
        $xml = @simplexml_load_file($paramInfoXML);
        $json = @json_encode($xml);
        $info = @json_decode($json, true);
        return $info['plugin']['access_key'];
    }
    
    /**
     * Returns activated plugins
     * @return array containing plugins
     */
    public static function getActivatedPlugins(){
        if(self::$requiresDone) throw new Exception();
        self::$requiresDone = true;
        self::$authKeys = array();
        $q =  "SELECT `plugin_xml_path` FROM `activated_plugins`";
        $result = getDatabase()->execQuery($q);
        $plugins = array();
        $count = 0;
        while($row = mysql_fetch_assoc($result)){
            $infoXML = $row['plugin_xml_path'];
            if(!file_exists($infoXML)){
                if(!file_exists('../' . $infoXML)) continue;
                $infoXML = '../' . $infoXML;
            }
            try{
               $phpFile = self::getPHPFile($infoXML);
            }catch(Exception $e){ 
                $msgbox = new MessageBox();
                $msgbox->bindException($e);
                die($msgbox->getImports() . $msgbox->toHTML());
            }
            require_once($phpFile);
            try{
               $className = self::getMainClass($infoXML);
               $accessKey = self::getAccessKey($infoXML);
               $authKey = md5(rand() . microtime() . rand());
               self::$authKeys[$authKey] = $accessKey;
               $plugin = new $className(array("time" => time(), "auth_key" => $authKey));
               if($plugin instanceof OrongoPluggableObject) $plugins[$count] = $plugin; 
               $count++;
            }catch(IllegalMemoryAccessException $ie){
                throw new ClassLoadException("Plugin tried to access illegal memory. Unable to load plugin: <br /> " . $phpFile);
                continue;
            }catch(Exception $e){
                throw new ClassLoadException("Unable to load plugin: <br /> " . $phpFile);
                continue;
            }
        }
        mysql_free_result($result);
        return $plugins;
    }
    
    /**
     * Gets activated plugins count
     * @return int plugins count
     */
    public static function getPluginCount(){
        $q = "SELECT `plugin_xml_path` FROM `activated_plugins`";
        $result = getDatabase()->execQuery($q);
        $rows = mysql_num_rows($result);
        mysql_free_result($result);
        return $rows;
    }
    
    /**
     * Hooks a terminal plugin
     * @param $paramPlugin object class implementing IOrongoTerminalPlugin
     * @return boolean indicating if it was hooked succesfully
     */
    public static function hookTerminalPlugin($paramPlugin){
        if(($paramPlugin instanceof IOrongoTerminalPlugin) == false)
            throw new IllegalMemoryAccessException("Invalid argument, class implementing IOrongoTerminalPlugin expected.");
        $methods = array('about', 'version');
        $methods = array_merge($methods, get_class_methods('OrongoTerminal'));
        $pluginMethods = get_class_methods(get_class($paramPlugin));
        foreach($pluginMethods as $pluginMethod){
            if(in_array($pluginMethod, $methods))
                    return false;
        }
        self::$tPlugins[count(self::$tPlugins)] = $paramPlugin;
        return true;
    }
    
    /**
     * Returns the terminal plugins
     * @return array classes implementing IOrongoTerminalPlugin
     */
    public static function getHookedTerminalPlugins(){
        return self::$tPlugins;
    }
}

?>
