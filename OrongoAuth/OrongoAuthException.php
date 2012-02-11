<?php

/**
 * OrongoAuthException Class
 *
 * @author Jaco Ruit
 */
class OrongoAuthException extends Exception {

    public function __construct($message = "", $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
    
    public function __toString(){
        return "OrongoAuth Error: " . $this->message;
    }
}

?>
