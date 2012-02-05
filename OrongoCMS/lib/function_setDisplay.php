<?php
/**
 * setDisplay function
 *
 * @author Jaco Ruit
 */

/**
* Defines the function getDisplay
* @param Display $paramDisplay display object
*/

function setDisplay($paramDisplay){
    if(function_exists('getDisplay')) return;
    if(($paramDisplay instanceof Display) == false) throw new IllegalArgumentException("Invalid argument, display object expected!");
    global $_orongo_display;
    $GLOBALS['_orongo_display'] = &$paramDisplay;
    function getDisplay(){ return $GLOBALS['_orongo_display']; }
}
?>
