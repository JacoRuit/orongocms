<?php
/**
 * Template OrongoScript Package
 *
 * @author Jaco Ruit
 */
class OrongoScriptTemplate extends OrongoPackage {
    
    public function __construct($runtime) {
        
    }
    public function getFunctions() {
        return array(
            new FuncSetVar(), 
            new FuncGetVar(), 
            new FuncAddToVar(),
            new FuncAddHTML(),
            new FuncAddJS()
        );
    }
}

/**
 * SetVar OrongoScript function
 *
 * @author Jaco Ruit
 */
class FuncSetVar extends OrongoFunction {
    

    public function __invoke($args) {
        if(count($args) < 2) throw new OrongoScriptParseException("Arguments missing for Template.SetVar()");
        getDisplay()->setTemplateVariable($args[0], $args[1]);
    }

    public function getShortname() {
        return "SetVar";
    }
    
    public function getSpace(){
        return "Template";
    }
}

/**
 * GetVar OrongoScript function
 *
 * @author Jaco Ruit
 */
class FuncGetVar extends OrongoFunction {
    

    public function __invoke($args) {
        if(count($args) < 1) throw new OrongoScriptParseException("Argument missing for Template.GetVar()");
        getDisplay()->getTemplateVariable($args[0]);
    }

    public function getShortname() {
        return "GetVar";
    }
    
    public function getSpace(){
        return "Template";
    }
}

/**
 * AddToVar OrongoScript function
 * 
 * @author Jaco Ruit 
 */
class FuncAddToVar extends OrongoFunction{
    
    public function __invoke($args) {
        if(count($args) < 2) throw new OrongoScriptParseException("Arguments missing for Template.AddToVar()");
        getDisplay()->addToTemplateVariable($args[0], $args[1]);
    }
    
    public function getShortname() {
        return "AddToVar";
    }
    
    public function getSpace() {
        return "Template";
    }
}

/**
 * AddHTML OrongoScript function
 * 
 * @author Jaco Ruit 
 */
class FuncAddHTML extends OrongoFunction{
    
    public function __invoke($args) {
        if(count($args) < 1) throw new OrongoScriptParseException("Argument missing for Template.AddHTML()");
        if(!isset($args[1])) $args[1] = null;
        getDisplay()->addHTML($args[0], $args[1]);
    }
    
    public function getShortname() {
        return "AddHTML";
    }
    
    public function getSpace() {
        return "Template";
    }
}

/**
 * AddJS OrongoScript function
 * 
 * @author Jaco Ruit 
 */
class FuncAddJS extends OrongoFunction{
    
    public function __invoke($args) {
        if(count($args) < 1) throw new OrongoScriptParseException("Argument missing for Template.AddJS()");
        if(!isset($args[1])) $args[1] = null;
        getDisplay()->addJS($args[0], $args[1]);
    }
    
    public function getShortname() {
        return "AddJS";
    }
    
    public function getSpace() {
        return "Template";
    }
}

?>
