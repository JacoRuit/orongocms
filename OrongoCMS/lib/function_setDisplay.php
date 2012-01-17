<?php
/**
 * setDisplay function
 *
 * @author Jaco Ruit
 */

/**
* Defines the function setDisplay
* @param Display $paramDisplay display object
*/

function setDisplay($paramDisplay){
    if(($paramDisplay instanceof Display) == false) throw new IllegalArgumentException("Invalid argument, display object expected!");
    global $_orongo_display;
    $GLOBALS['_orongo_display'] = &$paramDisplay;
    @eval('function getDisplay(){ return $GLOBALS[\'_orongo_display\']; }');
}
?>
