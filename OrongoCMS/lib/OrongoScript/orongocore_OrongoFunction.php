<?php

/**
 * OrongoFunction Class
 * 
 * @author Jaco Ruit
 */
abstract class OrongoFunction {
    
    abstract function __invoke($args);
    
    abstract function getShortname();
    
    abstract function getSpace();
}

?>
