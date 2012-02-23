<?php
/**
 * setLanguage function
 *
 * @author Jaco Ruit
 */

/**
* Defines the function getLanguage
* @param Language $paramLanguage language object
*/
function setLanguage($paramLanguage){
    if(function_exists('getLanguage')) return;
    if(($paramLanguage instanceof Language) == false) throw new IllegalArgumentException("Invalid argument, language object expected!");
    global $_orongo_language;
    $GLOBALS['_orongo_language'] = &$paramLanguage;
    /**
     * @return Language
     */
    function getLanguage(){ return $GLOBALS['_orongo_language']; }
}
?>
