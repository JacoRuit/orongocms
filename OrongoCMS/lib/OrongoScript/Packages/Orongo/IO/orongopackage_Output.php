<?php
/**
 * Output OrongoScript Package
 *
 * @author Jaco Ruit
 */
class OrongoScriptOutput extends OrongoPackage {
    
    public function __construct($runtime) {
        
    }
    public function getFunctions() {
        return array(new FuncPrint(), new FuncDie());
    }
}



/**
 * Print OrongoScript function
 *
 * @author Jaco Ruit
 */
class FuncPrint extends OrongoFunction {
    

    public function __invoke($args) {
        $str = "";
        foreach($args as $arg){
            if(is_object($arg)){
                $str .= "Object";
                continue;
            }
            $str .= $arg;
        }
        echo $str;
    }

    public function getShortname() {
        return "Print";
    }
    
    public function getSpace(){
        return "Output";
    }
}

/**
 * Die OrongoScript function
 *
 * @author Jaco Ruit
 */
class FuncDie extends OrongoFunction {
    

    public function __invoke($args) {
        $str = "";
        foreach($args as $arg){
            if(is_object($arg)){
                $str .= "Object";
                continue;
            }
            $str .= $arg;
        }
        die($str);
    }

    public function getShortname() {
        return "Die";
    }
    
    public function getSpace(){
        return "Output";
    }
}


?>
