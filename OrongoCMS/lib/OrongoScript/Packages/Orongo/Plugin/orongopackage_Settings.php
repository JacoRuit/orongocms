<?php
/**
 * Settings OrongoScript Package
 *
 * @author Jaco Ruit
 */
class OrongoScriptSettings extends OrongoPackage {
    
    private $settings = array();
    
    public function __construct($runtime) {
        if(!$runtime->isVar("%AuthKey%")) throw new Exception("Can't fetch settings no %AuthKey% was set.");
        $this->settings = Plugin::getSettings($runtime->getVar("%AuthKey%")->get());
        foreach($this->settings as &$setting){ $setting = new OrongoVariable($setting); }
    }
    
    public function getFunctions() {
        return array(new FuncSettingsGetAll($this->settings), new FuncSettingsGet($this->settings));
    }
}



/**
 * GetAll OrongoScript function
 *
 * @author Jaco Ruit
 */
class FuncSettingsGetAll extends OrongoFunction {
    
    private $settings;
    
    public function __construct($paramSettings){
        $this->settings = $paramSettings;
    }

    public function __invoke($args) {
        return $this->settings;
    }

    public function getShortname() {
        return "GetAll";
    }
    
    public function getSpace(){
        return "Settings";
    }
}

/**
 * Get OrongoScript function
 *
 * @author Jaco Ruit
 */
class FuncSettingsGet extends OrongoFunction {
    
    private $settings;
    
    public function __construct($paramSettings){
        $this->settings = $paramSettings;
    }

    public function __invoke($args) {
        if(count($args) < 1) throw new OrongoScriptParseException("Argument missing for Settings.Get()"); 
        if(!isset($this->settings[$args[0]]))
            throw new Exception("Can't find setting: " . $args[0]);
        return new OrongoVariable($this->settings[$args[0]]);
    }

    public function getShortname() {
        return "Get";
    }
    
    public function getSpace(){
        return "Settings";
    }
}

?>
