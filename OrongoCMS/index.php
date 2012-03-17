<?php
/**
 * @author Jaco Ruit
 */

require 'startOrongo.php';
startOrongo();


setCurrentPage('index');
$index = new IndexFrontend();
$index->main(array('time' => time()));
$index->render();

?>
