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
        $runtime = new OrongoScriptRuntime();
        $lines = explode(";", $this->orongoScript);
        $this->lines = $lines;
        for($linec = 0; $linec < count($lines) - 1; $linec++){
            $this->line++;
            if(!is_string($lines[$linec])) continue;
            $line = trim($lines[$linec]);
            $words = explode(" ", $line);
            foreach($words as &$word){
                $word = trim($word);
            }
            $first = $words[0];
            if($first == "import"){
                if(!isset($words[1])) throw new OrongoScriptParseException("Invalid import");
                $runtime->import($words[1]);
            }
            else if($first == "let"){
                if(!isset($words[1])) throw new OrongoScriptParseException("Invalid let");
                if(!isset($words[2]) || $words[2] != "=") throw new OrongoScriptParseException("Invalid let");
                $toLet = explode("=", $line);
                $value = end($toLet);
                if(empty($value)) throw new OrongoScriptParseException("Invalid let: empty value!");
                $runtime->letVar($words[1], $value); 
            }
            else if($first[0] == "i" && $first[1] == "f"){
                $f = str_replace("if", "", $line);
                $f = trim($f);
                if($f[0] != "(" || $f[strlen($f) -1] != ")") throw new OrongoScriptParseException("Invalid if statement");
                $f[0] = "";
                $f[strlen($f) - 1] = "";
                $if = new OrongoIfStatement($runtime, $f, "");
                $if->exec();
            }
        }
    }
    
    /**
     * @param boolean $paramAddOne if true, +1 added. (only to show real line number, not array index) (default false)
     * @return int line number
     */
    public function getCurrentLine($paramAddOne = false){
        if($paramAddOne) return $this->line + 1;
        return $this->line;
    }
   
    /**
     * Checks if the line starts with specified string (CURRENT LINE)
     * @param String $paramString string to search
     * @return boolean indicating if the line started with this string
     */
    private function lineStartsWith($paramString){
        $line = $this->lines[$this->getCurrentLine() - 1];
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
        if($this->line >= count($this->lines) - 1) return;
        $this->lineparsed = false;
        $this->line++;
    }

    /**
     * Parses current line
     */
    private function parseLine(){
        if($this->lineparsed) return;
        
        $this->lineparsed = true;
    }
    
    /**
     * Checks if current line is parsed
     */
    private function lineIsParsed(){
        return $this->lineparsed;
    }
}

?>
