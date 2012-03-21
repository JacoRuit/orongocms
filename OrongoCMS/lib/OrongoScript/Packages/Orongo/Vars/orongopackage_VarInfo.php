<?php
/**
 * VarInfo OrongoScript Package
 *
 * @author Jaco Ruit
 */
class OrongoScriptVarInfo extends OrongoPackage {
    
    private $runtime;
    public function __construct($runtime) {
        $this->runtime = &$runtime;
    }
    public function getFunctions() {
        return array(new FuncDump(), new FuncIsSet($this->runtime));
    }
}



/**
 * Dump OrongoScript function
 *
 * @author Jaco Ruit
 */
class FuncDump extends OrongoFunction {
    

    public function __invoke($args) {
        if(count($args) < 1) throw new OrongoScriptParseException("Argument missing for VarInfo.Dump()");     
        var_dump($args[0]);
        return new OrongoVariable(null);
    }

    public function getShortname() {
        return "Dump";
    }
    
    public function getSpace(){
        return "VarInfo";
    }
}

/**
 * IsSet OrongoScript function
 * 
 * @author Jaco Ruit 
 */
class FuncIsSet extends OrongoFunction {
    
    private $runtime;
    
    public function __construct($paramRuntime){
        $this->runtime =& $paramRuntime;
    }
    
    public function __invoke($args) {
        if(count($args) < 1) throw new OrongoScriptParseException("Argument missing for VarInfo.IsSet()"); 
        $field = "__main__";
        $var = trim($args[0]);
        if(stristr($args[0], ":")){
            $field = explode(":", $args[0], 2);
            foreach($field as &$varb){ $varb = trim($varb); }
            $var = $field[0];
            $field = $field[1];
        }
        return new OrongoVariable($this->runtime->isVar($var, $field));
    }

    public function getShortname() {
        return "IsSet";
    }
    
    public function getSpace(){
        return "VarInfo";
    }
}
?>
