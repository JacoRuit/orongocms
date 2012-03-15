<?php

/**
 * OrongoScriptRuntime CLass
 *
 * @author Jaco Ruit
 */
class OrongoScriptRuntime {
    
    private $variables;
    private $functions;
    private $customFunctions;
    private $tempVars;
    private $spaces;
    private $currentSpace;
    
    public function __construct(){
        $this->variables = array();
        $this->customFunctions = array();
        $this->functions = array();
        $this->tempVars = array();
        $this->spaces = array();
    }
    
    /**
     * Sets a variable only for this runtime only,
     * if the vars will be copied, this var will be ignored.
     * Excluded from the variables array.
     * @param var $paramName variable name
     * @param var $paramVar variable 
     */
     public function letTempVar($paramName, $paramVar){
        $tolet = "";
        if($paramVar instanceof OrongoVariable) $tolet = $paramVar;
        else $tolet = new OrongoVariable($paramVar);
        $this->tempVars[$paramName] = $tolet;
    }
    
    /**
     * @param var $paramName variable name
     * @param var $paramVar variable
     */
    public function letVar($paramName, $paramVar){
        $tolet = "";
        if($paramVar instanceof OrongoVariable) $tolet = $paramVar;
        else $tolet = new OrongoVariable($paramVar);
        $this->variables[$paramName] = $tolet;
    }
    
    /**
     * @param var $paramName variable name
     * @return var Stored Var
     */
    public function getVar($paramName){
        if(!array_key_exists($paramName, $this->variables) && !array_key_exists($paramName, $this->tempVars))
               throw new OrongoScriptParseException("Unknown variable: " . $paramName);
        if(array_key_exists($paramName, $this->tempVars)){
            if($this->tempVars[$paramName] instanceof OrongoVariable == false) return new OrongoVariable(null);
            return $this->tempVars[$paramName];
        }
        if($this->variables[$paramName] instanceof OrongoVariable == false) return new OrongoVariable(null);
        return $this->variables[$paramName];
    }
    
    /**
     * @return array all stored vars 
     */
    public function getVars(){
        return $this->variables;
    }
    
    /**
     * @param String $paramSpace Space name 
     */
    public function registerSpace($paramSpace){
        if(in_array($paramSpace, $this->spaces)) throw new OrongoScriptParseException("This space does exists already!");
        $this->spaces[count($this->spaces)] = $paramSpace;
        $this->functions[$paramSpace] = array();
        $this->customFunctions[$paramSpace] = array();
    }
    
    /**
     * @param array $paramFunctionData array containing all data from parseFunc function of parser 
     */
    public function registerFunction($paramFunctionData){
        $data = array( 'name' , 'param_count', 'logic', 'params');
        foreach($data as $_data)
            if(!array_key_exists($_data, $paramFunctionData)) throw new OrongoScriptParseException("Core error: data missing (registerFunction)");
        if(isset($this->customFunctions[$this->currentSpace][$paramFunctionData['name']]) || isset($this->functions[$paramFunctionData['name']])) 
            throw new OrongoScriptParseException("Function has already been registered for this session.");
        $this->customFunctions[$this->currentSpace][$paramFunctionData['name']] = array(
            'param_count' => $paramFunctionData['param_count'],
            'logic' => $paramFunctionData['logic'],
            'params' => $paramFunctionData['params']
        );
    }
    
    /**
     * @return String  Space name 
     */
    public function getCurrentSpace(){
        return $this->currentSpace;
    }
    
    /**
     * @param String $paramSpace Space name 
     */
    public function setCurrentSpace($paramSpace){
        $this->currentSpace = $paramSpace;
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
        $path = dirname(__FILE__) . '/' . $path;
        if(!file_exists($path)) throw new OrongoScriptParseException("Invalid import, package not found!");
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
            
            $p = new $class($this);
            if(($p instanceof OrongoPackage) == false) return;
            $funcs = $p->getFunctions();
            foreach($funcs as $newFunc){
                if(($newFunc instanceof OrongoFunction) == false) continue;
                try{ $this->registerSpace($newFunc->getSpace()); }catch(Exception $e){}
                if($this->isFunction($newFunc->getSpace(), $newFunc->getShortname())) continue;              
                $this->functions[$newFunc->getSpace()][$newFunc->getShortname()] = $newFunc;
            }
            return;
        }catch(Exception $e){
            throw new OrongoScriptParseException("Couldn't import: " . $path);
        }
    }
    
    /**
     * Checks if an variable was stored with this name
     * @param String $paramName var name
     */
    public function isVar($paramName){
        return array_key_exists($paramName, $this->variables) || array_key_exists($paramName, $this->tempVars);
    }
    
    /**
     * Checks if an function has this name
     * @param String $paramSpace Space name 
     * @param String $paramName function name
     */
    public function isFunction($paramSpace, $paramName){
       if(!in_array($paramSpace, $this->spaces)) throw new OrongoScriptParseException("Space: " . $paramSpace . " doesn't exist.");
       return array_key_exists($paramName, $this->functions[$paramSpace]) || array_key_exists($paramName, $this->customFunctions[$paramSpace]);
    }
    
    /**
     * Calls the function and returns the value
     * @param String $paramSpace Space name 
     * @param String $paramName function name
     * @param array $paramArgs args (optional)
     * @return OrongoVariable orongovariable(null) if it didnt return anything or any error occured else the OrongoVariable returned
     */
    public function execFunction($paramSpace, $paramName, $paramArgs = null){
        if(!is_array($paramArgs)) $args = array();
        else $args = $paramArgs;
        //you should prevent this..
        if(!$this->isFunction($paramSpace,$paramName)) return new OrongoVariable(null);
        if(isset($this->functions[$paramSpace][$paramName])){
            $func = &$this->functions[$paramSpace][$paramName];
            if(($func instanceof OrongoFunction) == false) return new OrongoVariable(null);
            $return = $func($args);
            if(($return instanceof OrongoVariable) == false) return new OrongoVariable(null);
            return $return;
        }else{
            $func = &$this->customFunctions[$paramSpace][$paramName];
            $argCount = empty($args[0]) ? 0 : count($args);
            if($func['param_count'] > $argCount) 
                    throw new OrongoScriptParseException("Invalid function call to " . $paramName. " (parameter count)", -10);
            $p = new OrongoScriptParser($func['logic']);
            $arguments = array();
            if($func['param_count'] > 0 && isset($func['params']) && is_array($func['params'])){
                $i = 0;
                foreach($func['params'] as $paramName){
                    $arguments[$paramName] = $args[$i];
                    $i++;
                }
            }
            $return = $p->startParser($this, $arguments);
            $this->variables = $p->getRuntime()->getVars();
            return new OrongoVariable($return);
        }
    }
}

?>
