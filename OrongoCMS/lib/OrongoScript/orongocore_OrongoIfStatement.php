<?php

/**
 * OrongoIfStatement Class
 *
 * @author Jaco Ruit
 */
class OrongoIfStatement {
    
    private $runtime;
    private $expression;
    
    private static $expressions = array('==', '!=');
    
    /**
     * Init an if statement
     * @param OrongoScriptRuntime $paramRuntime pointer! &
     * @param String $paramExpression expression like ... == ...
     */
    public function __construct($paramRuntime, $paramExpression){
        $this->runtime = &$paramRuntime;
        $this->expression = $paramExpression;
    }
    
    
    /**
     * Executes logic if expression was true
     * @return boolean indicating if logic should be executed
     */
    public function exec(){
        $exp = null;
        $case = false;
        
        foreach(self::$expressions as $expression){
            $exp = explode($expression, $this->expression);
            if(count($exp) <= 1) continue;
            if(count($exp) >= 3) throw new OrongoScriptParseException("Invalid if statement!");
            $case = $expression;
            break;
        }
        
        //trim array
        $expnew =array();
        foreach($exp as &$f){
            $f = trim($f);
        }
        
        if(!$case) throw new OrongoScriptParseException("Invalid if statement!");
        
        $toCompare = array();
        
        //CHECK IF VAR
        if($this->runtime->isVar($exp[0]) && !isset($toCompare[0])){
            $toCompare[0] = $this->runtime->getVar($exp[0])->get();
        }
        
        if($this->runtime->isVar($exp[1]) && !isset($toCompare[1])){
            $toCompare[1] = $this->runtime->getVar($exp[1])->get();
        }
        
       
        //CHECK
        if(count($toCompare) == 2){
            switch($case){
                case "==":
                    if($toCompare[0] == $toCompare[1]){
                        return true;
                    }
                    return false;
               case "!=":
                   if($toCompare[0] != $toCompare[1]){
                       return true;
                   }
                   return false;
               default:
                   break;
            }
        }
        
        //CHECK IF FUNCTION
        if(stristr($exp[0], "(") && stristr($exp[0], ")") && !isset($toCompare[0])){
            $f = explode("(", $exp[0]);
            if(!stristr($f[1], ")")) throw new OrongoScriptParseException("Invalid call to function: " . $exp[0]);
            if(!$this->runtime->isFunction($f[0])) throw new OrongoScriptParseException("Call to undefined function: " . $f[0]);
            $toCompare[0] = $this->runtime->execFunction($f[0], str_replace(")", "", $f[1]))->get();
        }
        if(stristr($exp[1], "(") && stristr($exp[1], ")") && !isset($toCompare[1])){
            $f = explode("(", $exp[1]);
            if(!stristr($f[1], ")")) throw new OrongoScriptParseException("Invalid call to function: " . $exp[1]);
            if(!$this->runtime->isFunction($f[0])) throw new OrongoScriptParseException("Call to undefined function: " . $f[0]);
            $toCompare[1] = $this->runtime->execFunction($f[0], str_replace(")", "", $f[1]))->get();
        }
        
        //CHECK
        if(count($toCompare) == 2){
            switch($case){
                case "==":
                    if($toCompare[0] == $toCompare[1]){
                        return true;
                    }
                    return false;
               case "!=":
                   if($toCompare[0] != $toCompare[1]){
                       return true;
                   }
                   return false;
               default:
                   break;
            }
        }
        
        //IF COMPARES ARE NOT SET, IT WILL BE STRING COMPARING ORSTH!
        if(!isset($toCompare[0])) $toCompare[0] = $exp[0];
        if(!isset($toCompare[1])) $toCompare[1] = $exp[1];
        
        
        
        if(count($toCompare) == 2){
            switch($case){
                case "==":
                    if($toCompare[0] == $toCompare[1]){
                        return true;
                    }
                    return false;
               case "!=":
                   if($toCompare[0] != $toCompare[1]){
                       return true;
                   }
                   return false;
               default:
                   break;
            }
        }
        
        throw new OrongoScriptParseException("Invalid if statement!");
    }
}

?>
