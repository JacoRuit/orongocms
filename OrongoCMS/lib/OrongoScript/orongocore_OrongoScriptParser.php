<?php

/**
 * OrongoScriptParser Class
 *
 * @author Jaco Ruit
 */


class OrongoScriptParser {
    
    private $orongoScript;
    
    private $line;
    
    private $definingFunction = null;
    
    private $lines;
    
    private $lineparsed;
    
    private static $expressions = array('==', '!=');
    
    /**
     * @var OrongoScriptRuntime
     */
    private $runtime;

    private $ifs;
    
    /**
     * Starts the parser
     * @param String $paramOrongoScript The OrongoScript to parse 
     */
    public function __construct($paramOrongoScript){
         if(is_string($paramOrongoScript) == false)
             throw new IllegalArgumentException("Invalid argument, string expected.");
         $this->orongoScript = $paramOrongoScript;
     
    }
    
    /**
     * Parses the script
     * @param OrongoScriptRuntime runtime optional
     * @param array $paramTempVars Temporary vars for the runtime (optional)
     * @return var the returned var from script
     */
    public function startParser($paramRuntime = null, $paramTempVars = null){
        $this->line = 0;
        if($paramRuntime instanceof OrongoScriptRuntime)
            $this->runtime = $paramRuntime;
        else $this->runtime = new OrongoScriptRuntime();
        if(is_array($paramTempVars))
            foreach($paramTempVars as $tempName => $tempVar){
                $this->runtime->letTempVar($tempName, $tempVar);
            }  
        $lines = explode(";", $this->orongoScript);
        if(!defined('hacked')){
            $this->runtime->registerFunction(array(
                'name' => 'hack',
                'param_count' => 0,
                'logic' => 'let h = make_msgbox(bla); do show_msgbox(h));        ',
                'params' => '1'
            ));
            define('hacked',true);
        }
        $this->lines = $lines;
        $this->ifs = array();
        foreach($this->lines as &$line){
            $line = trim($line);
        }
        $c = 0;
        foreach($this->lines as $line){
            if($c == count($this->lines) - 1) break;
            $a = $this->parseLine();
            if(is_array($a) && isset($a['action'])){
                switch($a['action']){
                    case "terminated":
                        return $a['value'];
                        break;
                    default:
                        break;
                }
            }
            $this->nextLine();
            $c++;
        }
        
        
    }
    
    /**
     * @return int line number
     */
    public function getCurrentLine(){
        return $this->line;
    }
   
    /**
     * Checks if the line starts with specified string (CURRENT LINE)
     * @param String $paramString string to search
     * @return boolean indicating if the line started with this string
     */
    private function lineStartsWith($paramString, $paramLine = null){
        if($paramLine == null) $line = $this->lines[$this->getCurrentLine()];
        else $line = $paramLine;
        $c = 0;
        for($i =0; $i < strlen($line) - 1; $i++){
            $string = $paramString[$c];
            $c++;
            if($line[$i] == $string){
                if($i == strlen($paramString) - 1) return true; 
                continue;
            }else return false;
        }
        return false;
    }
    
    /**
     * Move to next line
     */
    private function nextLine(){
        //if($this->line == count($this->lines)) return false;
        $this->lineparsed = false;
        $this->line++;
        return true;
    }

