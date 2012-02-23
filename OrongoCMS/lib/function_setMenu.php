<?php
/**
 * setMenu function
 *
 * @author Jaco Ruit
 */

/**
* Defines the function getMenu
* @param Menu $paramMenu Menu object
*/

function setMenu($paramMenu){
    if(function_exists('getMenu')) return;
    if(($paramMenu instanceof Menu) == false) throw new IllegalArgumentException("Invalid argument, menu object expected!");
    global $_orongo_menu;
    $GLOBALS['_orongo_menu'] = &$paramMenu;
    /**
     * @return Menu
     */
    function getMenu(){ return $GLOBALS['_orongo_menu']; }
}
?>
