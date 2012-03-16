<?php

/**
 * OrongoScriptPluginBridge CLass
 *
 * @author Jaco Ruit
 */
class OrongoScriptPluginBridge extends OrongoPluggableObject{
    
    private $parser;
    
    public function __construct($paramArgs) {
        if(!isset($paramArgs['osc_file'])) throw new Exception("Argument 'osc_file' missing.");
        $this->parser = new OrongoScriptParser(file_get_contents($paramArgs['osc_file']));
        $this->parser->startParser(null, array(
            "%File%" => $paramArgs['osc_file'],
            "%AuthKey%" => $paramArgs['auth_key'],
            "%Time%" => $paramArgs['time']
        ));
    }
    
    public function getVersionString() {
        return "?";
    }
    
    public function onInstall() {
        return;
    }
    
    public function getRuntime(){
        return $this->parser->getRuntime();
    }
    
    public function getParser(){
        return $this->parser;
    }
}
?>
