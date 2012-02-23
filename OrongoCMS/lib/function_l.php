<?php
/**
 * language function
 * @author Jaco Ruit 
 */

/**
 * get language text
 * @param String $paramText text to convert
 * @param array/string $paramArgs  sprintf args
 */
function l($paramText, $paramArgs = null){
    if(!function_exists('getLanguage')) return "!!LANGUAGE_NOT_LOADED!!";
    return getLanguage()->getText($paramText, $paramArgs);
}

?>
