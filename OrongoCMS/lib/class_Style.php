<?php

/**
 * Style Object
 *
 * @author Jaco Ruit
 */
class Style {
    

    private $styleFolder;
    private $styleName;
    private $styleCopyright;
    private $authorName;
    private $authorWebsite;
    private $usePHP;
    private $phpFile;
    private $mainClass;
    private $doHTMLArticle;
    private $doHTMLPage;
    private $stylePath;
    
    /**
     * Style Object
     * @param String $paramPrefix Prefix for the folder, sub-folders use this
     * @param String $paramStyleFolder Folder where info.xml and template files are located 
     * @author Jaco Ruit
     */
    public function __construct($paramPrefix, $paramStyleFolder){
        $this->styleFolder = $paramStyleFolder;
        $filePath = $paramPrefix . 'themes/'. $this->styleFolder . '/info.xml';
        if(file_exists($filePath) == false) throw new Exception("Unable to load the style. <br /> The info.xml file of the style doesn't exist!");
        $xml = @simplexml_load_file($filePath);
        $this->stylePath = 'themes/'. $this->styleFolder . "/";
        $json = @json_encode($xml);
        $info = @json_decode($json, true);
        $this->styleName = $info['style']['name'];
        $this->styleCopyright = $info['style']['copyright'];
        $this->authorName = $info['style']['author']['name'];
        $this->authorWebsite = $info['style']['author']['website'];
        if($info['style']['use_php'] == 'true'){
            $this->usePHP = true;
        }else{
            $this->usePHP = false;
        }
        if($this->usePHP){
            $this->phpFile = $info['style']['php_file'];
            
        }else{
            $this->phpFile = null;
        }
        if($this->usePHP){
            try{
                $pathString = $paramPrefix . 'themes/' . $this->getStyleFolder() . '/' . $this->getPHPFile();
                if(!file_exists($pathString)) throw new ClassLoadException();
                require $paramPrefix . 'themes/' . $this->getStyleFolder() . '/' . $this->getPHPFile();
                $this->mainClass = new $info['style']['main_class'];
                if(($this->mainClass instanceof IOrongoStyle) == false) throw Exception();
            }catch(Exception $e){
                throw new ClassLoadException("Unable to load the style. <br /> Please check the info.xml of the activated style for errors.");
            }
        }else{
            $this->mainClass = null;
        }
        if($info['style']['own_page_html'] == 'true'){
            $this->doHTMLPage = true;
        }else{
            $this->doHTMLPage = false;
        }
        if($info['style']['own_article_html'] == 'true'){
            $this->doHTMLArticle = true;
        }else{
            $this->doHTMLArticle = false;
        }
        
    }
    
    #   styleFolder
    /**
     * Returns style folder
     * Returns only the name of the folder not the whole URL
     * @return String folder of the style
     */
    public function getStyleFolder(){
        return $this->styleFolder;
    }
    
    #   styleName
    /**
     * Returns style name
     * This has to be put in the footer
     * @return String name of the style
     */
    public function getStyleName(){
        return $this->styleName;
    }
    
    #   styleCopyright
    /**
     * Returns copyright of the style
     * This has to be put in the footer
     * @return String Copyright string of the style
     */
    public function getCopyright(){
        return $this->styleCopyright;
    }
    
    #   authorName
    /**
     * Returns the name of the author of the style
     * This has to be put in the footer
     * @return String name of author
     */
    public function getAuthorName(){
        return $this->authorName;
    }
    
    #   authorWebsite
    /**
     * Returns website of the author of the style
     * This has to be put in the footer
     * @return String website of author
     */
    public function getAuthorWebsite(){
        return $this->authorWebsite;
    }
    
    #usePHP
    /**
     * Returns true if the style uses php to customize some Smarty things
     * @return boolean indicating if the style uses php
     */
    public function isUsingPHP(){
        return $this->usePHP;
    }
    
    #phpFile
    /**
     * Returns the PHP file name.
     * Always check if its not null before using this, because then it's not using php.
     * @return String PHP file name.
     */
    public function getPHPFile(){
        return $this->phpFile . '.php';
    }
    
    #stylePath
    /**
     * Returns the path of the style
     * @return String Path of style
     */
    public function getStylePath(){
        return $this->stylePath;
    }
    
    
    
    #mainClass
    /**
     * Returns style's main class
     * Always check if its not null before using this, because then it's not using php.
     * @return String Main Class
     */
    public function getMainClass(){
        return $this->mainClass;
    }
    
    /**
     * Checks if the style generates the HTML for articles
     * @return boolean indicating if it does
     */
    public function doArticleHTML(){
        return $this->doHTMLArticle;
    }
    
    /**
     * Checks if the style generates the HTML for pages
     * @return boolean indicating if it does
     */
    public function doPageHTML(){
        return $this->doHTMLPage;
    }
    
    
    /**
     * Runs the style's PHP file if it has one.
     * @param Smarty $paramSmarty Smarty Object
     */
    public function run(&$paramSmarty){
        if($this->usePHP){
            if($this->getPHPFile() != null){
                try{
                    if($this->mainClass instanceof IOrongoStyle){
                        $this->mainClass->run($paramSmarty);
                    }
                }catch(Exception $e){
                    //TODO throw error in db
                }
            }
        }
    }
    
    /**
     * Gets the HTML for a page
     * @param Page $paramPage Page object
     */
    public function getPageHTML($paramPage){
        try{
            if($this->doHTMLPage && $this->usePHP &&($this->mainClass instanceof IOrongoStyle)){
                $genHTML = $this->mainClass->getPageHTML($paramPage);
                if($genHTML != null && is_string($genHTML) && $genHTML != "") return $genHTML;
                else throw new Exception();
            }else throw new Exception();
        }catch(Exception $e){
            throw new Exception("Style doesn't generate the page HTML. Please call default function.");
        }
    }
    
