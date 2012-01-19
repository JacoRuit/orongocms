<?php
/**
 * OrongoPluggableObject
 *
 * @author Jaco Ruit
 */
abstract class OrongoPluggableObject{
    
    abstract public function __construct();
    
    /**
     * Called when the plugin is being installed
     */
    abstract public function onInstall();
    
    /**
     * @return String version string
     */
    abstract public function getVersionString();
    
    /**
     * @return String path of info.xml
     */
    final public function getInfoPath(){
        $path = dirname(__FILE__) . 'info.xml';
        if(!file_exists($path)) return null;
        return $path;
    }
    
    /**
     * @return array Settings
     */
    final public function getSettings(){
        
    }
    
    /**
     * @return String plugin name
     */
    final public function getName(){
        return Plugin::getName($this->getInfoPath());
    }
    
    /**
     * @return String plugin description
     */
    final public function getDescription(){
        return Plugin::getDescription($this->getInfoPath());
    }
    
    /**
     * @param String $paramInfo What do you want to get
     * @return String name of author of plugin
     */
    final public function getAuthorInfo($paramInfo){
        $authorinfo = Plugin::getAuthorInfo($this->getInfoPath());
        if(!isset($authorinfo[$paramInfo])) return "";
        else return $authorinfo[$paramInfo];
    }
}

?>
