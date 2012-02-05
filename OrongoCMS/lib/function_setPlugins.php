<?php
/**
 * setPlugins function
 *
 * @author Jaco Ruit
 */

/**
* Defines the function getPlugins
* @param array $paramPlugins array containing page objects
*/

function setPlugins($paramPlugins){
    if(function_exists('getPlugins')) return;
    foreach($paramPlugins as $plugin){
        if(($plugin instanceof OrongoPluggableObject)==false) throw new Exception("Invalid argument, corrupt plugin classes!");
    }
    global $_orongo_plugins;
    $GLOBALS['_orongo_plugins'] = &$paramPlugins;
    function getPlugins(){ return $GLOBALS['_orongo_plugins']; }
}
?>
