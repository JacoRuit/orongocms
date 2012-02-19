<?php

/**
 * Cache Class
 *
 * @author Jaco Ruit
 */
class Cache {
    
    /**
     * Contains all the items stored in the cache
     * @var array Cache array
     */
    private static $cacheArray = array();
    

    /**
     * Caches the value of the variable in the cache array
     * @param String $paramKey Key to access the variable later (Cache key)
     * @param var $paramVar Variable to cache
     * @param boolean $paramOverwrite If true and the key already exists it will overwrite it
     * @return boolean Indicates if the storage was succesful
     */
    public static function store($paramKey, $paramValue, $paramShare = false, $paramOverwrite = true){
        if($paramShare) $key = $paramKey; 
        else{
            $backtrace = debug_backtrace();
            if(!is_array($backtrace)) throw new Exception ("Couldn't get array from debug_backtrace function.");
            if(!isset($backtrace[0]['file'])) throw new IllegalMemoryAccessException ("Debug backtrace provided false information.");
            $file = $backtrace[0]['file'];
            $key = $file . $paramKey;
        }
        if($paramOverwrite == false && array_key_exists($key, self::$cacheArray)) return false;
        self::$cacheArray[$key] = $paramValue;
        return true;
    }
    
    /**
     * Gets the value of the key
     * @param String $paramKey Cache key
     * @param boolean $paramPrivate Boolean indicating if the value you are trying to access was stored private
     * @return var The value
     */
    public static function get($paramKey, $paramPrivate = true){
        if($paramPrivate){
            $backtrace = debug_backtrace();
            if(!is_array($backtrace)) throw new Exception ("Couldn't get array from debug_backtrace function.");
            if(!isset($backtrace[0]['file'])) throw new IllegalMemoryAccessException ("Debug backtrace provided false information.");
            $file = $backtrace[0]['file'];
            $key = $file . $paramKey;
        }else $key = $paramKey;
        if(!array_key_exists($key, self::$cacheArray)) throw new IllegalMemoryAccessException("There were no items cached with this key!");
        return self::$cacheArray[$key];
    }
    
    /**
     * Checks if variable with specified key was stored
     * @param String $paramKey Cache key
     * @param boolean $paramPrivate Boolean indicating if the value you are trying to access was stored private
     */
    public static function isStored($paramKey, $paramPrivate = true){
        if($paramShared){
            $backtrace = debug_backtrace();
            if(!is_array($backtrace)) throw new Exception ("Couldn't get array from debug_backtrace function.");
            if(!isset($backtrace[0]['file'])) throw new IllegalMemoryAccessException ("Debug backtrace provided false information.");
            $file = $backtrace[0]['file'];
            $key = $file . $paramKey;
        }else $key = $paramKey;
        return array_key_exists($key,self::$cacheArray);
    }
}

?>