    /**
     * Gets the HTML for an article
     * @param Article $paramArticle Article object
     */
    public function getArticleHTML($paramArticle){
        try{
            if($this->doHTMLArticle && $this->usePHP &&($this->mainClass instanceof IOrongoStyle)){
                $genHTML = $this->mainClass->getArticleHTML($paramArticle);
                if($genHTML != null && is_string($genHTML) && $genHTML != "") return $genHTML;
                else throw new Exception();
            }else throw new Exception(); 
        }catch(Exception $e){
            throw new Exception("Style doesn't generate the article HTML. Please call default function.");
        }
    }
    
    /**
     * Gets the HTML for an article array
     * @param array $paramArticles array of arrticle objects
     */
    public function getArticlesHTML($paramArticles){
        try{
            if($this->doHTMLArticle && $this->usePHP &&($this->mainClass instanceof IOrongoStyle)){
                $genHTML = $this->mainClass->getArticlesHTML($paramArticles);
                if($genHTML != null && is_string($genHTML) && $genHTML != "") return $genHTML;
                else throw new Exception();
            }else throw new Exception();
        }catch(Exception $e){
            throw new Exception("Style doesn't generate the HTML for articles. Please call default function.");
        }
    }
    
    /**
     * Installs database for the style
     * @param String $paramPrefix Prefix for the folder, sub-folders use this (starts from themes/)
     * @param String $paramStyleFolder folder where style is located
     */
    public static function install($paramPrefix, $paramStyleFolder){
        $filePath = $paramPrefix . 'themes/'. $paramStyleFolder . '/info.xml';
        if(file_exists($filePath) == false) throw new Exception("The style's info.xml doesn't exist!");
        $xml = @simplexml_load_file($filePath);
        $json = @json_encode($xml);
        $info = @json_decode($json, true);
        $setting = '';
        $typeSetting= '';
        if($info['style']['use_php'] != 'true') throw new Exception("Cannot install settings because the style is not using PHP.");
        foreach($info['style']['settings'] as $key=>$value){
            $setting = $key;
            foreach($info['style']['settings'][$key] as $key=>$value){
                if($key == 'type'){
                    $typeSetting = $value;
                    self::installSetting($info['style']['main_class'] , $setting, $typeSetting);
                }else if($key == 'default'){
                    $default = str_replace('{$website_url}', Settings::getWebsiteURL(), $value);
                    $q2 = "UPDATE `styles` SET `setting_value` = '" . $default . "' WHERE `style_main_class` = '" . $info['style']['main_class'] . "' AND `setting` = '" . $setting . "'";
                    @mysql_query($q2);
                }
            }
        }  
    }
    
    /**
     * Installs a setting
     * @param String $paramStyleMainClass Style main class
     * @param String $paramSetting     Setting name
     * @param String $paramSettingType Setting type
     */
    private static function installSetting($paramStyleMainClass, $paramSetting, $paramSettingType){
        $q = "INSERT INTO `styles` (`style_main_class`, `setting`, `setting_type`, `setting_value`) VALUES ('" . $paramStyleMainClass . "', '" .$paramSetting . "', '" . $paramSettingType . "', '')";
        @mysql_query($q);  
    }
    
    /**
     * Gets the style settings
     * @return array Settings of style
     */
    public static function getSettings(){
        $backtrace = debug_backtrace();
        if(!is_array($backtrace)) throw new Exception ("Couldn't get array from debug_backtrace function.");
        if(!isset($backtrace[1]['class'])) throw new IllegalMemoryAccessException("You can only call this function inside a class.");
        $q = "SELECT `setting_value`, `setting`, `setting_type` FROM `styles` WHERE `style_main_class` = '" . $backtrace[1]['class'] . "'";
        $result = @mysql_query($q);
        $settings = array();
        while($row = mysql_fetch_assoc($result)){
            if($row['setting_type'] == 'boolean'){
                if($row['setting_value'] == 'true'){
                    $settings[$row['setting']] = true;
                }else{
                    $settings[$row['setting']] = false;
                }
            }else{
                $settings[$row['setting']] = $row['setting_value'];
            }
        }
        mysql_free_result($result);
        return $settings;
    }
    
    /**
     * Sets a style setting
     * @param String $paramSetting      The setting to edit
     * @param String $paramValue        New value of settings
     */
    public static function setSetting($paramSetting, $paramValue){
        $backtrace = debug_backtrace();
        if(!is_array($backtrace)) throw new Exception ("Couldn't get array from debug_backtrace function.");
        if(!isset($backtrace[1]['class'])) throw new IllegalMemoryAccessException("You can only call this function inside a class.");
        $paramSetting =  mysql_escape_string($paramSetting);
        $paramValue =  mysql_escape_string($paramValue);
        $q1 = "SELECT `setting_value` FROM `styles` WHERE `style_main_class` = '" . $backtrace[1]['class'] . "' AND `setting` = '" . $paramSetting . "'";
        $result = @mysql_query($q1);
        if(mysql_num_rows($result)  < 1 && $backtrace[1]['class'] != __CLASS__) throw new IllegalMemoryAccessException("This settings doesn't exist or you are accessing the setting illegal.");
        $q2 = "UPDATE `styles` SET `setting_value` = '" . $paramValue . "' WHERE `style_main_class` = '" . $backtrace[1]['class'] . "' AND `setting` = '" . $paramSetting . "'";
        @mysql_query($q);
    }
}

?>
