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
        getDatabase()->delete("plugin_data", "access_key =  %s", $accessKey);
        foreach($info['plugin']['settings'] as $key=>$value){
            $setting = $key;
            foreach($info['plugin']['settings'][$key] as $key=>$value){
                if($key == 'type'){
                    $typeSetting = $value;
                    self::installSetting($accessKey , $setting, $typeSetting);
                }else if($key == 'default'){
                    getDatabase()->update("plugin_data",array(
                        "setting_value" => $value
                    ),"`access_key`=%s AND `setting`=%s", $accessKey, $setting);
                }
            }
        }
        getDatabase()->delete("activated_plugins", "plugin_xml_path=%s", $paramInfoXML);
        getDatabase()->insert("activated_plugins", array(
            "plugin_xml_path" => $paramInfoXML
        ));
    }
    
    /**
     * Installs a setting
     * @param String $paramAccessKey Plugin access key
     * @param String $paramSetting     Setting name
     * @param String $paramSettingType Setting type
     */
    private static function installSetting($paramAccessKey, $paramSetting, $paramSettingType){
        getDatabase()->insert("plugin_data", array(
            "access_key" => $paramAccessKey,
            "setting" => $paramSetting,
            "setting_type" => $paramSettingType,
            "setting_value" => ""
        ));
    }
    
    /**
     * Gets the plugin settings
     * @param String $paramAuthKey Plugin auth key 
     * @return array Settings of plugin
     */
    public static function getSettings($paramAuthKey){
        if(!isset(self::$authKeys[$paramAuthKey])) throw new IllegalMemortyAccessException("Invalid auth key!");
        else $accessKey = self::$authKeys[$paramAuthKey];
        $rows = getDatabase()->query("SELECT `setting_value`, `setting`, `setting_type` FROM `plugin_data` WHERE `access_key` = %s", $accessKey);
        $settings = array();
        foreach($rows as $row){
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
        return $settings;
    }
    
    /**
     * Sets a plugin setting
     * @param String $paramAuthKey Plugin auth key 
     * @param String $paramSetting      The setting to edit
     * @param String $paramValue        New value of settings
     */
    public static function setSetting($paramAuthKey, $paramSetting, $paramValue){
        if(!isset(self::$authKeys[$paramAuthKey])) throw new IllegalMemoryAccessException("Invalid auth key!");
        else $accessKey = self::$authKeys[$paramAuthKey];
        $paramSetting =  mysql_escape_string($paramSetting);
        $paramValue =  mysql_escape_string($paramValue);
        getDatabase()->update("plugin_data",array(
            "setting_value" => $paramValue
        ),"`access_key`=%s AND `setting`=%s", $accessKey, $paramSetting);
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
        $loadedPluginNames = array();
        if(self::$requiresDone) throw new Exception();
        self::$requiresDone = true;
        self::$authKeys = array();
        $rows = getDatabase()->query("SELECT `plugin_xml_path` FROM `activated_plugins`");
        $plugins = array();
        $count = 0;
        foreach($rows as $row){
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
               if(($plugin instanceof OrongoPluggableObject) == false){
                   throw new ClassLoadException("Invalid plugin object!");
               }
               $plugin->setXMLFile($infoXML);
               if(in_array($plugin->getName(), $loadedPluginNames))
                       throw new ClassLoadException("There is already a plugin loaded with this name: " . $plugin->getName());
               $plugins[$count] = $plugin; 
               $count++;
            }catch(IllegalMemoryAccessException $ie){
                throw new ClassLoadException("Plugin tried to access illegal memory. Unable to load plugin: <br /> " . $phpFile);
                continue;
            }catch(Exception $e){
                throw new ClassLoadException("Unable to load plugin: <br /> " . $phpFile . "<br/><br /><strong>Exception</strong><br />" . $e->getMessage());
                continue;
            }
        }
        return $plugins;
    }
    
    /**
     * Gets activated plugins count
     * @return int plugins count
     */
    public static function getPluginCount(){
        getDatabase()->query("SELECT `plugin_xml_path` FROM `activated_plugins`");
        return getDatabase()->count();
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
