<?php

/**
 * OrongoScriptParser Class
 *
 * @author Jaco Ruit
 */


class OrongoScriptParser {
    
    private $orongoScript;
    
    private static $ifStructure = "if % * %";
    
    private static $ifOperators = array("=", "!=");
    
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
    public function parse(){
        $runtime = new OrongoScriptRuntime();
        $lines = explode(";", $this->orongoScript);
        $inExpression = false;
        $inIf = false;
        $inElseIf = false;
        for($linec = 0; $linec < count($lines) - 1; $linec++){
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
    
    private function throwError($paramLine, $paramWord){
        throw new OrongoScriptParseException("Invalid character found at line " . $paramLine . " -> " . $paramWord);
    }
    
    private function compareLine($paramLine, $paramComparison){
        $wordsLine = explode($paramLine, " ");
    }

}

?>
