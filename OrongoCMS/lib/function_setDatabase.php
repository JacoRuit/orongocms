<?php
/**
 * setDatabase function
 *
 * @author Jaco Ruit
 */

/**
* Defines the function setDatabase
* @param Database $paramDatabase db object
*/

function setDatabase($paramDatabase){
    if(($paramDatabase instanceof Database) == false) throw new IllegalArgumentException("Invalid argument, database object expected!");
    global $_orongo_database;
    $GLOBALS['_orongo_database'] = &$paramDatabase;
    @eval('function getDatabase(){ return $GLOBALS[\'_orongo_database\']; }');
}
?>
