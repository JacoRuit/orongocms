<?php

/**
 * Display Class
 *
 * @author Jaco Ruit
 */
class Display {
    
    private $smarty;
    private $tpls;
    private $rendered;
    private $messageboxes;
    private $head;
    
    /**
     * Initialize the smarty and display object
     * @param String $paramTemplateDir smarty/template dir
     * @param String $paramCompileDir smarty/compile dir
     * @param String $paramCacheDir smarty/cache dir
     * @param String $paramConfigDir  smarty/config dir
     */
    public function __construct($paramTemplateDir, $paramCompileDir = "../smarty/compile", $paramCacheDir = "../smarty/cache", $paramConfigDir = "../smarty/config"){
        $this->smarty = new Smarty();
        $this->smarty->template_dir = $paramTemplateDir;
        $this->smarty->compile_dir = $paramCompileDir;
        $this->smarty->cache_dir = $paramCacheDir;
        $this->smarty->config_dir = $paramConfigDir; 
        
        $this->rendered = false;
        $this->tpls = array();
        $this->messageboxes = "";
        $this->head = "<meta name=\"generator\" content=\"OrongoCMS r" . REVISION . "\" />";
    }
    
    /**
     * Adds string to template variable
     * @param String $paramVariable name of the variable
     * @param String $paramAdd string to add
     */
    public function addToTemplateVariable($paramVariable, $paramAdd){
        $this->setTemplateVariable($paramVariable, $this->getTemplateVariable($paramVariable) . $paramAdd);
    }
    
    /**
     * Returns template variable
     * @param String $paramVariable name of the variable
     */
    public function getTemplateVariable($paramVariable){
        $var = "";
        try{
            $var = $this->smarty->getTemplateVars($paramVariable);
        }catch(Exception $e){ }
        return $var;
    }
    
    /**
     * Sets a template variable
     * @param String $paramVariable name of the variable
     * @param String $paramValue value of the variable
     */
    public function setTemplateVariable($paramVariable, $paramValue){
        if($this->rendered) return;
        $this->smarty->assign($paramVariable,$paramValue);
    }
    
    /**
     * Adds a messagebox to display
     * @param MessageBox $paramMessageBox MessageBox object
     */
    public function addMessageBox($paramMessageBox){
        $this->messageboxes .= $paramMessageBox->toHTML();
    }
    
    /**
     * Adds a TPL file
     * @param String $paramTPLFile TPL (*.orongo) file to add to display 
     * @return boolean indicating if was added succesful
     */
    public function add($paramTPLFile){
        if(!is_string($paramTPLFile)) throw new IllegalArgumentException("Invalid argument, string expected.");
        if(in_array($paramTPLFile, $this->tpls)) return false;
        $this->tpls[count($this->tpls)] = $paramTPLFile;
        return true;
    }
    
    /**
     * Delets a TPL file
     * @param String $paramTPLFile TPL (*.orongo) file to delete from display
     */
    public function delete($paramTPLFile){
        foreach($this->tpls as &$tpl){
            if($tpl == $paramTPLFile){
                $tpl = null;
                return;
            }
        }
    }
    
    /**
     * Renders the Display
     */
    public function render(){
        if($this->rendered) return;
        $this->setTemplateVariable("website_name", Settings::getWebsiteName());
        $this->setTemplateVariable("website_url", Settings::getWebsiteURL());
        $this->addToTemplateVariable("body", $this->messageboxes);
        $this->addToTemplateVariable("head", $this->head);
        if(getUser() != null){
            $this->setTemplateVariable("user", getUser());
            $mb = new MenuBar(getUser());
            $this->setTemplateVariable("menu_bar", $mb->toHTML());
        }else{
            $this->setTemplateVariable("menu_bar", "");
        }
        foreach($this->tpls as $tpl){
            if(empty($tpl)) continue;
            $this->smarty->display($tpl);
        }
        $this->rendered = true;
    }
}

?>
