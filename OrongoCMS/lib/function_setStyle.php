<?php
/**
 * setStyle function
 *
 * @author Jaco Ruit
 */

/**
* Defines the function getStyle
* @param Style $paramStyle style object
*/

function setStyle($paramStyle){
    if(function_exists('getStyle')) return;
    if(($paramStyle instanceof Style) == false) throw new IllegalArgumentException("Invalid argument, style object expected!");
    global $_orongo_style;
    $GLOBALS['_orongo_style'] = &$paramStyle;
    /**
     * @return Style
     */
    function getStyle(){ return $GLOBALS['_orongo_style']; };
}
?>
