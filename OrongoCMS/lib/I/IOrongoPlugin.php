<?php

/**
 * OrongoPlugin Interface
 * @author Jaco Ruit
 */
interface IOrongoPlugin {

    //no params
    public function __construct();
    
    /**
     * Does the actions it should do when installing
     */
    public function onInstall();
    
    /**
     * Checks if the plugin puts HTML on the webpage
     * @return boolean Indicating if the plugin puts HTML on the webpage
     */
    public function injectHTMLOnWebPage();
    
    /**
     * Returns array with HTML/JavaScript
     * @return array HTML/JS code in array with keys.
     */
    public function getHTML();
    
    /**
     * Returns version number of plugin
     * @return String version number
     */
    public function getVersionNumber();
    
    /**
     * Returns array with all settings
     * @return array settings array
     */
    public function getSettings();
}

?>
