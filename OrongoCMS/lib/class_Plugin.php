<?php
/**
 * Plugin Class
 *
 * @author Jaco Ruit
 */
class Plugin {
    
    private static $currentPage = 0;
    /**
     * Installs database for the plugin
     * @param String $paramPrefix Prefix for the folder, sub-folders use this
     * @param String $paramPluginFolder folder where plugin is located
     */
    public static function install($paramPrefix, $paramPluginFolder){
        $xml = @simplexml_load_file($paramPrefix . 'plugins/'. $paramPluginFolder . '/info.xml');
        $json = @json_encode($xml);
        $info = @json_decode($json, true);
        $setting = '';
        $typeSetting= '';
        foreach($info['plugin']['settings'] as $key=>$value){
            $setting = $key;
            foreach($info['plugin']['settings'][$key] as $key=>$value){
                if($key == 'type'){
                    $typeSetting = $value;
                    self::installSetting($info['plugin']['main_class'] , $setting, $typeSetting);
                }else if($key == 'default'){
                    $default = $value;
                    self::setSetting($info['plugin']['main_class'], $setting, $default);
                }
            }
        }  
    }
    
    /**
     * Installs a setting
     * @param String $paramPluginMainClass Plugin main class
     * @param String $paramSetting     Setting name
     * @param String $paramSettingType Setting type
     */
    private static function installSetting($paramPluginMainClass, $paramSetting, $paramSettingType){
        $q = "INSERT INTO `plugins` (`plugin_main_class`, `setting`, `setting_type`, `setting_value`) VALUES ('" . $paramPluginMainClass . "', '" .$paramSetting . "', '" . $paramSettingType . "', '')";
        @mysql_query($q);  
    }
    /**
     * Gets the plugin settings
     * @return array Settings of plugin
     */
    public static function getSettings(){
        $backtrace = debug_backtrace();
        if(!is_array($backtrace)) throw new IllegalMemoryAccessException("Debug backtrace didn't provide information.");
        if(!isset($backtrace[1]['class'])) throw new IllegalMemoryAccessException("You can only call this function inside a class.");
        $q = "SELECT `setting_value`, `setting`, `setting_type` FROM `plugins` WHERE `plugin_main_class` = '" . $backtrace[1]['class'] . "'";
        $result = @mysql_query($q);
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
     * @param String $paramSetting      The setting to edit
     * @param String $paramValue        New value of settings
     */
    public static function setSetting($paramSetting, $paramValue){
        $backtrace = debug_backtrace();
        if(!is_array($backtrace)) throw new IllegalMemoryAccessException("Debug backtrace didn't provide information.");
        if(!isset($backtrace[1]['class'])) throw new IllegalMemoryAccessException("You can only call this function inside a class.");
        $paramSetting =  mysql_escape_string($paramSetting);
        $paramValue =  mysql_escape_string($paramValue);
        $q1 = "SELECT `setting_value` FROM `plugins` WHERE `plugin_main_class` = '" . $backtrace[1]['class'] . "' AND `setting` = '" . $paramSetting . "'";
        $result = @mysql_query($q1);
        if(mysql_num_rows($result)  < 1) throw new IllegalMemoryAccessException("This settings doesn't exist or you are accessing the setting illegal.");
        $q2 = "UPDATE `plugins` SET `setting_value` = '" . $paramValue . "' WHERE `plugin_main_class` = '" . $backtrace[1]['class'] . "' AND `setting` = '" . $paramSetting . "'";
        @mysql_query($q);
    }
    
    /**
     * Gets the plugin name
     * @param String $paramPrefix Prefix for the folder, sub-folders use this
     * @param String $paramPluginFolder Plugin name
     * @return String Name of plugin
     */
    public static function getName($paramPrefix, $paramPluginFolder){
        $xml = @simplexml_load_file($paramPrefix . 'plugins/'. $paramPluginFolder . '/info.xml');
        $json = @json_encode($xml);
        $info = @json_decode($json, true);
        return $info['plugin']['name'];
    }
    
    /**
     * Gets the plugin main_class
     * @param String $paramPrefix Prefix for the folder, sub-folders use this
     * @param String $paramPluginFolder Plugin folder
     * @return String Main class of plugin
     */
    public static function getMainClass($paramPrefix,$paramPluginFolder){
        $xml = @simplexml_load_file($paramPrefix.'plugins/'. $paramPluginFolder . '/info.xml');
        $json = @json_encode($xml);
        $info = @json_decode($json, true);
        return $info['plugin']['main_class'];
    }
    
    /**
     * Gets the plugin description
     * @param String $paramPrefix Prefix for the folder, sub-folders use this
     * @param String $paramPluginFolder Plugin folder
     * @return String Description of plugin
     */
    public static function getDescription($paramPrefix, $paramPluginFolder){
        $xml = @simplexml_load_file($paramPrefix . 'plugins/'. $paramPluginFolder . '/info.xml');
        $json = @json_encode($xml);
        $info = @json_decode($json, true);
        return $info['plugin']['description'];
    }
    
    /**
     * @param String $paramPrefix Prefix for the folder, sub-folders use this
     * @param String $paramPluginFolder Plugin folder
     * @return String PHP file of plugin
     */
    public static function getPHPFile($paramPrefix, $paramPluginFolder){
        $xml = @simplexml_load_file($paramPrefix . 'plugins/'. $paramPluginFolder . '/info.xml');
        $json = @json_encode($xml);
        $info = @json_decode($json, true);
        return $info['plugin']['php_file'] . '.php';
    }
    
    /**
     * Returns activated plugins
     * @param String $paramPrefix Prefix for the folder, sub-folders inserts before plugins/plugin_name
     * @return array containing plugin folders
     */
    public static function getActivatedPlugins($paramPrefix){
        $q =  "SELECT `plugin_folder` FROM `activated_plugins`";
        $result = @mysql_query($q);
        $plugins = array();
        $count = 0;
        while($row = mysql_fetch_assoc($result)){
            $pluginFolder = $paramPrefix . 'plugins/' . $row['plugin_folder'] . '/'. self::getPHPFile($paramPrefix, $row['plugin_folder']);
            if(!file_exists($pluginFolder)) continue;
            require $pluginFolder;
            try{
               $className = self::getMainClass($paramPrefix, $row['plugin_folder']);
               $plugin = new $className;
               if($plugin instanceof IOrongoPlugin) $plugins[$count] = $plugin; 
               $count++;
            }catch(IllegalMemoryAccessException $ie){
                throw new ClassLoadException("Plugin tried to access illegal memory. Unable to load plugin: " . $pluginFolder);
                continue;
            }catch(Exception $e){
                throw new ClassLoadException("Unable to load plugin: " . $pluginFolder);
                continue;
            }
        }
        mysql_free_result($result);
        return $plugins;
    }
    
    /**
     * Sets current page
     * @param int $curPage current page
     */
    public static function setCurrentPage($curPage){
        self::$currentPage = $curPage;
    }
    
    /**
     * Returns the page code
     * PAGE_PAGE = 600
     * PAGE_INDEX = 700
     * PAGE_ARTICLE = 800
     * @return int current page
     */
    public static function getCurrentPage(){
        return self::$currentPage;
    }
    
    /**
     * Gets activated plugins count
     * @return int plugins count
     */
    public static function getPluginCount(){
        $q = "SELECT `plugin_folder` FROM `activated_plugins`";
        $result = @mysql_query($q);
        $rows = mysql_num_rows($result);
        mysql_free_result($result);
        return $rows;
    }
}

?>
