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
    private $globalVars;
    
    public function __construct(){
        $this->variables = array();
        $this->customFunctions = array();
        $this->functions = array();
        $this->tempVars = array();
        $this->globalVars = array();
        $this->spaces = array();
    }
    
    /**
     * Sets a variable only for this runtime only,
     * if the vars will be copied, this var will be ignored.
     * Excluded from the variables array.
     * This var is global cant be redefined
     * @param var $paramName variable name
     * @param var $paramVar variable 
     * @param String $paramField field of variable
     */
    public function letGlobalVar($paramName, $paramVar, $paramField = "__main__"){
        $tolet = "";
        if($paramVar instanceof OrongoVariable) $tolet = $paramVar;
        else $tolet = new OrongoVariable($paramVar);
        if(!isset($this->globalVars[$paramName])) $this->globalVars[$paramName] = array();
        $this->globalVars[$paramName][$paramField] = $tolet;
    }
    
    /**
     * Sets a variable only for this runtime only,
     * if the vars will be copied, this var will be ignored.
     * Excluded from the variables array.
     * @param var $paramName variable name
     * @param var $paramVar variable 
     * @param String $paramField field of variable
     */
     public function letTempVar($paramName, $paramVar, $paramField = "__main__"){
        $tolet = "";
        if($paramVar instanceof OrongoVariable) $tolet = $paramVar;
        else $tolet = new OrongoVariable($paramVar);
        if(!isset($this->tempVars[$this->currentSpace][$paramName])) $this->tempVars[$this->currentSpace][$paramName] = array();
        $this->tempVars[$this->currentSpace][$paramName][$paramField] = $tolet;
    }
    
        
    /**
     * @param var $paramName variable name
     * @param var $paramVar variable
     * @param String $paramField field of variable
     */
    public function letVar($paramName, $paramVar, $paramField = "__main__"){
        if(array_key_exists($paramName, $this->globalVars))
                throw new OrongoScriptParseException("Can not redefine global variables!");
        $tolet = "";
        if($paramVar instanceof OrongoVariable) $tolet = $paramVar;
        else $tolet = new OrongoVariable($paramVar);
        if(!isset($this->variables[$this->currentSpace][$paramName])) $this->variables[$this->currentSpace][$paramName] = array();
        $this->variables[$this->currentSpace][$paramName][$paramField] = $tolet;
    }
    
    /**
     * @param var $paramName variable name
     * @param String $paramField field of variable
     * @return var Stored Var
     */
    public function getVar($paramName, $paramField = "__main__"){ 
        if(array_key_exists($paramName, $this->globalVars) &&
           array_key_exists($paramField, $this->globalVars[$paramName])){
            if($this->globalVars[$paramName][$paramField] instanceof OrongoVariable == false) return new OrongoVariable(null);
            return $this->globalVars[$paramName][$paramField];
        }
        else if(array_key_exists($paramName, $this->tempVars[$this->currentSpace]) && 
                array_key_exists($paramField, $this->tempVars[$this->currentSpace][$paramName])){
            if($this->tempVars[$this->currentSpace][$paramName][$paramField] instanceof OrongoVariable == false) return new OrongoVariable(null);
            return $this->tempVars[$this->currentSpace][$paramName][$paramField];
        }       
        else if(array_key_exists($paramName, $this->variables[$this->currentSpace]) &&
                array_key_exists($paramField, $this->variables[$this->currentSpace][$paramName])){
            if($this->variables[$this->currentSpace][$paramName][$paramField] instanceof OrongoVariable == false) return new OrongoVariable(null);
            return $this->variables[$this->currentSpace][$paramName][$paramField];
        }
        else
           throw new OrongoScriptParseException("Unknown variable: " . $paramName . " (" . $paramField . ")");       
    }
    
    /**
     *@param var $paramName variable name
     * @return var Stored Var 
     */
    public function getRawVar($paramName){ 
        if(array_key_exists($paramName, $this->globalVars))
            return $this->globalVars[$paramName];
        else if(array_key_exists($paramName, $this->tempVars[$this->currentSpace]))
            return $this->tempVars[$this->currentSpace][$paramName];  
        else if(array_key_exists($paramName, $this->variables[$this->currentSpace]))
            return $this->variables[$this->currentSpace][$paramName];
        else
           throw new OrongoScriptParseException("Unknown variable: " . $paramName);       
    }
    
    

    
    /**
     * @return array all stored vars 
     */
    public function getVars(){
        return $this->variables;
    }
    
    /**
     * set vars
     * @param array $paramVars the stored vars 
     */
    public function setVars($paramVars){
        $this->variables = $paramVars;
    }
    
    /**
     * @param String $paramSpace Space name 
     */
    public function registerSpace($paramSpace){
        if(in_array($paramSpace, $this->spaces)) throw new OrongoScriptParseException("This space does exists already!");
        $this->spaces[count($this->spaces)] = $paramSpace;
        $this->functions[$paramSpace] = array();
        $this->customFunctions[$paramSpace] = array();
        $this->variables[$paramSpace] = array();
        $this->tempVars[$paramSpace] = array();
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
        $file = explode(".", $paramPackage);
        $file = end($file);
        $phpPath = dirname(__FILE__) . '/' .$path . "/orongopackage_" . $file . ".php";
        $oPath = dirname(__FILE__) . '/' .$path . "/" . $file . ".osc";
        $oScript = false;
        $path = $phpPath;
        if(!file_exists($path)){
            if(!file_exists($oPath))
                throw new OrongoScriptParseException("Invalid import, package not found!"); 
            $path = $oPath;
            $oScript = true;
        }
        if(!$oScript){
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
        }else{
            $p = new OrongoScriptParser(file_get_contents($path));
            $p->startParser($this, null, null, true, true);
        }
    }
    
    /**
     * Checks if an variable was stored with this name
     * @param String $paramName var name
     * @param String $paramField field of variable
     */
    public function isVar($paramName, $paramField = "__main__"){
        return (array_key_exists($paramName, $this->globalVars) && array_key_exists($paramField, $this->globalVars[$paramName]))||
               (array_key_exists($paramName, $this->variables[$this->currentSpace]) && array_key_exists($paramField, $this->variables[$this->currentSpace][$paramName])) ||
               (array_key_exists($paramName, $this->tempVars[$this->currentSpace]) && array_key_exists($paramField, $this->tempVars[$this->currentSpace][$paramName]));
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
            $return = $p->startParser($this, null, $arguments);
            $this->variables = $p->getRuntime()->getVars();
            return new OrongoVariable($return);
        }
    }
}

?>
