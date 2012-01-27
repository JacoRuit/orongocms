<?php

/**
 * echo OrongoScript function
 *
 * @author Jaco Ruit
 */
class FuncEcho extends OrongoFunction {
    

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
        return "echo";
    }
}

?>