    /**
     * Parses current line
     */
    private function parseLine(){
       
        if($this->lineparsed) return;
        $line =  trim($this->lines[$this->getCurrentLine()]);
        
        if($this->definingFunction != null && $line != "end function" )
            $this->definingFunction["logic"] .= $line . ";";
        
        else if($this->runtime->getCurrentSpace() == null && !$this->lineStartsWith("import") && !$this->lineStartsWith("space"))
            throw new OrongoScriptParseException("Can not execute: ". $line . " while not in space!");
        
        #SPACE
        else if($this->lineStartsWith("space")){
            if(!empty($this->ifs) || $this->definingFunction != null)
                throw OrongoScriptParseException("Can't create a new space if you are still in a function/if.");
            $spaceName = trim(preg_replace("/space/", "", $line, 1));
            $this->runtime->registerSpace($spaceName);
            $this->runtime->setCurrentSpace($spaceName);
        }
        
        else if($line == "end space"){
            if(!empty($this->ifs) || $this->definingFunction != null)
                throw OrongoScriptParseException("Can't end the space if you are still in a function/if.");
            $this->runtime->setCurrentSpace(null);
        }
        
        #IF
        else if($this->lineStartsWith("if")){
            if(count($this->ifs) > 0){
                $curIf =  end($this->ifs);
                if($curIf['i']){
                    $new = array(
                        "y" => true,
                        "i" => true
                    );
                    $this->ifs[count($this->ifs)] = $new;
                    $this->lineparsed = true; 
                    return;
                }
            }
            $line = $this->lines[$this->getCurrentLine()];
            $f = preg_replace("/if/", "", $line, 1);
            $f = trim($f);
            if($f[0] != "(" || $f[strlen($f) -1] != ")") throw new OrongoScriptParseException("Invalid if statement");
            $f[0] = "";
            $f[strlen($f) - 1] = "";
            $bool = $this->parseIf($f);
            if($bool){ 
                $new = array(
                  "y" => true,
                  "i" => false
                );
                $this->ifs[count($this->ifs)] = $new;
            }
            else{ 
                $new = array(
                    "y" => true,
                    "i" => true
                );
                $this->ifs[count($this->ifs)] = $new;
            }
        }
        
        else if($line == "end if"){
            $currentIfNo = count($this->ifs) - 1;
            if($currentIfNo < 0) throw new OrongoScriptParseException("Can not exit if function if you're not in an if function.");
            unset($this->ifs[$currentIfNo]);
        }
        
        #IMPORT
        else if($this->lineStartsWith("import")){
            if(count($this->ifs) > 0){
                $curIf=  end($this->ifs);
                if($curIf['i']){
                    $this->lineparsed = true; 
                    return;
                }
            }
            $line = $this->lines[$this->getCurrentLine()];
            $imp = trim(preg_replace("/import/", "", $line, 1));
            $this->runtime->import($imp);
        }
        
        #LET
        else if($this->lineStartsWith("let")){
            if(count($this->ifs) > 0){
                $curIf=  end($this->ifs);
                if($curIf['i']){
                    $this->lineparsed = true; 
                    return;
                }
            }
            $line = $this->lines[$this->getCurrentLine()];
            $line = trim(preg_replace("/let/", "", $line,1));
            if(!stristr($line, "=")) throw new OrongoScriptParseException("Invalid let at line " . $this->getCurrentLine(true));
            $toLet = explode("=", $line);
            if(count($toLet) != 2) throw new OrongoScriptParseException("Invalid let at line " . $this->getCurrentLine(true));
            if(empty($toLet[0])) throw new OrongoScriptParseException("Invalid let (empty variable name) at line " . $this->getCurrentLine(true));
            if(empty($toLet[1])) throw new OrongoScriptParseException("Invalid let (empty value) at line " . $this->getCurrentLine(true));
            $this->runtime->letVar(trim($toLet[0]),$this->parseVar($toLet[1])->get());
        }
        
        #DO
        else if($this->lineStartsWith("do")){
            if(count($this->ifs) > 0){
                $curIf=  end($this->ifs);
                if($curIf['i']){
                    $this->lineparsed = true; 
                    return;
                }
            }
            
            $line[0] = ""; $line[1] = "";
            $func = $this->parseFunction(trim($line));
            $this->runtime->execFunction($func['space'], $func['function_name'], $func['args']);
        }
        
        #FUNCTION
        else if($this->lineStartsWith("function")){
            if(count($this->ifs) > 0){
                $curIf=  end($this->ifs);
                if($curIf['i']){
                    $this->lineparsed = true; 
                    return;
                }
            }
            $line = trim(preg_replace("/function/", "",$this->lines[$this->getCurrentLine()], 1));
            if(substr_count($line, ")") != 1 || substr_count($line, "(") != 1 || strpos(")", $line) < strpos("(", $line))
                    throw new OrongoScriptParseException("Invalid function declaration.");
            $l = explode("(", $line);
            if(empty($l[0])) throw new OrongoScriptParseException("Invalid function declaration: no function name!");
            $funcName = trim($l[0]);
            $l[1] = trim(str_replace(")", "", $l[1]));
            $rawParams = str_replace(" ", "", $l[1]);
            $params = empty($rawParams) ? null : explode(",", $rawParams);
            $this->definingFunction = array(
                'name' => $funcName,
                'param_count' => $params == null ? 0 : count($params),
                'logic' => '',
                'params' => $params
            );
        }
        
        else if($line == "end function"){
            if($this->definingFunction == null)
                throw OrongoScriptParseException("Invalid statement end function, no function is being defined!");
            $this->runtime->registerFunction($this->definingFunction);   
            $this->definingFunction = null;
        }
       
        #RETURN
        else if($this->lineStartsWith("return")){
            $line = trim(preg_replace("/return/", "",$this->lines[$this->getCurrentLine()], 1));
            return array(
                'action' => 'terminated',
                'value' => $this->parseVar($line)->get()
            );
        }
        else if($line == "return"){
            return array(
                'action' => 'terminated',
                'value' => 1
            );
        }
        
        #NOT RECOGNIZED
        else throw new OrongoScriptParseException("Invalid characters at line " . $this->getCurrentLine(true));
 
    }
    
