<?php
/**
 * ClassLoadException
 *
 * @author Ruit
 */
class ClassLoadException extends Exception{
    
    public function __construct($message = "", $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
    
    public function toString(){
        return "OrongoCMS Internal Error. " . __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}

?>
