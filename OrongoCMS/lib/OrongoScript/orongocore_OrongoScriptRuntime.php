<?php

/**
 * OrongoScriptRuntime CLass
 *
 * @author Jaco Ruit
 */
class OrongoScriptRuntime {
    
    private $variables;
    private $functions;
    
    public function __construct(){
        $this->variables = array();
        $this->functions = array();
    }
    
    /**
     * @param var $paramName variable name
     * @param var $paramVar variable
     */
    public function letVar($paramName, $paramVar){
        /**$vare = explode($paramVar, ".");
        $var = $vare[0];
        if(isset($this->variables[$var]) || count($var) > 1){
            if($this->variables[$var] instanceof OrongoClass){
                if(method_exists($this->variables[$var], $vare[1])){
                    $tolet = $this->variables->$vare[1];
                    if(($tolet instanceof OrongoVariable)== false) $tolet = new OrongoVariable(null);
                    $this->variables[$paramName] = $tolet;
                }
            }
        }**/
        $this->variables[$paramName] = new OrongoVariable($paramVar);
    }
    
    /**
     * @param var $paramName variable name
     * @return var Stored Var
     */
    public function getVar($paramName){
        if(!array_key_exists($paramName, $this->variables))
                throw new OrongoScriptParseException("Unknown variable: " . $paramName);
        if($this->variables[$paramName] instanceof OrongoVariable == false) return new OrongoVariable(null);
        return $this->variables[$paramName];
    }
    
    /**
     * Imports the classes
     * @param String $paramPackage package to load
     */
    public function import($paramPackage){
        $package = explode(".", $paramPackage);
        unset($package[count($package) - 1]);
        $path = "Packages/";
        for($i = 0; $i <= count($package) -1; $i++){
            $path .= $package[$i];
            if($i != count($package) - 1)
                $path .= "/";
        }
        //if(!is_dir($path)) throw new OrongoScriptParseException("Invalid package: " . $paramPackage . $path);
        $file = explode(".", $paramPackage);
        $file = end($file);
        if($file != "*") $path .= "/orongopackage_" . $file . ".php";
        else $path .= "/" . $file;
        $path = dirname(__FILE__) . '\\' . $path;

        require_once($path);
        $class = false;
        //Snippet provided here: http://stackoverflow.com/questions/928928/determining-what-classes-are-defined-in-a-php-class-file
        $php_file = file_get_contents($path);
        $tokens = token_get_all($php_file);
        $class_token = false;
        foreach ($tokens as $token) {
            if (is_array($token)) {   
                if ($token[0] == T_CLASS) { 
                    $class_token = true;
                } else if ($class_token && $token[0] == T_STRING) {
                     $class = $token[1];
                     $class_token = false;
                }
            }
            if($class != false) break;
        }
        try{
            
            $p = new $class;
            if(($p instanceof OrongoPackage) == false) return;
            $funcs = $p->getFunctions();
            foreach($funcs as $newFunc){
                if(($newFunc instanceof OrongoFunction) == false) continue;
                if($this->isFunction($newFunc->getShortname())) continue;
                $this->functions[$newFunc->getShortname()] = $newFunc;
            }
            return;
        }catch(Exception $e){
            throw new OrongoScriptParseException("Couldn't import: " . $path);
        }
         /**foreach(glob($path) as $file){
            echo $file;
            require_once($file);
            $class = false;
            //Snippet provided here: http://stackoverflow.com/questions/928928/determining-what-classes-are-defined-in-a-php-class-file
            $php_file = file_get_contents($file);
            $tokens = token_get_all($php_file);
            $class_token = false;
            foreach ($tokens as $token) {
                if (is_array($token)) {
                    if ($token[0] == T_CLASS) {
                        $class_token = true;
                    } else if ($class_token && $token[0] == T_STRING) {
                        $class = $token[1];
                        $class_token = false;
                    }
                }
                if($class != false) break;
            }
            try{
                $newFunc = new $class;
                if(($newFunc instanceof OrongoFunction) == false) continue;
                if($this->isFunction($newFunc->getShortname())) continue;
                $this->variables[$newFunc->getShortname()] = $newFunc;
                continue;
            }catch(Exception $e){
                throw new OrongoScriptParseException("Couldn't import: " . $path);
            }
        }**/
    }
    
    /**
     * Checks if an variable was stored with this name
     * @param String $paramName var name
     */
    public function isVar($paramName){
        return array_key_exists($paramName, $this->variables);
    }
    
    /**
     * Checks if an function has this name
     * @param String $paramName function name
     */
    public function isFunction($paramName){
        return array_key_exists($paramName, $this->functions);
    }
    
    /**
     * Calls the function and returns the value
     * @param String $paramName function name
     * @param array $paramArgs args (optional)
     * @return OrongoVariable orongovariable(null) if it didnt return anything or any error occured else the OrongoVariable returned
     */
    public function execFunction($paramName, $paramArgs = null){
        if(!is_array($paramArgs)) $args = array();
        else $args = $paramArgs;
        //you should prevent this..
        if(!$this->isFunction($paramName)) return new OrongoVariable(null);
        $func = &$this->functions[$paramName];
        if(($func instanceof OrongoFunction) == false) return new OrongoVariable(null);
        $return = $func($args);
        if(($return instanceof OrongoVariable) == false) return new OrongoVariable(null);
        return $return;
    }
}

?>
