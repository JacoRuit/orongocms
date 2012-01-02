<?php

/**
 * Storable Interface
 * @author Jaco Ruit
 */
interface IStorable {
    
    /**
     * Gets the vars to store
     * @return array With the vars
     */
    public function toStorageSyntax();
    
    /**
     * Gets the stored vars
     * This will be called by CMS
     * @param array With the vars
     */
    public function __construct($paramArray);
}

?>
