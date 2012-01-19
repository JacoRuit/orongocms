<?php

/**
 * Example Plugin
 *
 * @author Jaco Ruit
 */
class ExampleClass extends OrongoPluggableObject{
    private $injectHTML = true;
    private $htmlToInject = '';
    private $htmlArray;
    
    public function __construct(){
         $this->htmlArray = array();
        require 'TerminalPlugin.php';
        Plugin::hookTerminalPlugin(new TerminalPlugin());
        $stored = Plugin::getSettings();
        OrongoEventManager::addEventHandler('article_edit', $this, 'onArticleEdit');
        //Access the settings in the array.
        if($stored['example_setting_2']){
            $this->injectHTML = true;
            $this->htmlToInject = $stored['example_setting_1'];
        }else{
            $this->injectHTML = false;
        }
        $store_string = 'this is a variable';
        $bool = Storage::store('a_storage_key', $store_string, true );
        if($bool){
            //This will fail and return false, because overwrite = false
            $bool2 = Storage::store('a_storage_key', 'will this overwrite?', false);
            if($bool2 == false){
                //This wil return: this is a variable
                $returnString = Storage::get('a_storage_key');
                
                //Delete the storage
                Storage::delete('a_storage_key');             
            }
        }
    }
    
    public function injectHTMLOnWebPage(){
        return true;
    }
    
    
    public function getHTML(){
        //$p = "not supported";
       
        /**if(getCurrentPage() == 'index') $p = "the landing page";
        else if(getCurrentPage() == 'article') $p = "an article";
        else if(getCurrentPage() == 'page') $p = "a page";
        else if(getCurrentPage() == 'archive') $p = "archive"; 
        $htmlArray['javascript']['document_ready']="";
        $msgbox = new MessageBox("The example plugin detected that you're viewing " . $p . "." , "ExamplePlugin getHTML() function");
        $htmlArray['html']['body'] = $msgbox->toHTML();
        $c=0;
        debug lines:       
        $stored = Plugin::getSettings();
        foreach($stored as $value){
            $htmlArray['javascript']['document_ready'] .= "prettyAlert('#msgbox" . $c . "', '" . $value . "! :D','Plugin');          ";
            $htmlArray['html']['body'] .= "<div id='msgbox" . $c . "'></div>";
            $c++;
        } **/
        $this->htmlArray['html']['footer'] = "<br />" . Settings::getWebsiteName() . " is using example plugin. This text was added by the Example Plugin.";
        return $this->htmlArray;
    }
    

    public function getVersionNumber(){
        return 0.1;
    }
    
    public function onInstall(){
        
    }
    
    public function getSettings(){
        //TODO make this work
        return array('show_menubar' => false);
    }
    
    public function onArticleEdit($eventArgs){
        $msgBox = new MessageBox($eventArgs['by']);
        $this->htmlArray['html']['body'] = $msgBox->toHTML();
    }
    
}

?>
