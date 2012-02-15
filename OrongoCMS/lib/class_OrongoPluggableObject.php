<?php
/**
 * OrongoPluggableObject
 *
 * @author Jaco Ruit
 */
abstract class OrongoPluggableObject{
    
    private $__XML_FILE__;
    
    abstract public function __construct($paramArgs);
    
    /**
     * Called when the plugin is being installed
     */
    abstract public function onInstall();
    
    /**
     * @return String version string
     */
    abstract public function getVersionString();
    
    /**
     * Sets XML FIle
     * @param String $paramXMLFile path to info.xml 
     */
    final public function setXMLFile($paramXMLFile){
        $this->__XML_FILE__ = $paramXMLFile;
    }
    
    /**
     * @return String path of info.xml
     */
    final public function getInfoPath(){
        return $this->__XML_FILE__;
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
