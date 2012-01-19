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
    $pages = array('index','archive','article', 'page', 'admin_index', 'admin_terminal', 'admin_list');
    if(!in_array($paramPage, $pages)) throw new Exception("This page doesn't exist.");
    if(function_exists('getCurrentPage')) return;
    foreach($pages as $val){ define("_orongo_def" . $val,$val); }
    @eval('function getCurrentPage(){ return _orongo_def' . $paramPage . '; }' );
}
?>
