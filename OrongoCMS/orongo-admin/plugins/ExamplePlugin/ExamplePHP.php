<?php

/**
 * Example Plugin
 *
 * @author Jaco Ruit
 */
class ExampleClass implements IOrongoPlugin{
    private $injectHTML = true;
    private $htmlToInject = '';
    
    
    public function __construct(){

        $stored = Plugin::getSettings();
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
        $ex = "";
        $htmlArray = array();
        if(Plugin::getCurrentPage() == PAGE_INDEX) $ex = "Yay index!";
        $stored = Plugin::getSettings();
        $htmlArray['javascript']['document_ready']="";
        $msgbox = new MessageBox("The example plugin loads fine! :D", "ExamplePlugin :)");
        $htmlArray['html']['body'] = $msgbox->toHTML();
        $c=0;
        //debug lines:
       /** foreach($stored as $value){
            $htmlArray['javascript']['document_ready'] .= "prettyAlert('#msgbox" . $c . "', '" . $value . "! :D','Plugin');          ";
            $htmlArray['html']['body'] .= "<div id='msgbox" . $c . "'></div>";
            $c++;
        } **/
        $htmlArray['html']['footer'] = $ex . "<br />" . Settings::getWebsiteName() . " is using example plugin!";
        return $htmlArray;
    }
    

    public function getVersionNumber(){
        return 0.1;
    }
    
    public function onInstall(){
        
    }
    
    public function getSettings(){
        return array('show_menubar' => false);
    }
    
}

?>
