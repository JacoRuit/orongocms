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
    foreach($paramPlugins as $plugin){
        if(($plugin instanceof OrongoPluggableObject)==false) throw new Exception("Invalid argument, corrupt plugin classes!");
    }
    global $_orongo_plugins;
    $GLOBALS['_orongo_plugins'] = &$paramPlugins;
    @eval('function getPlugins(){ return $GLOBALS[\'_orongo_plugins\']; }');
}
?>
