<?php
/**
 * Utilities Class
 *
 * @author Jaco Ruit
 */
class Utils {
    
    /**
     * Returns how many times the string contains a string
     * @param String $paramString the string to search in (stack)
     * @param String $paramSearch the string to search (
     * needle)
     */
    public static function stringTimesContains(&$paramString,$paramSearch){
        $chars = str_split($paramString);
        $searchLength = strlen($paramSearch);
        $searchHelper = "";
        $count = 0;
        foreach($chars as $char){
            if(strlen($searchHelper) == $searchLength) $searchHelper = substr($searchHelper,1);      
            $searchHelper .= $char;
            if($searchHelper == $paramSearch) $count++;
        }
        return $count;
    }
}

?>
