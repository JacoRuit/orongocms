<?php
/**
 * @author Jaco Ruit
 */

require 'startOrongo.php';
startOrongo('index');


$index = new IndexFrontend();
$index->main(array('time' => time()));
$index->render();

?>
