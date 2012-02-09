<?php

/**
 * Example Plugin
 *
 * @author Jaco Ruit
 */

class ExampleClass extends OrongoPluggableObject{

    
    public function __construct($args){
        //$script = file_get_contents(LIB . "/OrongoScript/Tests/test.osc");
        //$parser = new OrongoScriptParser($script);
        //$parser->startParser();
        require 'TerminalPlugin.php';
        Plugin::hookTerminalPlugin(new TerminalPlugin());
        $stored = Plugin::getSettings($args['auth_key']);
        OrongoEventManager::addEventHandler('article_edit', $this, 'onArticleEdit');
        //Access the settings in the array.
        if(isset($stored['example_setting_2'] ) && $stored['example_setting_2']){
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
    

    public function getVersionString(){
        return "v0.1";
    }
    
    public function onInstall(){
        
    }
    
    public function onArticleEdit($eventArgs){
        $msgBox = new MessageBox($eventArgs['by']);
        getDisplay()->addObject($msgBox);
    }
    
}

?>
