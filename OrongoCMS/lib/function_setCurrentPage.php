<?php
/**
 * setCurrentPage function
 *
 * @author Jaco Ruit
 */

/**
* Defines the function getCurrentPage
* @param String $paramPage can be index,archive,article,page
*/

function setCurrentPage($paramPage){
    if(function_exists('getCurrentPage')) return;
    define("_orongo_current_page", $paramPage);
    @eval('function getCurrentPage(){ return _orongo_current_page; }' );
}
?>