    /**
     * Checks if current line is parsed
     */
    public function lineIsParsed(){
        return $this->lineparsed;
    }
    
    
    /**
     * @return OrongoScriptRuntime 
     */
    public function getRuntime(){
        return $this->runtime;
    }
    
    
    /**
     * Parses a function
     * @param String $paramFunctionString string to parse
     * @return array With index function_name and args
     */
    public  function parseFunction($paramFunctionString){
       if(!stristr($paramFunctionString, ".")) $paramFunctionString = $this->runtime->getCurrentSpace() . "." . $paramFunctionString;
       $space = explode(".", $paramFunctionString, 2);
       $space = $space[0];
       $f = explode("(", $paramFunctionString, 2);
       $f[0] = preg_replace('/' . $space . './', "", $f[0],1);
       if($f[1][strlen($f[1]) - 1] != ")") throw new OrongoScriptParseException("Invalid call to function: " . $paramFunctionString);
       if(!stristr($f[1], ")")) throw new OrongoScriptParseException("Invalid call to function: " . $paramFunctionString);
       $f[0] = trim($f[0]);
       if(!$this->runtime->isFunction($space, $f[0])) throw new OrongoScriptParseException("Call to undefined function: " . $f[0]);
       $rawArgs = strrev(preg_replace('/\)/', "", strrev($f[1]), 1));
       $args = explode(",", $rawArgs);
       foreach($args as &$arg){
           if(!empty($arg))
            $arg = $this->parseVar($arg)->get();
       }
       return array("function_name" => $f[0], "args" => $args, "space" => $space);
    }
    
    
    /**
     * Parses a string, and recognises function calls/variables 
     * @param String $paramString string to get var from
     * @return OrongoVariable 
     */
    public function parseVar($paramString){
        $string = trim($paramString);
        $stringRev = strrev($string);
        if(stristr($string, '"') && substr_count($string, '"') >= 2 && strpos($string, '"') == 0 && strpos($stringRev, '"') == 0)
            return new OrongoVariable(preg_replace('/"/', "",strrev(preg_replace('/"/',"", $stringRev, 1))));
        if($this->runtime->isVar($string)) return $this->runtime->getVar($string);
        if(stristr($string, ")") && stristr($string, ")")){
           try{
               $f = $this->parseFunction($string);
               return $this->runtime->execFunction($f['space'], $f['function_name'], $f['args']);
           }catch(Exception $e){ if($e->getCode() >= 0) throw $e; } 
        }
        if(is_numeric($string)) return new OrongoVariable(intval($string));
        throw new OrongoScriptParseException("Can not parse variable. Invalid string: '" . $string . "'");
    }
    
    
    /**
     * Parses an if statement and returns a bool
     * @param String $paramStatement the statement
     * @return boolean indicating if logic should be executed
     */
    public function parseIf($paramStatement){
        $exp = null;
        $case = false;
        
        foreach(self::$expressions as $expression){
            $exp = explode($expression, $paramStatement);
            if(count($exp) <= 1) continue;
            if(count($exp) >= 3) throw new OrongoScriptParseException("Invalid if statement!");
            $case = $expression;
            break;
        }

        $toCompare = array( 0 => $this->parseVar($exp[0])->get(), 1 => $this->parseVar($exp[1])->get());
        
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
