<?php
/**
 * language function
 * @author Jaco Ruit 
 */

/**
 * get language
 * @param String $paramSentence sentence to convert
 * @param array/string $paramArgs  sprintf args
 */
function l($paramSentence, $paramArgs = null){
    if(!is_string($paramSentence)) throw new IllegalArgumentException("Invalid argument, string expected!");
    $LANG = Settings::getLangArray();
    $paramSentence = strtoupper(str_replace(" ", "_", $paramSentence));
    if(!array_key_exists($paramSentence, $LANG)) return "?" . $paramSentence . "?";
    if($paramArgs == null) return $LANG[$paramSentence];
    if(is_array($paramArgs)){
        $return = call_user_func("sprintf", array($LANG[$paramSentence] + $paramArgs));
        if(!$return) return $paramSentence;
        return $return;
    }
    else return sprintf($LANG[$paramSentence], $paramArgs);
}

?>
