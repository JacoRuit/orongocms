<?php

/**
 * Language Class
 *
 * @author Jaco Ruit
 */
class Language {
    
    private $langArray;
    
    /**
     * Construct the language
     * @param String $paramLanguageFile path of the language file
     */
    public function __construct($paramLanguageFile){
        if(!file_exists($paramLanguageFile)) throw new Exception("Language file doesn't exist!");
        $this->langArray = self::languageFileToArray($paramLanguageFile);
    }
    
    /**
    * get language string
    * @param String $paramText text to convert
    * @param array/string $paramArgs  sprintf args
    */
    public function getText($paramText, $paramArgs = null){
        if(!is_string($paramText)) throw new IllegalArgumentException("Invalid argument, string expected!");
        $paramText = strtoupper(str_replace(" ", "_", $paramText));
        if(!array_key_exists($paramText, $this->langArray)) return "?" . $paramText . "?";
        if($paramArgs == null) return $this->langArray[$paramText];
        if(is_array($paramArgs)){
            $return = vsprintf($this->langArray[$paramText], $paramArgs);
            if(!$return) return $paramText;
            return $return;
        }
        else return sprintf($this->langArray[$paramText], $paramArgs);
    }
    
    
    /**
     * Set temporary language (only for this script execution)
     * @param String $paramLanguageFile name of the language file
     */
    public function setTempLanguage($paramLanguageFile){
        if(!file_exists($paramLanguageFile)) throw new Exception("Language file doesn't exist!");
        $this->langArray = self::languageFileToArray($paramLanguageFile);
    }
    
    /**
     * Converts a language file to an array
     * @param String $paramLanguageFile whole path to the language file
     * @return array containing all the strings
     */
    public static function languageFileToArray($paramLanguageFile){
        $langFile = file_get_contents($paramLanguageFile);
        $lines = preg_split( '/\r\n|\r|\n/', $langFile);
        $langArray = array();
        foreach($lines as $line){
            $exp = explode(":", $line,2);
            if(empty($exp[0])) continue;
            if(count($exp) <= 1) throw new Exception("Invalid language file: " . $langPath);
            if(array_key_exists(trim($exp[0]), $langArray)) continue;
            $langArray[trim($exp[0])] = trim($exp[1]);
        }
        return $langArray;
    }
}

?>
