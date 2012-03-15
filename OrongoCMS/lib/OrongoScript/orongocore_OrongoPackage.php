<?php

/**
 * OrongoPackage Class
 *
 * @author Jaco Ruit
 */
abstract class OrongoPackage {
    
    abstract function __construct($runtime);
    
    abstract function getFunctions();
    
}

?>
