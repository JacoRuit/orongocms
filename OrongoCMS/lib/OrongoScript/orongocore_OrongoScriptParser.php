<?php

/**
 * OrongoScriptParser Class
 *
 * @author Jaco Ruit
 */


class OrongoScriptParser {
    
    private $orongoScript;
    
    private $line;
    
    private $lines;
    
    private $lineparsed;
    
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
     */
    public function startParser(){
        $this->line = 0;
        $this->runtime = new OrongoScriptRuntime();
        $lines = explode(";", $this->orongoScript);
        $this->lines = $lines;
        $this->ifs = array();
        foreach($this->lines as &$line){
            $line = trim($line);
        }
        $c = 0;
        foreach($this->lines as $line){
            if($c == count($this->lines) - 1) break;
            $this->parseLine();
            $this->nextLine();
            $c++;
        }
    }
    
    /**
     * @param boolean $paramAddOne if true, +1 added. (only to show real line number, not array index) (default false)
     * @return int line number
     */
    public function getCurrentLine($paramAddOne = false){
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
        
        
        #IF
        if($this->lineStartsWith("if")){
            if(count($this->ifs) > 0){
                $curIf =  end($this->ifs);
                if($curIf['i']){
                    $new = array(
                        "y" => true,
                        "i" => true
                    );
                //$this->ifs = $this->ifs + $new;
                    $this->ifs[count($this->ifs)] = $new;
                    $this->lineparsed = true; 
                    return;
                }
            }
            $line = $this->lines[$this->getCurrentLine()];
            $f = str_replace("if", "", $line);
            $f = trim($f);
            if($f[0] != "(" || $f[strlen($f) -1] != ")") throw new OrongoScriptParseException("Invalid if statement");
            $f[0] = "";
            $f[strlen($f) - 1] = "";
            $if = new OrongoIfStatement($this->runtime, $f);
            $bool = $if->exec();
            if($bool){ 
                $new = array(
                  "y" => true,
                  "i" => false
                );
                //$this->ifs = $this->ifs + $new;
                $this->ifs[count($this->ifs)] = $new;
            }
            else{ 
                $new = array(
                    "y" => true,
                    "i" => true
                );
                //$this->ifs = $this->ifs + $new;
                $this->ifs[count($this->ifs)] = $new;
            }
        }
        
        else if($this->lines[$this->getCurrentLine()] == "endif"){
            $currentIfNo = count($this->ifs) - 1;
            if($currentIfNo < 0) throw new OrongoScriptParseException("Can not exit if function if you're not in an if function.");
            $currentIf = $this->ifs[$currentIfNo];
            /**if($currenIfNo > 0){
                if(!$currentIf['i']){
                    unset($this->ifs[$currentIfNo]);
                }
            }else{
                
            }*/
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
            $imp = trim(str_replace("import", "", $line));
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
            $line = trim(str_replace("let", "", $line));
            if(!stristr($line, "=")) throw new OrongoScriptParseException("Invalid let at line " . $this->getCurrentLine(true));
            $toLet = explode("=", $line);
            if(count($toLet) != 2) throw new OrongoScriptParseException("Invalid let at line " . $this->getCurrentLine(true));
            if(empty($toLet[0])) throw new OrongoScriptParseException("Invalid let (empty variable name) at line " . $this->getCurrentLine(true));
            if(empty($toLet[1])) throw new OrongoScriptParseException("Invalid let (empty value) at line " . $this->getCurrentLine(true));
            if(stristr($toLet[1], ")") && stristr($toLet[1], "(")){
                try{
                    $func = $this->parseFunction(trim($toLet[1]));
                    $toLet[1] = $this->runtime->execFunction($func['function_name'], $func['args']);
                }catch(Exception $e){}
            }
            $this->runtime->letVar(trim($toLet[0]), $toLet[1]);
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
            
            $line = trim($this->lines[$this->getCurrentLine()]);
            $line[0] = ""; $line[1] = "";
            $func = $this->parseFunction(trim($line));
            $this->runtime->execFunction($func['function_name'], $func['args']);
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
     * Parses a function
     * @param String $paramFunctionString string to parse
     * @return array With index function_name and args
     */
    private function parseFunction($paramFunctionString){
       $f = explode("(", $paramFunctionString);
       if($f[1][strlen($f[1]) - 1] != ")") throw new OrongoScriptParseException("Invalid call to function: " . $paramFunctionString);
       if(!stristr($f[1], ")")) throw new OrongoScriptParseException("Invalid call to function: " . $paramFunctionString);
       if(!$this->runtime->isFunction($f[0])) throw new OrongoScriptParseException("Call to undefined function: " . $f[0]);
       $rawArgs = str_replace(")", "", $f[1]);
       $args = explode(",", $rawArgs);
       foreach($args as &$arg){
           if($this->runtime->isVar($arg)){
               $arg = $this->runtime->getVar($arg)->get();
           }
       }
       return array("function_name" => $f[0], "args" => $args);
    }
}

?>
