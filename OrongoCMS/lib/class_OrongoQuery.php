<?php

/**
 * OrongoQuery object
 *
 * @author Jaco Ruit
 */
class OrongoQuery {
    
    /**
     * Construct a query.
     * You can obtain like latest post, all posts by specified user etc.
     * @param String $paramQuery query string
     */
    public function __construct($paramQuery){
        if(is_string($paramQuery) == false) throw new IllegalArgumentException("String ex")
    }
}

?>
