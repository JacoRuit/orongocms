<?php
/**
 * setDatabase function
 *
 * @author Jaco Ruit
 */

/**
* Defines the function getDatabase
* @param Database $paramDatabase db object
*/

function setDatabase($paramDatabase){
    if(function_exists('getDatabase')) return;
    if(($paramDatabase instanceof Database) == false) throw new IllegalArgumentException("Invalid argument, database object expected!");
    global $_orongo_database;
    $GLOBALS['_orongo_database'] = $paramDatabase->getMeekro();
    /**
     * @return MeekroDB
     */
    function getDatabase(){ return $GLOBALS['_orongo_database']; }
}
?>
